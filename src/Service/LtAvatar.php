<?php

namespace Riotoon\Service;

use YoHang88\LetterAvatar\LetterAvatar;

class LtAvatar
{
    /**
     * Generates an avatar image based on the given name using LetterAvatar library.
     * @param string $name The name used to generate the avatar initials.
     * @param int $size The size of the avatar in pixels. Default is 96 pixels.
     * @return LetterAvatar An instance of LetterAvatar configured with the specified name and size.
     */
    public static function initialAvatar(string $name, int $size = 96) {
        $avatar = new LetterAvatar($name, 'square', $size);
        // Define array of colors for avatar background
        $colors = ['#A7001E', '#7AA95C', '#955149', '#4AA3A2', '#1E0F1C', '#C18845', '#5D7052',
            '#A4BD01', '#CF5C78', '#C49D83', '#FF6B1A', '#B33F00', '#253659', '#BF665E', '#F27457', '#226D68', '#E1A624'];
        // Set a random color from $colors array as the avatar background
        $avatar->setColor($colors[array_rand($colors)], '#45F3FF');
        return $avatar;
    }
}
