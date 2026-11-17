<?php

namespace App\Support;

/** Pakistani mobile numbers: accept 0300-1234567 / +92 300 1234567 / 923001234567, store 03001234567. */
class PkPhone
{
    public const PATTERN = '/^03\d{9}$/';

    public static function normalize(?string $raw): string
    {
        $d = preg_replace('/\D/', '', (string) $raw);
        if (str_starts_with($d, '0092')) {
            $d = substr($d, 4);
        } elseif (str_starts_with($d, '92') && strlen($d) === 12) {
            $d = substr($d, 2);
        }
        if (strlen($d) === 10 && str_starts_with($d, '3')) {
            $d = '0' . $d;
        }

        return $d !== '' ? $d : (string) $raw;
    }

    public static function display(string $phone): string
    {
        return preg_match(self::PATTERN, $phone) ? substr($phone, 0, 4) . '-' . substr($phone, 4) : $phone;
    }

    /** wa.me wants the international form without "+". */
    public static function whatsapp(string $phone): string
    {
        return preg_match(self::PATTERN, $phone) ? '92' . substr($phone, 1) : preg_replace('/\D/', '', $phone);
    }
}
