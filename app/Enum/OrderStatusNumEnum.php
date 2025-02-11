<?php

namespace App\Enum;

use App\Enum\BaseEnum;

class OrderStatusNumEnum extends BaseEnum{
    public const COMPLETE         = 1;
    public const PENDING          = 2;
    public const NOT_COMPLETE     = 3;
    public const CONFIRMED         = 4;

}
