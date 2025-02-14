<?php

namespace App\Enums;

use ReflectionClass;

enum AttributesFilterTypes : string
{
    case CHECKBOX = 'checkbox';
    case RANGE = 'range';
    case MULTI_CHOICE = 'multi_choice';
    case ONE_CHOICE = 'one_choice';
    case COLOR = 'color';
    case INPUT = 'input';

    public static function getRandomEnumValue(): string
    {
        $cases = self::cases();
        $randomCase = $cases[array_rand($cases)];
        return $randomCase->value;
    }
}