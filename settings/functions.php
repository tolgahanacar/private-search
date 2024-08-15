<?php

setlocale(LC_TIME, 'tr_TR.UTF-8');
date_default_timezone_set('Europe/Istanbul');

/**
 * Highlights search terms in the given text with a specific background color.
 *
 * @param string $text The text in which to highlight the search terms.
 * @param string $searchTerms The search terms to highlight.
 * @return string The text with highlighted search terms.
 */
function highlightSearchTerms($text, $searchTerms) {
    $termsArray = explode(" ", $searchTerms);
    foreach ($termsArray as $term) {
        $text = str_ireplace($term, "<b style='background-color:#2c3e50; color:white'>$term</b>", $text);
    }
    return $text;
}

/**
 * Sanitizes the input value to prevent XSS attacks.
 *
 * @param string $value The input value to sanitize.
 * @return string The sanitized value.
 */
function sanitizeInput($value) {
    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
}

/**
 * Removes all non-numeric characters from the given string.
 *
 * @param string $value The input string from which to remove non-numeric characters.
 * @return string The string with non-numeric characters removed.
 */
function removeNonNumeric($value) {
    return preg_replace("/[^0-9]/", "", $value);
}
