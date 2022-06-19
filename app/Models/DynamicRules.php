<?php

namespace App\Models;

abstract class DynamicRules
{
    static function validateRules(array $rules, array $params): array
    {
        $dynamicRules = [];

        foreach ($rules as $input => $rules) {
            if (array_key_exists($input, $params)) {
                $dynamicRules[$input] = $rules;
            }
        }

        return $dynamicRules;
    }
}
