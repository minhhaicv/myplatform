<?php
/**
 * Washes strings from unwanted noise.
 *
 * Helpful methods to make unsafe strings usable.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Utility
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */


/**
 * Data Sanitization.
 *
 * Removal of alphanumeric characters, SQL-safe slash-added strings, HTML-friendly strings,
 * and all of the above on arrays.
 *
 * @package       Cake.Utility
 * @deprecated    3.0.0 Deprecated since version 2.4
 */
class sanitizeHelper {

/**
 * Removes any non-alphanumeric characters.
 *
 * @param string $string String to sanitize
 * @param array $allowed An array of additional characters that are not to be removed.
 * @return string Sanitized string
 */
    public static function paranoid($string, $allowed = array()) {
        $allow = null;
        if (!empty($allowed)) {
            foreach ($allowed as $value) {
                $allow .= "\\$value";
            }
        }

        if (!is_array($string)) {
            return preg_replace("/[^{$allow}a-zA-Z0-9]/", '', $string);
        }

        $cleaned = array();
        foreach ($string as $key => $clean) {
            $cleaned[$key] = preg_replace("/[^{$allow}a-zA-Z0-9]/", '', $clean);
        }

        return $cleaned;
    }

/**
 * Makes a string SQL-safe.
 *
 * @param string $string String to sanitize
 * @param string $connection Database connection being used
 * @return string SQL safe string
 */
    public static function escape($string) {
        if (is_numeric($string) || $string === null || is_bool($string)) {
            return $string;
        }

        return Helper::getDB()->value($string, 'string');
    }

/**
 * Returns given string safe for display as HTML. Renders entities.
 *
 * strip_tags() does not validating HTML syntax or structure, so it might strip whole passages
 * with broken HTML.
 *
 * ### Options:
 *
 * - remove (boolean) if true strips all HTML tags before encoding
 * - charset (string) the charset used to encode the string
 * - quotes (int) see http://php.net/manual/en/function.htmlentities.php
 * - double (boolean) double encode html entities
 *
 * @param string $string String from where to strip tags
 * @param array $options Array of options to use.
 * @return string Sanitized string
 */
    public static function html($string, $options = array()) {
        $defaults = array(
            'remove' => false,
            'charset' => 'UTF-8',
            'quotes' => ENT_QUOTES,
            'double' => true
        );

        $options += $defaults;

        if ($options['remove']) {
            $string = strip_tags($string);
        }

        return htmlentities($string, $options['quotes'], $options['charset'], $options['double']);
    }

/**
 * Strips extra whitespace from output
 *
 * @param string $str String to sanitize
 * @return string whitespace sanitized string
 */
    public static function stripWhitespace($str) {
        return preg_replace('/\s{2,}/u', ' ', preg_replace('/[\n\r\t]+/', '', $str));
    }

/**
 * Strips image tags from output
 *
 * @param string $str String to sanitize
 * @return string Sting with images stripped.
 */
    public static function stripImages($str) {
        $preg = array(
            '/(<a[^>]*>)(<img[^>]+alt=")([^"]*)("[^>]*>)(<\/a>)/i' => '$1$3$5<br />',
            '/(<img[^>]+alt=")([^"]*)("[^>]*>)/i' => '$2<br />',
            '/<img[^>]*>/i' => ''
        );

        return preg_replace(array_keys($preg), array_values($preg), $str);
    }

/**
 * Strips scripts and stylesheets from output
 *
 * @param string $str String to sanitize
 * @return string String with <link>, <img>, <script>, <style> elements and html comments removed.
 */
    public static function stripScripts($str) {
        $regex =
            '/(<link[^>]+rel="[^"]*stylesheet"[^>]*>|' .
            '<img[^>]*>|style="[^"]*")|' .
            '<script[^>]*>.*?<\/script>|' .
            '<style[^>]*>.*?<\/style>|' .
            '<!--.*?-->/is';
        return preg_replace($regex, '', $str);
    }

/**
 * Strips extra whitespace, images, scripts and stylesheets from output
 *
 * @param string $str String to sanitize
 * @return string sanitized string
 */
    public static function stripAll($str) {
        return Sanitize::stripScripts(
            Sanitize::stripImages(
                Sanitize::stripWhitespace($str)
            )
        );
    }

/**
 * Strips the specified tags from output. First parameter is string from
 * where to remove tags. All subsequent parameters are tags.
 *
 * Ex.`$clean = Sanitize::stripTags($dirty, 'b', 'p', 'div');`
 *
 * Will remove all `<b>`, `<p>`, and `<div>` tags from the $dirty string.
 *
 * @param string $str String to sanitize.
 * @return string sanitized String
 */
    public static function stripTags($str) {
        $params = func_get_args();

        for ($i = 1, $count = count($params); $i < $count; $i++) {
            $str = preg_replace('/<' . $params[$i] . '\b[^>]*>/i', '', $str);
            $str = preg_replace('/<\/' . $params[$i] . '[^>]*>/i', '', $str);
        }
        return $str;
    }

/**
 * Sanitizes given array or value for safe input. Use the options to specify
 * the connection to use, and what filters should be applied (with a boolean
 * value). Valid filters:
 *
 * - odd_spaces - removes any non space whitespace characters
 * - encode - Encode any html entities. Encode must be true for the `remove_html` to work.
 * - dollar - Escape `$` with `\$`
 * - carriage - Remove `\r`
 * - unicode -
 * - escape - Should the string be SQL escaped.
 * - backslash -
 * - remove_html - Strip HTML with strip_tags. `encode` must be true for this option to work.
 *
 * @param string|array $data Data to sanitize
 * @param string|array $options If string, DB connection being used, otherwise set of options
 * @return mixed Sanitized data
 */
    public static function clean($data, $options = array()) {
        if (empty($data)) {
            return $data;
        }

        $default = array(
                        'odd_spaces' => true,
                        'remove_html' => false,
                        'encode' => true,
                        'dollar' => true,
                        'carriage' => true,
                        'unicode' => true,
                        'escape' => false,
                        'backslash' => true
        );

        if(!empty($options)) {
            $default = array_merge($default, $options);
        }


        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $data[$key] = self::clean($val, $default);
            }
            return $data;
        }

        if ($default['odd_spaces']) {
            $data = str_replace(chr(0xCA), '', $data);
        }
        if ($default['encode']) {
            $data = self::html($data, array('remove' => $default['remove_html']));
        }
        if ($default['dollar']) {
            $data = str_replace("\\\$", "$", $data);
        }
        if ($default['carriage']) {
            $data = str_replace("\r", "", $data);
        }
        if ($default['unicode']) {
            $data = preg_replace("/&amp;#([0-9]+);/s", "&#\\1;", $data);
        }
        if ($default['escape']) {
            $data = self::escape($data);
        }
        if ($default['backslash']) {
            $data = preg_replace("/\\\(?!&amp;#|\?#)/", "\\", $data);
        }

        return $data;
    }
}
