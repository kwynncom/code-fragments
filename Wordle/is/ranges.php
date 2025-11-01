<?php

    require_once('intf.php');

    function WordleImageRangesF(): array
    {
        static $cache = null;
        if ($cache !== null) return $cache;

        $m = WordleImageIntf::MARGIN_PERCENT / 100;
        $cache = ['colors' => [], 'metrics' => []];

        foreach (WordleImageIntf::COLOR_LIMITS as $rgb => [$min, $max]) {
            $cache['colors'][$rgb] = [(int)($min * (1 - $m)), (int)($max * (1 + $m))];
        }

        foreach (WordleImageIntf::METRICS as $k => [$min, $max]) {
            $cache['metrics'][$k] = [(int)($min * (1 - $m)), (int)($max * (1 + $m))];
        }

        return $cache;
    }

