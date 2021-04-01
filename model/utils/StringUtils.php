<?php

namespace model\utils;

class StringUtils {

    /**
     * @param $haystack
     * @param $needle
     * @param bool $case
     * @return bool
     */
    static function startsWith($haystack, $needle, $case = true): bool {
        if ($case)
            return strpos($haystack, $needle, 0) === 0;

        return stripos($haystack, $needle, 0) === 0;
    }

    /**
     * @param $haystack
     * @param $needle
     * @param bool $case
     * @return bool
     */
    static function endsWith($haystack, $needle, $case = true): bool {
        $expectedPosition = strlen($haystack) - strlen($needle);

        if ($case)
            return strrpos($haystack, $needle, 0) === $expectedPosition;

        return strripos($haystack, $needle, 0) === $expectedPosition;
    }

    /**
     * @param string $email
     * @return bool
     */
    static function isEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param string $password
     * @return string
     */
    static function encryptPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @param ?string $ch
     * @return bool
     */
    static function isEmpty(?string $ch): bool {
        return is_null($ch) || $ch == '';
    }

}
