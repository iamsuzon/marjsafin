<?php

namespace App\Http\Enums;

enum LinkLimitEnum: int
{
    case NORMAL = 1;
    case NORMAL_PLUS = 3;
    case SPECIAL = 4;
    case SPECIAL_PLUS = 6;
};

