<?php

namespace App\Enums;

use ReflectionClass;
use ReflectionException;

enum AttributesTypes : string
{
    case CHECKBOX = 'checkbox';

    case COUNT = 'count';
    case ONE_CHOICE = 'one_choice';
    case MULTI_CHOICE = 'multi_choice';
    case COLOR = 'color';

    case INPUT = 'input';

    public static function getRandomEnumValue(): string
    {
        $cases = self::cases();
        $randomCase = $cases[array_rand($cases)];
        return $randomCase->value;
    }
}
