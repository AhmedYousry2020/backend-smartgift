<?php

namespace App\Enum;

use App\Enum\BaseEnum;

class OrderStatusEnum extends BaseEnum{
    public const COMPLETE         = 'completed';
    public const PENDING          = 'pending';
    public const NOT_COMPLETE     = 'notcomplete';
    public const CONFIRMED             = 'confirmed';

}
