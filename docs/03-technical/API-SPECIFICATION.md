# NAVORA — HTTP Interface Specification

| Field | Value |
|---|---|
| Document ID | NAVORA-API |
| Version | 0.2.0 (Consolidated) |
| Date | 2026-11-22 |
| Status | **PROPOSED — no routes or handlers exist** |
| Related | `TECHNICAL-ARCHITECTURE.md`, `SRS.md`, `SECURITY-GUIDELINES.md` |

> **There is no JSON/REST API in the MVP** (DEC-017). NAVORA is server-rendered: the "API" is the set of HTTP routes that accept HTML form submissions and return HTML or redirects. `NAVORA/routes/web.php` is empty; nothing below is implemented.
>
> Authentication is **required** (DEC-003). All routes except public auth pages require a valid session.

---

## 1. Conventions

| Aspect | Convention |
|---|---|
| Base URL | Application root (e.g., `/`) |
| Content type | `application/x-www-form-urlencoded` (forms) |
| Success | `303 See Other` redirect (POST→GET) + flash message |
| Validation failure | `200` re-render of the form with errors |
| Not found / not owned / archived (outside archive view) | `404` |
| CSRF failure | `419` |
| Server error | `500` |
| Auth required | Redirect to `/masuk` with return path |
| IDs | Positive integers; invalid → `404` |
| Escaping | All output escaped in views |
| CSRF | Hidden `_token` field on every POST |
| Language | User-facing messages in Bahasa Indonesia (DEC-013) |
| Money | Whole-rupiah integers in requests (DEC-007) |

**Why 303:** prevents duplicate submissions on refresh and keeps URLs clean.

---

## 2. Endpoint Table

### 2.1 Public pages

| ID | Method | Path | Handler | Purpose |
|---|---|---|---|---|
| EP-01 | GET | `/masuk` | AuthController@showLogin | Login form |
| EP-02 | POST | `/masuk` | AuthController@login | Authenticate |
| EP-03 | GET | `/daftar` | AuthController@showRegister | Registration form |
| EP-04 | POST | `/daftar` | AuthController@register | Create account + send verification |
| EP-05 | GET | `/verifikasi-email/{token}` | AuthController@verifyEmail | Confirm email |
| EP-06 | POST | `/verifikasi-email/kirim-ulang` | AuthController@resendVerification | Resend verification |
| EP-07 | GET | `/lupa-kata-sandi` | PasswordResetController@showRequest | Request reset form |
| EP-08 | POST | `/lupa-kata-sandi` | PasswordResetController@send | Send reset link |
| EP-09 | GET | `/atur-ulang-kata-sandi/{token}` | PasswordResetController@showReset | New password form |
| EP-10 | POST | `/atur-ulang-kata-sandi` | PasswordResetController@reset | Set new password |
| EP-11 | POST | `/keluar` | AuthController@logout | End session (POST + CSRF) |

### 2.2 Authenticated pages

| ID | Method | Path | Handler | Purpose |
|---|---|---|---|---|
| EP-12 | GET | `/` | DashboardController@index | Dashboard |
| EP-13 | GET | `/goals` | GoalController@index | Goal list (`?q=&sort=&status=&page=`) |
| EP-14 | GET | `/goals/create` | GoalController@create | New goal form |
| EP-15 | GET | `/goals/{id}` | GoalController@show | Goal detail |
| EP-16 | GET | `/goals/{id}/edit` | GoalController@edit | Edit form |
| EP-17 | GET | `/goals/{id}/savings` | SavingsController@index | History (`?page=`) |
| EP-18 | GET | `/arsip` | GoalController@archived | Archived goals list |

### 2.3 Mutations

| ID | Method | Path | Handler | Purpose |
|---|---|---|---|---|
| EP-19 | POST | `/goals` | GoalController@store | Create goal |
| EP-20 | POST | `/goals/{id}` | GoalController@update | Update goal |
| EP-21 | POST | `/goals/{id}/arsipkan` | GoalController@archive | Archive goal (confirmed) |
| EP-22 | POST | `/goals/{id}/pulihkan` | GoalController@restore | Restore goal |
| EP-23 | POST | `/goals/{id}/savings` | SavingsController@store | Add deposit/withdrawal |
| EP-24 | POST | `/savings/{id}` | SavingsController@update | Edit transaction (`OPEN-01`) |
| EP-25 | POST | `/savings/{id}/delete` | SavingsController@destroy | Delete transaction (`OPEN-02`) |

