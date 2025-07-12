<?php

namespace App\Helpers;

class ColorHelper
{
    /**
     * Menentukan apakah sebuah warna heksadesimal itu gelap atau terang.
     * Berguna untuk menentukan warna teks (putih atau hitam) yang kontras.
     *
     * @param string $hexColor Kode warna dalam format hex (misal: #RRGGBB atau #RGB)
     * @return bool True jika warna gelap, false jika warna terang.
     */
    public function isDark(string $hexColor): bool
    {
        // Hilangkan karakter '#' jika ada
        $hex = ltrim($hexColor, '#');

        // Jika formatnya singkat (#RGB), ubah menjadi format panjang (#RRGGBB)
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        // Konversi hex ke RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Hitung luminans (tingkat kecerahan)
        // Formula standar YIQ
        $luminance = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        // Warna dianggap gelap jika luminans di bawah 128
        return $luminance < 128;
    }
}