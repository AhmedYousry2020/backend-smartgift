<?php

namespace App\Enum;

use App\Enum\BaseEnum;

class UserStatusEnum extends BaseEnum{
    public const ACTIVE         = 'active';
    public const BLOCK          = 'block';
    public const FREEZE         = 'freeze';
}