### 2.4 Not present

No `PUT`, `PATCH`, or `DELETE` verbs (HTML forms cannot send them). No `/api/*` namespace. No JSON responses. No webhooks. No third-party integrations.

---

## 3. Request Contracts

### EP-04 — Register

```http
POST /daftar
Content-Type: application/x-www-form-urlencoded

_token=<csrf>&name=Rafi&email=rafi@example.com&password=secret123&password_confirmation=secret123
```

| Field | Required | Rules | Error message |
|---|---|---|---|
| `_token` | Yes | Valid CSRF token | "Sesi Anda berakhir. Silakan coba lagi." |
| `name` | Yes | 1–80 chars after trim | "Nama wajib diisi." / "Nama maksimal 80 karakter." |
| `email` | Yes | Valid, unique, normalized | "Email tidak valid." / "Email sudah terdaftar." |
| `password` | Yes | Minimum length `OPEN-10` | "Kata sandi terlalu pendek." |
| `password_confirmation` | Yes | Must match | "Konfirmasi kata sandi tidak cocok." |

**Responses**

| Condition | Status | Body |
|---|---|---|
| Created | 303 → `/verifikasi-email/kirim-ulang` or limited dashboard | Flash: "Akun dibuat. Periksa email Anda." |
| Invalid | 200 | Form with errors, values preserved except passwords |
| CSRF invalid | 419 | Error page |

### EP-19 — Create Goal

```http
POST /goals
Content-Type: application/x-www-form-urlencoded

_token=<csrf>&name=Yamaha+Aerox&target_price=30000000&target_date=2026-12-20&description=DP+motor
```

| Field | Required | Rules | Error message |
|---|---|---|---|
| `_token` | Yes | Valid CSRF token | "Sesi Anda berakhir. Silakan coba lagi." |
| `name` | Yes | 1–80 chars, unique per account | "Nama goal wajib diisi." / "Nama goal sudah digunakan." |
| `target_price` | Yes | Integer digits, > 0, ≤ max (`OPEN`) | "Masukkan nominal yang valid." / "Nominal harus lebih dari nol." |
| `target_date` | No | Valid date; past allowed | "Masukkan tanggal yang valid." |
| `description` | No | ≤ 500 chars (`OPEN-03`) | "Deskripsi maksimal 500 karakter." |

**Responses**

| Condition | Status | Body |
|---|---|---|
| Created | 303 → `/goals/{id}` | Flash: "Goal berhasil dibuat." |
| Invalid | 200 | Form with errors, values preserved |
| CSRF invalid | 419 | Error page |
| Not authenticated | 303 → `/masuk` | — |

### EP-23 — Add Savings (Deposit or Withdrawal)

```http
POST /goals/12/savings
Content-Type: application/x-www-form-urlencoded

_token=<csrf>&direction=deposit&amount=1500000&occurred_on=2026-11-22&note=Bonus
```

| Field | Required | Rules |
|---|---|---|
| `direction` | Yes | `deposit` or `withdrawal` |
| `amount` | Yes | Integer digits, > 0, ≤ max (`OPEN`) |
| `occurred_on` | Yes | Valid date, not in the future (proposal), WIB |
| `note` | No | ≤ 255 chars (`OPEN-04`) |

**Responses:** 303 → `/goals/12` with "Tabungan tercatat." · 200 with errors · 404 if goal missing/not owned/archived · 419 CSRF.

**Note:** a withdrawal larger than the balance is accepted; the balance becomes negative (DEC-025).

### EP-21 — Archive Goal

```http
POST /goals/12/arsipkan
Content-Type: application/x-www-form-urlencoded

_token=<csrf>&confirm=1
```

**Responses:** 303 → `/goals` with "Goal diarsipkan." · 404 · 419. Transactions are retained; the goal appears in `/arsip`.

