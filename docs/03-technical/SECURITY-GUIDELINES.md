# NAVORA — Security Guidelines

| Field | Value |
|---|---|
| Document ID | NAVORA-SEC |
| Version | 0.1 (Draft) |
| Date | 2026-11-22 |
| Status | **PROPOSED — requires approval; no controls implemented** |
| Related | `TECHNICAL-ARCHITECTURE.md`, `API-SPECIFICATION.md`, `SRS.md` |

> The application is an empty scaffold. **No security control exists yet.** This document defines requirements for implementation; it is not a claim of security.

---

## 1. Scope & Data Classification

| Data | Sensitivity | Notes |
|---|---|---|
| Account email, name | Personal | Regulated by basic privacy expectations |
| Password hash | Highly sensitive | Never logged or displayed |
| Goals and target prices | Personal financial | Access restricted to owner |
| Savings transactions | Personal financial | Access restricted to owner |
| Session identifiers | Sensitive | HttpOnly cookie |
| Server logs | Internal | Must exclude secrets and full financial payloads |

No payment card data, bank credentials, or government identifiers are collected. If that changes, this document must be revised before implementation.

---

## 2. Threat Model (Practical)

| Threat | Vector | Impact | Primary control |
|---|---|---|---|
| SQL injection | Form fields, query params | Data breach/corruption | Prepared statements |
| XSS | Goal names, notes | Session theft, defacement | Output escaping + CSP |
| CSRF | Forged form posts | Unauthorized changes | CSRF tokens |
| Broken access control (IDOR) | Guessing `/goals/{id}` | Access to others' finances | Ownership-scoped queries, 404 |
| Credential stuffing | Login form | Account takeover | Rate limiting (`TBD`), strong hashing |
| Session fixation | Pre-set session ID | Hijack | Regenerate on login |
| Sensitive data exposure | Misconfigured web root | Source/SQL download | Correct document root |
| Mass assignment | Extra POST fields | Privilege/data tampering | Allow-list fields |
| Clickjacking | Iframe embedding | Tricked actions | `X-Frame-Options`/CSP |
| Enumeration | Login/registration errors | Privacy leak | Generic messages |
| Brute force | Login | Takeover | Throttling (`TBD`) |
| Dependency risk | None currently | — | No dependencies installed |

---

## 3. Authentication

Authentication is **required** (DEC-003). Email verification and password reset are approved (DEC-015); their delivery and token details remain `OPEN`.

| ID | Requirement | Status |
|---|---|---|
| SEC-A1 | Passwords hashed with `password_hash()`; Argon2id preferred, bcrypt acceptable | `PROPOSED` |
| SEC-A2 | Verify with `password_verify()`; never compare hashes manually | `PROPOSED` |
| SEC-A3 | Minimum password length `OPEN-10` (proposal: 8); maximum 72 bytes (bcrypt limit) or 128 chars with Argon2id | `OPEN` |
| SEC-A4 | Generic login failure message ("Email atau kata sandi salah") | `PROPOSED` |
| SEC-A5 | Regenerate session ID on login and logout | `PROPOSED` |
| SEC-A6 | Email verification is required (DEC-015); whether login is blocked before verification is `OPEN-08` | `APPROVED` (behavior) / `OPEN` (enforcement) |
| SEC-A7 | Password reset uses a time-limited, single-use email link; lifetime `OPEN-09` (proposal: 60 minutes) | `APPROVED` (behavior) / `OPEN` (lifetime) |
| SEC-A8 | No "remember me" until token rotation is designed | `OPEN-11` |
| SEC-A9 | Account deletion policy | `OPEN` (not in MVP) |
| SEC-A10 | Verification and reset tokens are random (≥128 bits), stored hashed, single-use, and invalidated on use | `PROPOSED` |

---

## 4. Sessions & Cookies

| ID | Requirement |
|---|---|
| SEC-S1 | Session cookie `HttpOnly` = true |
| SEC-S2 | `SameSite=Lax` (or `Strict` if flows allow) |
| SEC-S3 | `Secure` = true in production (HTTPS only) |
| SEC-S4 | Session timeout and idle timeout defined (`TBD` values) |
| SEC-S5 | Logout invalidates server-side session data, not just the cookie |
| SEC-S6 | Session storage path outside the public webroot |
| SEC-S7 | No session identifiers in URLs |

---

## 5. Authorization

| ID | Requirement |
|---|---|
| SEC-Z1 | Every goal/transaction query is scoped by the authenticated owner |
| SEC-Z2 | Missing and unauthorized records both return 404 |
| SEC-Z3 | Authorization is enforced server-side, never by hiding UI |
| SEC-Z4 | IDs are validated as positive integers before querying |
| SEC-Z5 | No admin/superuser role exists in MVP |

---

## 6. Input Handling & Output Encoding

