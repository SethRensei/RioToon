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
    return br2nl(html_entity_decode($word));
}

/**
 * Removes all <br> tags (case and variant sensitive) with a 
 * @param mixed $string
 * @return array|string|null
 */
function br2nl($string)
{
    // Remplace les balises <br> (peu importe la casse et la variante) par un saut de ligne \n
    return trim(preg_replace('/<br\s*\/?>/i', "", $string));
}

/**
 * Creates and generates an HTML message for displaying flash alerts.
 * @param string $type_alert The type of alert (e.g. "success", "primary", "warning", "danger").
 * @param string $message The message to be displayed in the alert.
 * @return string The HTML code for the flash alert message.
 */
function messageFlash(string $type_alert, string $message): string
{
    $message = nl2br(trim($message));
    return <<<HTML
    <div class="alert alert-{$type_alert} alert-dismissible" role="alert">
        <strong>{$message}</strong>
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
function chars(): ?array {
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
    array $extension = ['jpeg', 'png', 'jpg', 'gif', 'jfif', 'webp']
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

/**
 * Generate an excerpt of a given content string with a specified character limit.
 * @param mixed $content The content string to generate an excerpt from.
 * @param int $limit (Optional) The character limit for the excerpt (default: 15).
 * @return string The excerpted content with an ellipsis (...) if truncated.
 */
function excerpt($content, int $limit = 15)
{
    $content = unClean($content);
    if (mb_strlen($content) <= $limit)
        return $content;
    return mb_substr($content, 0, $limit) . '...';
}

/**
 * Generate a URL-friendly string from a given word by converting to lowercase, replacing special characters with dashes, and encoding.
 * @param string $word The input word or string to generate a URL-friendly version of.
 * @return string|null The URL-friendly string, or null if input is invalid.
 */
function goodURL(string $text): ?string
{
    // 0. Décodage initial des entités HTML (&amp;#039; → &#039; → ', etc.)
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // 1. Translitération des accents (É → E, ç → c…)
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    if ($text === false) {
        return null;
    }

    // 2. Re-décodage pour convertir les entités numériques restantes (&#039; → ')
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = mb_strtolower($text, 'UTF-8');

    // 4. On ne garde que lettres, chiffres et espaces
    $text = preg_replace('/[^a-z0-9 -]+/', '', $text);

    // 5. Espaces (séquences) → tiret unique
    $text = preg_replace('/\s+/', '-', $text);
    // 7. Enfin, encodage URL strict pour sécuriser les éventuels restes
    return rawurlencode($text);
}


/**
 * Read a comic archive file (ZIP or CBZ) and extract image files as base64 encoded data URIs.
 * @param string $target $target The path to the ZIP or CBZ archive containing comic images.
 * @return array An array of base64 encoded image data URIs extracted from the ZIP archive.
 */
function comicReader(string $target)
{

    $zip = new ZipArchive();

    // Vérification si l'ouverture à bien réussie
    if ($zip->open($target) === true) {
        $imgs = [];
        // On parcourt l'ensemble des fichiers
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $file_into = $zip->statIndex($i);
            $filename = $file_into['name']; // Récupération du nom
            $files[$i] = $filename;
        }
        sort($files); // On trie le tableau dans l'ordre croissant
        for ($i = 0; $i < count($files); $i++) {
            $file_extension = pathinfo($files[$i], PATHINFO_EXTENSION); // Récupération de l'extension
            // Vérifie si c'est une image (JPEG, JPG, PNG, GIF)
            if (in_array($file_extension, ['jpeg', 'jpg', 'png', 'gif'])) {
                $imgs[$i] = "data:image/jpeg;base64," . base64_encode($zip->getFromName($files[$i])); // On encode pour la visibilité des images
            }
        }
        $zip->close();
    } else
        echo "<script>console.log('Impossible d'ouvrir le fichier')</script>";

    return $imgs;
}

function tableStyle()
{
    return <<<HTML
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 2px solid black;
        }

        th {
            background-color: #342628;
            color: white;
        }

        tr {
            background-color: #c0c0c0;
        }

        tr td a {
            text-decoration: none;
        }

        tr form {
            display: inline;
        }

        tr:nth-child(odd) {
            background-color: #A3B4C8;
        }

        .table-responsive td #add {
            color: #61a476;
        }

        .table-responsive td #edit {
            color: #FFA101;
        }

        .table-responsive td #remove {
            color: #F21137;
        }

        @media only screen and (max-width: 878px) {

            .table-responsive table,
            .table-responsive thead,
            .table-responsive tbody,
            .table-responsive tr,
            .table-responsive th,
            .table-responsive td {
                display: block;
            }

            .table-responsive thead {
                display: none;
            }

            .table-responsive td {
                padding-left: 150px;
                position: relative;
                margin-top: -1;
                background-color: #fff;
                font-size: .8rem !important;
                font-weight: 800;
            }

            .table-responsive td:nth-child(odd) {
                background-color: #cdcAd6;
            }

            .table-responsive td::before {
                content: attr(data-label);
                position: absolute;
                top: 0;
                left: 0;
                width: 130px;
                bottom: 0;
                color: white;
                background-color: #342628;
                display: flex;
                justify-content: center;
                padding: 10px;
                font-weight: bold;
            }

            .table-responsive tr {
                margin-bottom: 1.2rem;
            }
        }
    </style>
HTML;
}