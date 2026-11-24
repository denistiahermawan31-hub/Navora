<?php
// NAVORA — app/models/SavingsTransaction.php
// Presentation helpers for a savings transaction (Setoran / Penarikan).
//
// No SQL lives here. Persistence depends on the Owner's database contract.
// Direction rules follow DEC-004 / DEC-025: the stored amount is always
// positive and the direction determines the sign.

namespace NAVORA\Models;

final class SavingsTransaction
{
    public const DIRECTION_DEPOSIT = 'deposit';
    public const DIRECTION_WITHDRAWAL = 'withdrawal';

    /**
     * Expected input keys (aliases accepted):
     *   id, goal_id|goalId, goal_name|goalName,
     *   direction, amount, occurred_on|occurredOn|date,
     *   note, created_at|createdAt.
     */
    public static function present(array|object $row): array
    {
        $direction = self::normalizeDirection(self::field($row, ['direction', 'type', 'jenis'], ''));
        $amount = abs(self::toInt(self::field($row, ['amount', 'nominal', 'value'], 0)));
        $dateRaw = self::dateValue(self::field(
            $row,
            ['occurred_on', 'occurredOn', 'date', 'tanggal', 'dateRaw', 'occurred_on_raw', 'occurredOnRaw'],
            null
        ));

        $signed = 0;
        if ($direction === self::DIRECTION_DEPOSIT) {
            $signed = $amount;
        } elseif ($direction === self::DIRECTION_WITHDRAWAL) {
            $signed = -$amount;
        }

        return [
            'id' => max(0, self::toInt(self::field($row, ['id'], 0))),
            'goalId' => max(0, self::toInt(self::field($row, ['goal_id', 'goalId'], 0))),
            'goalName' => trim((string) self::field($row, ['goal_name', 'goalName'], '')),
            'direction' => $direction,
            'directionLabel' => self::directionLabel($direction),
            'isDeposit' => $direction === self::DIRECTION_DEPOSIT,
            'isWithdrawal' => $direction === self::DIRECTION_WITHDRAWAL,
            'amount' => $amount,
            'signedAmount' => $signed,
            'amountLabel' => Goal::formatRupiah($amount),
            'signedLabel' => self::signedLabel($direction, $amount),
            'dateRaw' => $dateRaw,
            'dateLabel' => Goal::formatDate($dateRaw),
            'dateLong' => Goal::formatDateLong($dateRaw),
            'note' => trim((string) self::field($row, ['note', 'catatan', 'description'], '')),
            'createdAtRaw' => self::field($row, ['created_at', 'createdAt'], null),
        ];
    }

    public static function normalizeDirection(mixed $direction): string
    {
        $value = strtolower(trim((string) $direction));

        if (in_array($value, ['deposit', 'setoran', 'masuk', 'in', '+'], true)) {
            return self::DIRECTION_DEPOSIT;
        }
        if (in_array($value, ['withdrawal', 'penarikan', 'keluar', 'out', '-'], true)) {
            return self::DIRECTION_WITHDRAWAL;
        }

        return 'unknown';
    }

    public static function directionLabel(string $direction): string
    {
        return match ($direction) {
            self::DIRECTION_DEPOSIT => 'Setoran',
            self::DIRECTION_WITHDRAWAL => 'Penarikan',
            default => 'Tidak diketahui',
        };
    }

    public static function signedLabel(string $direction, int $amount): string
    {
        $amount = abs($amount);

        return match ($direction) {
            self::DIRECTION_DEPOSIT => '+Rp' . number_format($amount, 0, ',', '.'),
            self::DIRECTION_WITHDRAWAL => '-Rp' . number_format($amount, 0, ',', '.'),
            default => Goal::formatRupiah($amount),
        };
    }

    public static function signedAmount(mixed $direction, mixed $amount): int
    {
        $normalized = self::normalizeDirection($direction);
        $value = abs(self::toInt($amount));

        return match ($normalized) {
            self::DIRECTION_DEPOSIT => $value,
            self::DIRECTION_WITHDRAWAL => -$value,
            default => 0,
        };
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

    private static function dateValue(mixed $value): ?string
    {
        $date = Goal::toDate($value);
        return $date?->format('Y-m-d');
    }

    private static function toInt(mixed $value): int
    {
        if (is_int($value)) {
            return $value;
        }
        if (is_float($value)) {
            return (int) round($value);
        }
        if (is_string($value)) {
            $clean = preg_replace('/[^0-9\-]/', '', $value);
            return $clean === '' || $clean === '-' ? 0 : (int) $clean;
        }
        return 0;
    }
}
