<?php
namespace App\Enum;

abstract class BaseEnum{
    /**
     * items
     * Get class constants as a cases
     * @return array
     */
    public static function items() : array
    {
        $ref = new \ReflectionClass(static::class);
        return $ref->getConstants();
    }

    /**
     * values
     * @return array
     */
    public static function values(): array
    {
        return array_values(static::items());
    }
}