### EP-22 — Restore Goal

```http
POST /goals/12/pulihkan
Content-Type: application/x-www-form-urlencoded

_token=<csrf>&confirm=1
```

**Responses:** 303 → `/arsip` or `/goals/12` with "Goal dipulihkan." · 404 · 419.

---

## 4. Query Parameters

| Parameter | Endpoint | Values | Default | Invalid behavior |
|---|---|---|---|---|
| `q` | EP-13 | text ≤ 80 chars | empty | Treated as empty |
| `sort` | EP-13 | `recent`, `target_date`, `progress` | `recent` | Fall back to `recent` |
| `status` | EP-13 | `berlangsung`, `tercapai`, `all` | `all` | Fall back to `all` |
| `page` | EP-13, EP-17, EP-18 | integer ≥ 1 | `1` | Clamp to page 1 / last page |

Archived goals are excluded from EP-13 regardless of `status`. Unknown parameters are ignored. Query strings never carry secrets.

---

## 5. Status Codes & Error Payloads

| Code | When | Body |
|---|---|---|
| 200 | Page or form re-render | HTML |
| 303 | Successful mutation | `Location` header |
| 404 | Missing, unauthorized, or archived record (outside archive view) | HTML error page |
| 419 | CSRF/session failure | HTML error page + retry link |
| 429 | Rate limited (`OPEN-19`) | HTML error page |
| 500 | Unhandled failure | HTML error page + reference ID |
| 503 | Maintenance (`OPEN`) | HTML error page |

**No 403 is used.** Unauthorized access is indistinguishable from a missing record to avoid leaking existence. Error pages contain no stack traces, SQL, or filesystem paths.

---

## 6. Validation Response Shape

Because rendering is server-side, errors are delivered as:

```php
// Illustrative shape, not implemented
$errors = [
  'name' => 'Nama goal wajib diisi.',
  'target_price' => 'Nominal harus lebih dari nol.',
];
```

Views render an error summary plus per-field messages. Values are preserved except password fields.

---

## 7. Authorization Rules

| Rule | Enforcement |
|---|---|
| Goal belongs to current user | `WHERE id = ? AND user_id = ?` |
| Transaction belongs to a goal owned by current user | Join/ownership check before mutation |
| Unauthorized access returns 404 | Avoids leaking record existence |
| Archived goals are not mutable from the active UI | Archive state check |
| Logout requires POST + CSRF | Prevents forced logout via GET |
| Verification/reset tokens are single-use | `used_at` + `expires_at` checks |

---

## 8. Caching & Headers

| Header | Value |
|---|---|
| `Cache-Control` | `no-store` for authenticated pages; static assets may be cached |
| `Content-Type` | `text/html; charset=UTF-8` |
| `X-Content-Type-Options` | `nosniff` |
| `Referrer-Policy` | `strict-origin-when-cross-origin` |
| `X-Frame-Options` | `DENY` (or CSP `frame-ancestors 'none'`) |
| `Content-Security-Policy` | `OPEN` — must allow self-hosted assets only |

---

## 9. Versioning

Not applicable. This is a server-rendered application, not a versioned API. Any future JSON API must be documented separately and approved.

---

## 10. Traceability

| Endpoint | Requirement |
|---|---|
| EP-01..EP-11 | FR-001..FR-003, FR-036, FR-037 |
| EP-12 | FR-026..FR-029 |
| EP-13..EP-16 | FR-005..FR-015, FR-039, FR-044 |
| EP-17, EP-23..EP-25 | FR-016..FR-025, FR-040, FR-041 |
| EP-18, EP-21, EP-22 | FR-038, FR-045 |
| Error behavior | FR-030..FR-032, SRS §7 |

---

## 11. Change History (0.2.0)

- Authentication routes added and made mandatory (DEC-003).
- Email verification and password reset routes added (DEC-015).
- Delete goal replaced by archive/restore routes (DEC-008).
- Transaction requests now carry `direction` (DEC-004).
- Money is a whole-rupiah integer (DEC-007).
- Goal list pagination is numbered, 20 per page (DEC-009).
- `status` query values use Indonesian status names.
- 403 removed; unauthorized access returns 404.
