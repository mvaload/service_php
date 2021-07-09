<?php

namespace App;

class Validator implements ValidatorInterface
{
    public function validate(array $items)
    {
        $errors = [];
        foreach ($items as $key => $value) {
            if (empty($items[$key])) {
                $errors[$key] = "Can't be blank";
            } elseif (
                $key == 'dateTime' &&
                !filter_var($value,
                FILTER_VALIDATE_REGEXP,
                ["options" => ["regexp" => "/(0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])[- \/.](19|20)\d\d ([0-1]\d|2[0-3])(:[0-5]\d){2}/"]])
            ) {
                $errors[$key] = "Invalid date and time format";
            } elseif ($key == 'price' && !filter_var($value, FILTER_VALIDATE_FLOAT)) {
                $errors[$key] = "Wrong format of monetary value";
            }
        }
        return $errors;
    }
}
