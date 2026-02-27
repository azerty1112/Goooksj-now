<?php

namespace App;

class Utils
{
    /**
     * Convert a string to a URL-friendly slug (lowercase, hyphens).
     *
     * @param string $text
     * @return string
     */
    public static function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }

    /**
     * Escape a string for safe HTML output.
     *
     * @param string $text
     * @return string
     */
    public static function e(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Return a normalized (filtered) integer value from input.
     */
    public static function intVal(mixed $value): int
    {
        return (int) trim((string) $value);
    }

    /**
     * Basic detect for arabic or english based on query string or headers.
     */
    public static function detectPreferredLanguage(): string
    {
        $queryLang = strtolower(trim((string)($_GET['lang'] ?? '')));
        if (in_array($queryLang, ['ar', 'en'], true)) {
            return $queryLang;
        }

        $countryCode = strtoupper(trim((string)($_SERVER['HTTP_CF_IPCOUNTRY'] ?? $_SERVER['GEOIP_COUNTRY_CODE'] ?? '')));
        $arabicCountries = ['SA', 'AE', 'EG', 'KW', 'QA', 'BH', 'OM', 'JO', 'LB', 'SY', 'IQ', 'YE', 'MA', 'DZ', 'TN', 'LY', 'SD', 'PS', 'MR', 'SO', 'DJ', 'KM'];
        if ($countryCode !== '') {
            return in_array($countryCode, $arabicCountries, true) ? 'ar' : 'en';
        }

        $acceptLanguage = strtolower((string)($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? ''));
        return str_starts_with($acceptLanguage, 'ar') ? 'ar' : 'en';
    }
}
