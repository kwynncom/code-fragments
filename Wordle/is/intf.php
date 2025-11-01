<?php

interface WordleImageIntf {

    const MARGIN_PERCENT     = 10;
    const MAX_COLORS         = 1100;
    const MAX_OTHER_PERCENT  = 1.6;  // ≤ 1.6%

    const WHITE     = 0xFFFFFF;
    const GREEN     = 0x6AAA64;
    const YELLOW    = 0xC9B458;
    const ALL_WRONG = 0x787C7E;
    const UNUSED    = 0xD3D6DA;
    const BLACK     = 0x000000;

    const COLOR_LIMITS = [
        self::WHITE     => [219038, 355333],
        self::UNUSED    => [48489, 125903],
        self::ALL_WRONG => [43893, 178072],
        self::GREEN     => [49592, 113885],
        self::YELLOW    => [0,     33285],
        self::BLACK     => [1298,   5680],
    ];

    const METRICS = [
        'total_pixels' => [520000, 650000],
        'filesize'     => [ 20000,  50000],
    ];
}

