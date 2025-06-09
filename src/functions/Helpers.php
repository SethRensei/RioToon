<?php

use Riotoon\Service\BuildErrors;

/**
 * Cleans and sanitizes a string input.
 * @param mixed $word The string to be cleaned.
 * @return string|null The cleaned and sanitized string, or null if input is null.
 */
function clean($word): ?string
{
    // Trim leading and trailing whitespace, remove backslashes, and encode special characters
    return htmlentities(htmlspecialchars(stripslashes(trim($word))));
}

/**
 * Uncleans a string input
 * @param mixed $word The string to be uncleaned
 * @return string|null The uncleaned string, or null if input is null.
 */
function unClean($word): ?string
{
    return html_entity_decode($word);
}

/**
 * Creates and generates an HTML message for displaying flash alerts.
 * @param string $type_alert The type of alert (e.g. "success", "primary", "warning", "danger").
 * @param string $message The message to be displayed in the alert.
 * @return string The HTML code for the flash alert message.
 */
function messageFlash(string $type_alert, string $message): string
{
    return <<<HTML
    <div class="alert alert-{$type_alert} alert-dismissible" role="alert">
        <strong class="ms-3">{$message}</strong>
        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
    </div>
HTML;
}

/**
 * Replace specific characters in a string with a given replacement.
 * @param string $subject The string in which characters will be replaced.
 * @param string $replace (Optional) The replacement character (default is '_').
 * @return string|null The modified string with characters replaced, or null if input is invalid.
 */
function replace(string $subject, string $replace = '_'): ?string
{
    return str_replace(chars(), $replace, $subject);
}

/**
 * Get an array of characters that are to be replaced in a string.
 * @return array|null An array of characters to be replaced, or null if no characters defined.
 */
function chars(): ?array
{
    return ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+',
                    '=', '[', ']', '{', '|', '}', '\\', ';', ':', '\'', '"', ',', 
                    '.', '<', '>', '?', '/', ' '
                ];
}

/**
 * Upload and validate a file.
 * @param mixed $file The file data (ex : $_FILES['image']).
 * @param mixed $name The desired name for the uploaded image.
 * @param string $size (Optional) The maximum size of the image file in bytes (default: '5242880').
 * @param string $directory (Optional) The directory path where the image will be stored (default: '../public/images/cover/').
 * @param array $extension (Optional) Allowed file extensions (default: ['jpeg', 'png', 'jpg', 'gif', 'jfif']).
 * @return string|null The path to the uploaded image if successful, or null if there are errors.
 */
function uploadFile(
    $file,
    $name,
    string $size = '5242880',
    string $directory = '../public/images/cover/',
    array $extension = ['jpeg', 'png', 'jpg', 'gif', 'jfif']
): ?string {
    $image_name = clean($file['name']);
    if ($file['size'] <= $size) {
        $ext_img = strtolower(substr(strchr($image_name, '.'), 1));
        if (in_array($ext_img, $extension)) {
            if (!is_dir($directory))
                mkdir($directory, 0777, true);

            return $directory . strtolower($name) . '.' . $ext_img;
        } else
            BuildErrors::setErrors('file', 'L\'extension doit être ' . implode(', ', $extension));
    } else
        BuildErrors::setErrors('file', 'Le fichier fait plus de ' . ceil((int) $size / 1048576) . 'Mo');

    return '';
}