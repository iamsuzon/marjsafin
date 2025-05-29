<?php

namespace App\Http\Enums;

enum LinkLimitEnum: int
{
    case NORMAL = 1;
    case SEMI_NORMAL = 2;
    case NIGHT_NORMAL = 3;
    case SPECIAL = 4;
};

