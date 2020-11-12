<?php

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class MoonStateType extends AbstractEnumType
{
    public const NEW_MOON = 'NM';
    public const WAXING_CRESCENT = 'WAXC';
    public const FIRST_QUARTER = 'FQ';
    public const WAXING_GIBBOUS = 'WAXG';
    public const FULL_MOON = 'FM';
    public const WANING_GIBBOUS = 'WANG';
    public const LAST_QUARTER = 'LQ';
    public const WANING_CRESCENT = 'WANC';

    protected static $choices = [
        self::NEW_MOON => 'New moon',
        self::WAXING_CRESCENT => 'Waxing crescent',
        self::FIRST_QUARTER => 'First quarter',
        self::WAXING_GIBBOUS => 'Waxing gibbous',
        self::FULL_MOON => 'Full moon',
        self::WANING_GIBBOUS => 'Waning gibbous',
        self::LAST_QUARTER => 'Last quater',
        self::WANING_CRESCENT => 'Waning crescent'
    ];
}