| ID | Requirement |
|---|---|
| SEC-I1 | All SQL uses prepared statements with bound parameters |
| SEC-I2 | No dynamic SQL built from user input (including `ORDER BY`; use allow-lists) |
| SEC-I3 | All view output escaped (`htmlspecialchars` with `ENT_QUOTES`) unless explicitly trusted markup |
| SEC-I4 | Rich text/HTML input is not accepted in MVP |
| SEC-I5 | Numeric fields parsed strictly; reject `NaN`, `INF`, locale separators unless intended |
| SEC-I6 | Mass assignment prevented by explicit field allow-lists |
| SEC-I7 | File uploads are not supported in MVP |
| SEC-I8 | Request size limits configured at the web server |
| SEC-I9 | Unicode input normalized where comparison matters (email) |

---

## 7. CSRF

| ID | Requirement |
|---|---|
| SEC-C1 | Every state-changing request (POST) carries a per-session token |
| SEC-C2 | Tokens compared with `hash_equals()` |
| SEC-C3 | Tokens rotated on login |
| SEC-C4 | Failure returns 419 with a retry path |
| SEC-C5 | Logout is POST-only |

---

## 8. Security Headers

```http
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: camera=(), microphone=(), geolocation=()
Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self'; script-src 'self'; font-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'
Strict-Transport-Security: max-age=31536000; includeSubDomains   (HTTPS only)
```

**Caveat:** inline scripts/styles must be avoided (or nonce-based) for this CSP to hold. Self-hosting fonts/icons is consistent with it.

---

## 9. Secrets & Configuration

| ID | Requirement |
|---|---|
| SEC-K1 | `.env` (or equivalent) is never committed; `.env.example` holds placeholders |
| SEC-K2 | DB credentials are read from environment, not hard-coded |
| SEC-K3 | Production errors are not displayed to users |
| SEC-K4 | Debug mode is off in production |
| SEC-K5 | Logs exclude passwords, tokens, and session IDs |
| SEC-K6 | No secrets in JavaScript or HTML comments |

---

## 10. Error Handling & Logging

- Log: timestamp, request ID, route, HTTP status, exception class/message (server-side only).
- Do not log: passwords, CSRF tokens, session IDs, full request bodies containing financial data.
- User-facing errors: generic, actionable, with a reference ID.
- Alerting: `TBD`.

---

## 11. Privacy

| ID | Requirement |
|---|---|
| SEC-P1 | No third-party analytics/trackers by default (NFR-011) |
| SEC-P2 | No data sold or shared |
| SEC-P3 | Users can archive goals and retain history (DEC-008); full account deletion is not in MVP | `APPROVED` (archive) / `OPEN` (account deletion) |
| SEC-P4 | Data retention policy `TBD` |
| SEC-P5 | Privacy notice page `TBD` |

---

## 12. Deployment Security Checklist

- [ ] Document root points to public assets only (not `app/`, `config/`, `database/`)
- [ ] `.env`, `*.sql`, and logs are not web-accessible
- [ ] HTTPS enforced; HSTS enabled
- [ ] Directory listing disabled
- [ ] PHP version supported and patched
- [ ] `display_errors=Off` in production
- [ ] Database user has least privilege (no `DROP`/`GRANT`)
- [ ] Backups encrypted and access-controlled
- [ ] Default credentials absent
- [ ] Security headers verified with a response inspection

> **Known risk:** the current scaffold's entry point is `NAVORA/index.php` while sensitive folders sit alongside it. Deploying with `NAVORA/` as the document root would expose `config/database.php` and `database/*.sql`. Resolve before deployment (TR-03).

---

## 13. Security Testing Requirements

| Test | Expected |
|---|---|
| SQL injection payloads in every text field | Stored/rendered literally; no query error |
| XSS payload in goal name and note | Escaped in list, detail, and table |
| CSRF: submit form without token | 419, no state change |
| IDOR: access another user's goal ID | 404 |
| Session fixation: pre-set ID then login | New ID issued |
| Logout then back button | No authenticated content |
| Password storage inspection | Hash only, never plaintext |
| Verification token reuse | Second use rejected; expired token rejected |
| Reset token reuse | Second use rejected; sessions invalidated after reset |
| Error page inspection | No stack trace/paths |
| Header inspection | Security headers present |
| Upload attempt | Rejected (not supported) |

Status: **NOT RUN** — no implementation exists.

---

## 14. Open Security Decisions

| ID | Question | Owner |
|---|---|---|
| SEC-Q1 | ~~Accounts required?~~ Resolved: multi-user (DEC-003) | — |
| SEC-Q2 | Session lifetime values | `OPEN-11` |
| SEC-Q3 | Rate limiting mechanism | `OPEN-19` |
| SEC-Q4 | CSP with self-hosted assets only? | `TECH-12` |
| SEC-Q5 | Privacy notice and retention policy | `OPEN-20` |
| SEC-Q6 | Password policy details | `OPEN-10` |
| SEC-Q7 | Verification/reset token lifetime and email provider | `OPEN-09`, `TECH-06` |
