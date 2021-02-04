<?php


namespace App\Helpers;


class TimeHelper
{
    protected const TIME_REGEX = "/^\d{2}:\d{2}$/";

    /**
     * Check if time is in right format
     *
     * @param string $time
     *
     * @return bool
     */
    public static function isTimeValid(string $time): bool {
        return preg_match(self::TIME_REGEX, $time);
    }

    /**
     * Finds difference in minutes between times
     *
     * @param string $t1
     * @param string $t2
     *
     * @return integer
    */
    public static function timeDiff(string $t1, string $t2): int {
        return self::timeInMinutes($t1) - self::timeInMinutes($t2);
    }

    /**
     * Extracts minutes from time
     *
     * @param string $time
     *
     * @return integer
    */
    public static function timeInMinutes(string $time): int {
        if (!self::isTimeValid($time)) {
            return 0;
        }
        $parts = explode(":", $time);
        $hour = $parts[0];
        $minutes = $parts[1];
        return $hour * 60 + $minutes;
    }
}
