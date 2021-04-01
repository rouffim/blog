<?php

namespace model\utils;

class NumberUtils {

    /**
     * @param $bool
     * @return int
     */
    static function booleanToInt($bool): int {
        return $bool ? 1 : 0;
    }

    /**
     * @param $int
     * @return bool
     */
    static function intToBool($int): bool {
        return $int == 1;
    }
}
