<?php
/*
    Plugin Name: Pretvori u latinicu
    Plugin URI: https://sajbersove.rs/
    Description: Pretvaramo ćirilicu iz WordPress-a u latinicu.
    Tags: ćirilica, latinica, sajbersove, sajber sove
    Author: Sajber Sove
    Author URI: https://sajbersove.rs/
    Version: 2.0
    License: GPLv2
    Text Domain: pretvori-u-latinicu
*/

if (!defined('ABSPATH')) {
    die('Forbidden');
}

final class Pretvori_U_Latinicu {

    private const CIRILICA = [
        'А', 'Б', 'В', 'Г', 'Д', 'Ђ', 'Е', 'Ж', 'З', 'И', 'Ј', 'К', 'Л', 'Љ', 'М',
        'Н', 'Њ', 'О', 'П', 'Р', 'С', 'Т', 'Ћ', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Џ', 'Ш',
        'а', 'б', 'в', 'г', 'д', 'ђ', 'е', 'ж', 'з', 'и', 'ј', 'к', 'л', 'љ', 'м',
        'н', 'њ', 'о', 'п', 'р', 'с', 'т', 'ћ', 'у', 'ф', 'х', 'ц', 'ч', 'џ', 'ш'
    ];

    private const LATINICA = [
        'A', 'B', 'V', 'G', 'D', 'Đ', 'E', 'Ž', 'Z', 'I', 'J', 'K', 'L', 'Lj', 'M',
        'N', 'Nj', 'O', 'P', 'R', 'S', 'T', 'Ć', 'U', 'F', 'H', 'C', 'Č', 'Dž', 'Š',
        'a', 'b', 'v', 'g', 'd', 'đ', 'e', 'ž', 'z', 'i', 'j', 'k', 'l', 'lj', 'm',
        'n', 'nj', 'o', 'p', 'r', 's', 't', 'ć', 'u', 'f', 'h', 'c', 'č', 'dž', 'š'
    ];

    private const UNI_CIRILICA = [
        'u0410', 'u0411', 'u0412', 'u0413', 'u0414', 'u0402', 'u0415', 'u0416', 'u0417',
        'u0418', 'u0408', 'u041a', 'u041b', 'u0409', 'u041c', 'u041d', 'u040a', 'u041e',
        'u041f', 'u0420', 'u0421', 'u0422', 'u040b', 'u0423', 'u0424', 'u0425', 'u0426',
        'u0427', 'u040f', 'u0428',
        'u0430', 'u0431', 'u0432', 'u0433', 'u0434', 'u0452', 'u0435', 'u0436', 'u0437',
        'u0438', 'u0458', 'u043a', 'u043b', 'u0459', 'u043c', 'u043d', 'u045a', 'u043e',
        'u043f', 'u0440', 'u0441', 'u0442', 'u045b', 'u0443', 'u0444', 'u0445', 'u0446',
        'u0447', 'u045f', 'u0448'
    ];

    private const UNI_LATINICA = [
        'u0041', 'u0042', 'u0056', 'u0047', 'u0044', 'u0110', 'u0045', 'u017d', 'u005a',
        'u0049', 'u004a', 'u004b', 'u004c', 'u004c\u006a', 'u004d', 'u004e', 'u004e\u006a',
        'u004f', 'u0050', 'u0052', 'u0053', 'u0054', 'u0106', 'u0055', 'u0046', 'u0048',
        'u0043', 'u010c', 'u0044\u017e', 'u0160',
        'u0061', 'u0062', 'u0076', 'u0067', 'u0064', 'u0111', 'u0065', 'u017e', 'u007a',
        'u0069', 'u006a', 'u006b', 'u006c', 'u006c\u006a', 'u006d', 'u006e', 'u006e\u006a',
        'u006f', 'u0070', 'u0072', 'u0073', 'u0074', 'u0107', 'u0075', 'u0066', 'u0068',
        'u0063', 'u010d', 'u0064\u017e', 'u0161'
    ];

    private static ?self $instance = null;

    public static function instance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        if (get_locale() !== 'sr_RS') {
            return;
        }

        add_filter('gettext',              [$this, 'prevedi'], 10, 1);
        add_filter('gettext_with_context', [$this, 'prevedi'], 10, 1);
        add_filter('ngettext',             [$this, 'prevedi'], 10, 1);
        add_filter('ngettext_with_context', [$this, 'prevedi'], 10, 1);

        add_filter('load_script_translations', [$this, 'prevedi_skripte'], 10, 1);
    }

    private function __clone() {}

    public function prevedi(string $prevod): string {
        if (!preg_match('/[\x{0400}-\x{04FF}]/u', $prevod)) {
            return $prevod;
        }
        return str_replace(self::CIRILICA, self::LATINICA, $prevod);
    }

    public function prevedi_skripte(?string $prevod): ?string {
        if ($prevod === null) {
            return null;
        }
        return str_replace(self::UNI_CIRILICA, self::UNI_LATINICA, $prevod);
    }
}

Pretvori_U_Latinicu::instance();
