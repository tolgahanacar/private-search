<?php
declare(strict_types=1);

setlocale(LC_TIME, 'tr_TR.UTF-8');
date_default_timezone_set('Europe/Istanbul');

/**
 * Highlights search terms in the given text safely, preserving case and avoiding HTML injection.
 *
 * @param string $text The text in which to highlight the search terms.
 * @param string $searchTerms The search terms to highlight.
 * @return string The text with highlighted search terms.
 */
function highlightSearchTerms(string $text, string $searchTerms): string {
    if (empty(trim($searchTerms))) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
    
    // Escape HTML of the original text to prevent XSS
    $safeText = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    
    // Split search terms by spaces
    $termsArray = explode(" ", $searchTerms);
    
    // Clean and quote terms for regular expression usage
    $regexParts = [];
    foreach ($termsArray as $term) {
        $trimmed = trim($term);
        if ($trimmed !== '') {
            // Escape special regex chars and HTML entities since $safeText is html-escaped
            $escapedTerm = htmlspecialchars($trimmed, ENT_QUOTES, 'UTF-8');
            $regexParts[] = preg_quote($escapedTerm, '/');
        }
    }
    
    if (empty($regexParts)) {
        return $safeText;
    }
    
    // Combine terms with | operator and match case-insensitively
    $pattern = '/(' . implode('|', $regexParts) . ')/iu';
    
    // Replace with highlighted style, using backreference $1 to preserve original casing
    return preg_replace($pattern, "<b style='background-color:#2c3e50; color:white;'>$1</b>", $safeText);
}

/**
 * Sanitizes input values to prevent XSS attacks.
 *
 * @param string $value The input value to sanitize.
 * @return string The sanitized value.
 */
function sanitizeInput(string $value): string {
    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
}

/**
 * Removes all non-numeric characters from the given string.
 *
 * @param string $value The input string.
 * @return string The string with non-numeric characters removed.
 */
function removeNonNumeric(string $value): string {
    return preg_replace("/[^0-9]/", "", $value);
}
