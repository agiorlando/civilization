<?php
declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Str;

class LifespanParser
{
    /**
     * Parses a 'lived' string into life_start and life_end.
     *
     * Examples:
     * - "356 - 323 BC" will yield ['life_start' => "356", 'life_end' => "-323"]
     * - "63 BC - 14 AD" will yield ['life_start' => "-63", 'life_end' => "14"]
     *
     * @param string $lived
     * @return array{life_start: ?string, life_end: ?string}
     */
    public static function parseLivedField(string $lived): array
    {
        $parts = explode('-', $lived);
        $leftRaw = trim($parts[0] ?? '');
        $rightRaw = trim($parts[1] ?? '');

        $left = self::parseSingleDate($leftRaw);
        $right = self::parseSingleDate($rightRaw);

        // If one side is missing a marker, inherit it from the other side.
        if ($left['marker'] === null && $right['marker'] !== null) {
            $left['marker'] = $right['marker'];
        }
        if ($right['marker'] === null && $left['marker'] !== null) {
            $right['marker'] = $left['marker'];
        }

        // Default to AD if still not set.
        if ($left['marker'] === null) {
            $left['marker'] = 'AD';
        }
        if ($right['marker'] === null) {
            $right['marker'] = 'AD';
        }

        return [
            'life_start' => self::formatYear($left['year'], $left['marker']),
            'life_end'   => self::formatYear($right['year'], $right['marker']),
        ];
    }

    /**
     * Parses an individual date string.
     *
     * @param string $datePart
     * @return array{year: ?string, marker: ?string}
     */
    protected static function parseSingleDate(string $datePart): array
    {
        // Remove common prefixes and extra characters.
        $clean = trim(str_ireplace(['c.', 'c', '~', '?'], '', $datePart));

        if (Str::lower($clean) === 'unknown' || $clean === '') {
            return ['year' => null, 'marker' => null];
        }

        // Updated regex to accept "BC", "B", "AD", or "A"
        if (preg_match('/^(\d+)\s*(BC|B|AD|A)?$/i', $clean, $matches)) {
            $year = ltrim($matches[1], '0') ?: '0';
            $marker = isset($matches[2]) ? strtoupper(trim($matches[2])) : null;
            // Normalize shorthand markers.
            if ($marker === 'A') {
                $marker = 'AD';
            } elseif ($marker === 'B') {
                $marker = 'BC';
            }
            return ['year' => $year, 'marker' => $marker];
        }

        // Fallback: return the cleaned value with no marker.
        return ['year' => $clean, 'marker' => null];
    }

    /**
     * Formats a year and marker into a normalized string.
     *
     * For BC dates, returns the year prefixed with a minus sign.
     * For AD dates, returns the year as is.
     *
     * @param ?string $year
     * @param ?string $marker
     * @return ?string
     */
    protected static function formatYear(?string $year, ?string $marker): ?string
    {
        if ($year === null) {
            return null;
        }
        if ($marker === 'BC') {
            return '-' . $year;
        }
        return $year;
    }
}
