<?php

namespace App\Enum;

use App\Enum\BaseEnum;

class OrderStatusNumEnum extends BaseEnum{
    public const COMPLETE         = 4;
    public const PENDING          = 2;
    public const NOT_COMPLETE     = 1;
    public const CONFIRMED        = 3;

}
