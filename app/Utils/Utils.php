<?php
namespace App\Utils;

class Utils
{
    /**
     * @param $email
     * @return boolean
     */
    public function validateEmail(string $email): bool {
        preg_match_all(
            '/^([a-zA-Z0-9_\-.]+)@([a-zA-Z0-9_\-.]+)\.([a-zA-Z]{2,5})$/',
            $email,
            $matches,
            PREG_OFFSET_CAPTURE
        );
        return count($matches) > 0;
    }
}
