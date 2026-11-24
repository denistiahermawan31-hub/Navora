<?php
// NAVORA — app/models/Goal.php
// Domain presentation helpers for a dream goal.
//
// This file intentionally contains no SQL. Persistence depends on the Owner's
// Database.php, schema, and controller contract, which are not available yet.
// Every calculation below follows the approved rules:
//   DEC-006 / DEC-023 / DEC-025 / RULE-020..RULE-029 / RULE-054.
//
// The present() method accepts either a raw database row or an already
// prepared view row. It understands the documented snake_case columns from
// DATABASE-DESIGN.md and documented camelCase presentation alternatives, so
// the Owner can wire a controller without changing this file.

namespace NAVORA\Models;

final class Goal
{
    public const STATUS_ACTIVE = 'Berlangsung';
    public const STATUS_COMPLETE = 'Tercapai';

    /**
     * Return a complete, presentation-safe goal row.
     *
     * Expected input keys (any of the aliases is accepted):
     *   id, name, target_price|targetPrice|target,
     *   saved_balance|savedBalance|saved|balance,
     *   target_date|targetDate, description,
     *   archived_at|archivedAt|archived,
     *   progress|progress_percent|progressPercent,
     *   remaining|remaining_amount|remainingAmount,
     *   excess, status.
     *
     * No value is fabricated: missing amounts become 0, missing dates become null.
     */
    public static function present(array|object $row, bool $archivedContext = false): array
    {
        $id = max(0, self::toInt(self::field($row, ['id', 'goal_id', 'goalId'], 0)));
        $name = trim((string) self::field($row, ['name', 'goal_name', 'goalName'], ''));
        $description = trim((string) self::field($row, ['description', 'note'], ''));

        $target = max(0, self::toInt(self::field($row, ['target_price', 'targetPrice', 'target'], 0)));
        $saved = self::toInt(self::field(
            $row,
            ['saved_balance', 'savedBalance', 'saved', 'balance', 'current_balance', 'currentBalance'],
            0
        ));

        $archivedRaw = self::field(
            $row,
            ['archived_at', 'archivedAt', 'archived_on', 'archivedOn', 'archivedDateRaw', 'archived_date_raw'],
            null
        );
        $archived = self::isFilled($archivedRaw)
            || self::toBool(self::field($row, ['archived', 'is_archived', 'isArchived'], false));

        $targetDateRaw = self::dateValue(self::field(
            $row,
            ['target_date', 'targetDate', 'targetDateRaw', 'target_date_raw'],
            null
        ));
        $archivedDateRaw = self::dateValue($archivedRaw);

        $rawProgress = $target > 0 ? (($saved / $target) * 100) : 0.0;
        $progress = self::clamp(
            self::toFloat(self::field($row, ['progress', 'progress_percent', 'progressPercent'], $rawProgress)),
            0.0,
            100.0
        );
        $progressRaw = self::toFloat(self::field($row, ['progress_raw', 'progressRaw'], $rawProgress));

        $remaining = max(0, self::toInt(self::field(
            $row,
            ['remaining', 'remaining_amount', 'remainingAmount', 'sisa'],
            max(0, $target - $saved)
        )));
        $excess = max(0, self::toInt(self::field(
            $row,
            ['excess', 'excess_amount', 'excessAmount', 'kelebihan'],
            max(0, $saved - $target)
        )));

        $status = (string) self::field($row, ['status', 'status_label', 'statusLabel'], '');
        if ($status !== self::STATUS_ACTIVE && $status !== self::STATUS_COMPLETE) {
            $status = ($target > 0 && $saved >= $target) ? self::STATUS_COMPLETE : self::STATUS_ACTIVE;
        }

        $nearly = self::toBool(self::field(
            $row,
            ['nearly_reached', 'nearlyReached', 'hampir'],
            self::isNearlyReached($progressRaw)
        ));

        $updatedRaw = self::field($row, ['updated_at', 'updatedAt'], null);

        return [
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'target' => $target,
            'saved' => $saved,
            'balance' => $saved,
            'targetDateRaw' => $targetDateRaw,
            'targetDateLabel' => self::formatDate($targetDateRaw),
            'targetDateLong' => self::formatDateLong($targetDateRaw),
            'hasTargetDate' => $targetDateRaw !== null,
            'archived' => $archived,
            'archivedDateRaw' => $archivedDateRaw,
            'archivedDateLabel' => self::formatDate($archivedDateRaw),
            'progress' => $progress,
            'progressRaw' => $progressRaw,
            'progressLabel' => self::formatPercent($progress),
            'remaining' => $remaining,
            'remainingLabel' => self::formatRupiah($remaining),
            'excess' => $excess,
            'excessLabel' => self::formatRupiah($excess),
            'status' => $status,
            'nearlyReached' => $nearly && !$archived,
            'isNegative' => $saved < 0,
            'savedLabel' => self::formatRupiah($saved),
            'targetLabel' => self::formatRupiah($target),
            'updatedAtRaw' => $updatedRaw,
            'updatedAtLabel' => self::formatDate($updatedRaw),
            'archivedContext' => $archivedContext,
            'url' => $id > 0 ? '/goals/' . $id : '',
            'editUrl' => $id > 0 ? '/goals/' . $id . '/edit' : '',
            'savingsUrl' => $id > 0 ? '/goals/' . $id . '/savings' : '',
            'archiveUrl' => $id > 0 ? '/goals/' . $id . '/arsipkan' : '',
            'restoreUrl' => $id > 0 ? '/goals/' . $id . '/pulihkan' : '',
        ];
    }

