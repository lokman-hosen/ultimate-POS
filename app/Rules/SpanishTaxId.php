<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a Spanish identification number against the selected document
 * type (DNI, NIE or CIF), including its control letter/digit.
 * Mirrors the client-side check in public/js/business_register.js.
 */
class SpanishTaxId implements ValidationRule
{
    const PATTERNS = [
        'DNI' => '/^[0-9]{8}[A-Z]$/',
        'NIE' => '/^[XYZ][0-9]{7}[A-Z]$/',
        'CIF' => '/^[ABCDEFGHJNPQRSUVW][0-9]{7}[0-9A-J]$/',
    ];

    const EXAMPLES = [
        'DNI' => '12345678Z',
        'NIE' => 'X1234567L',
        'CIF' => 'B12345674',
    ];

    const DNI_LETTERS = 'TRWAGMYFPDXBNJZSQVHLCKE';

    protected $type;

    public function __construct($type)
    {
        $this->type = strtoupper((string) $type);
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! self::isValid($this->type, $value)) {
            $fail(__('business.invalid_document_number', [
                'type' => $this->type,
                'example' => self::EXAMPLES[$this->type] ?? '',
            ]));
        }
    }

    public static function isValid($type, $value)
    {
        $value = strtoupper((string) $value);

        if (! isset(self::PATTERNS[$type]) || ! preg_match(self::PATTERNS[$type], $value)) {
            return false;
        }

        if ($type == 'DNI') {
            return self::DNI_LETTERS[(int) substr($value, 0, 8) % 23] === $value[8];
        }

        if ($type == 'NIE') {
            $number = strtr($value[0], ['X' => '0', 'Y' => '1', 'Z' => '2']).substr($value, 1, 7);

            return self::DNI_LETTERS[(int) $number % 23] === $value[8];
        }

        return self::isValidCif($value);
    }

    protected static function isValidCif($value)
    {
        $digits = substr($value, 1, 7);
        $sum = 0;
        for ($i = 0; $i < 7; $i++) {
            $n = (int) $digits[$i];
            if ($i % 2 == 0) {
                $n *= 2;
                $n = intdiv($n, 10) + ($n % 10);
            }
            $sum += $n;
        }
        $control_digit = (10 - ($sum % 10)) % 10;
        $control_letter = 'JABCDEFGHI'[$control_digit];
        $control = $value[8];

        //Entities that must use a letter / a digit as control character
        if (strpos('NPQRSW', $value[0]) !== false) {
            return $control === $control_letter;
        }
        if (strpos('ABEH', $value[0]) !== false) {
            return $control === (string) $control_digit;
        }

        return $control === (string) $control_digit || $control === $control_letter;
    }
}
