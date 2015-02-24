<?php

class stringHelper {

    static function removeAccent($string = "", $exeption = null) {
        $string = mb_strtolower($string);

        if (preg_match("/[\x80-\xFF]/", $string)) {
            $string = Normalizer::normalize($string, Normalizer::NFKC);

            $glibc = 'glibc' === ICONV_IMPL;

            preg_match_all('/./u', $string, $string);

            foreach ($string[0] as &$c)
            {
                if (! isset($c[1])) continue;

                if ($glibc) {
                    $t = iconv('UTF-8', 'ASCII//TRANSLIT', $c);
                } else {
                    $t = iconv('UTF-8', 'ASCII//IGNORE//TRANSLIT', $c);
                }

                if (!isset($t[0])) {
                    $t = '?';
                } elseif (isset($t[1])) {
                    $t = ltrim($t, '\'`"^~');
                }

                if ('?' === $t) {
                    $t = Normalizer::normalize($c, Normalizer::NFD);

                    if ($t[0] < "\x80") {
                        $t = $t[0];
                    } else {
                        $t = is_null($exeption) ? $c : $exeption;
                    }
                }

                $c = $t;
            }

            $string = implode('', $string[0]);
        }

        return $string;
    }

    static function block($string = '', $exception = '', $conjunct = array()) {
        $string = self::removeAccent($string, $exception);

        $default = array(' ' => '-');
        $conjunct = array_merge($default, $conjunct);

        return strtr($string, $conjunct);
    }
}