    public static function isNearlyReached(float $progress): bool
    {
        return $progress >= 90.0 && $progress < 100.0;
    }

    /**
     * Build the three dashboard figures from an optional summary payload.
     * Missing figures fall back to a calculation over $goals (archived excluded).
     */
    public static function summarize(array|object|null $summary, array $goals = []): array
    {
        $computed = self::summarizeGoals($goals);

        $active = self::nullableInt(self::field($summary ?? [], [
            'activeGoals', 'active_goals', 'active', 'goalAktif', 'goal_aktif',
        ], null));
        $saved = self::nullableInt(self::field($summary ?? [], [
            'totalSaved', 'total_saved', 'saved', 'totalTabungan', 'total_tabungan',
        ], null));
        $remaining = self::nullableInt(self::field($summary ?? [], [
            'totalRemaining', 'total_remaining', 'remaining', 'totalSisa', 'total_sisa',
        ], null));

        return [
            'activeGoals' => $active ?? $computed['activeGoals'],
            'totalSaved' => $saved ?? $computed['totalSaved'],
            'totalRemaining' => $remaining ?? $computed['totalRemaining'],
        ];
    }

    /**
     * @param array<int, array|object> $goals
     */
    public static function summarizeGoals(array $goals): array
    {
        $active = 0;
        $saved = 0;
        $remaining = 0;

        foreach ($goals as $raw) {
            if (!is_array($raw) && !is_object($raw)) {
                continue;
            }
            $goal = self::present($raw);
            if ($goal['archived']) {
                continue;
            }
            $active++;
            $saved += $goal['saved'];
            $remaining += $goal['remaining'];
        }

        return [
            'activeGoals' => $active,
            'totalSaved' => $saved,
            'totalRemaining' => $remaining,
        ];
    }

    public static function formatRupiah(int $amount): string
    {
        $sign = $amount < 0 ? '-' : '';
        return $sign . 'Rp' . number_format(abs($amount), 0, ',', '.');
    }

    public static function formatPercent(float $value): string
    {
        return number_format($value, 1, ',', '.') . '%';
    }

    public static function formatDate(mixed $value): string
    {
        $date = self::toDate($value);
        if ($date === null) {
            return '—';
        }

        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        return $date->format('d') . ' ' . $months[(int) $date->format('n')] . ' ' . $date->format('Y');
    }

    public static function formatDateLong(mixed $value): string
    {
        $date = self::toDate($value);
        if ($date === null) {
            return '—';
        }

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $date->format('d') . ' ' . $months[(int) $date->format('n')] . ' ' . $date->format('Y');
    }

    public static function toDate(mixed $value): ?\DateTimeImmutable
    {
        if ($value instanceof \DateTimeInterface) {
            return \DateTimeImmutable::createFromInterface($value);
        }

        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);
        if ($value === '') {
            return null;
        }

        // Storage format is ISO date. Datetimes are accepted too.
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $value, $matches) !== 1) {
            return null;
        }

        $date = \DateTimeImmutable::createFromFormat(
            '!Y-m-d',
            $matches[1] . '-' . $matches[2] . '-' . $matches[3],
            new \DateTimeZone('Asia/Jakarta')
        );

        if (!$date instanceof \DateTimeImmutable) {
            return null;
        }

        // Reject impossible dates such as 2026-02-31.
        if ($date->format('Y-m-d') !== $matches[1] . '-' . $matches[2] . '-' . $matches[3]) {
            return null;
        }

        return $date;
    }

    private static function dateValue(mixed $value): ?string
    {
        $date = self::toDate($value);
        return $date?->format('Y-m-d');
    }

    private static function field(array|object $row, array $keys, mixed $default = null): mixed
    {
        foreach ($keys as $key) {
            if (is_array($row) && array_key_exists($key, $row)) {
                return $row[$key];
            }
            if (is_object($row) && isset($row->{$key})) {
                return $row->{$key};
            }
            if (is_object($row) && property_exists($row, $key)) {
                return $row->{$key};
            }
        }

        return $default;
    }

    private static function isFilled(mixed $value): bool
    {
        if ($value === null || $value === false) {
            return false;
        }
        if (is_string($value)) {
            return trim($value) !== '' && trim($value) !== '0000-00-00 00:00:00';
        }
        return true;
    }

    private static function toInt(mixed $value): int
    {
        if (is_int($value)) {
            return $value;
        }
        if (is_float($value)) {
            return (int) round($value);
        }
        if (is_bool($value)) {
            return $value ? 1 : 0;
        }
        if (is_string($value)) {
            $clean = preg_replace('/[^0-9\-]/', '', $value);
            return $clean === '' || $clean === '-' ? 0 : (int) $clean;
        }
        return 0;
    }

    private static function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (!is_int($value) && !is_float($value) && !is_string($value)) {
            return null;
        }
        return self::toInt($value);
    }

    private static function toFloat(mixed $value): float
    {
        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }
        if (is_string($value)) {
            $clean = str_replace(',', '.', trim($value));
            $clean = preg_replace('/[^0-9\.\-]/', '', $clean);
            return $clean === '' ? 0.0 : (float) $clean;
        }
        return 0.0;
    }

    private static function toBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }
        if (is_int($value) || is_float($value)) {
            return $value != 0;
        }
        if (is_string($value)) {
            return in_array(strtolower(trim($value)), ['1', 'true', 'yes', 'ya'], true);
        }
        return false;
    }

    private static function clamp(float $value, float $min, float $max): float
    {
        return max($min, min($max, $value));
    }
}