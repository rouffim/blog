<?php
namespace model\utils;

use DateTime;
use DateTimeZone;

class DateUtils {

    /**
     * @return DateTime
     */
    static function now(): DateTime {
        // If now if uncorrect, you have to change php.ini -> date.timezone = Europe/Brussels
        return new DateTime();
    }

    /**
     * @param $date
     * @return string
     */
    static function stringDiff($date): string {
        $time = '';
        $unit = '';
        $diff = DateUtils::now()->diff($date);

        if($diff->y > 0) {
            $time = $diff->y;
            $unit = 'an';
        } else if($diff->m > 0) {
            $time = $diff->m;
            $unit = 'moi' . ($time == 1 ? 's' : '');
        } else if($diff->d > 0) {
            $time = $diff->d;
            $unit = 'jour';
        } else if($diff->h > 0) {
            $time = $diff->h;
            $unit = 'heure';
        } else if($diff->i > 0) {
            $time = $diff->i;
            $unit = 'minute';
        } else if($diff->s > 0) {
            $time = $diff->s;
            $unit = 'seconde';
        }

        return $time . ' ' . $unit . ($time == 1 ? '' : 's');
    }
}
