<?php

namespace Mackerel\Client;

class Helper {
    /**
     * @param string $str
     * @param string $replacement
     * @return mixed
     */
    public static function asSafeMetricName($str, $replacement = '-')
    {
        return preg_replace('/[^a-zA-Z0-9_\-]/', $replacement, $str);
    }
}