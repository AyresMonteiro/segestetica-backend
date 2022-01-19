<?php

namespace App\Rules;

use App\Utils\TranslatedAttributeName;
use Illuminate\Contracts\Validation\Rule;

class UnicodeText implements Rule
{
    public const pattern = "/^(\p{L}| |'|\.|,|\?|!|\"|\d|\\\$|;|\(|\)|:)+$/u";
    public ?string $attributeName = null;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $this->attributeName = $attribute;

        return preg_match(self::pattern, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        $attribute = TranslatedAttributeName::get(
            'validation.attributes.' . $this->attributeName
        );

        return __(
            'validation.unicode_text',
            ['attribute' => $attribute]
        );
    }
}
