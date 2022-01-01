<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UnicodeName implements Rule
{
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
        $valid_pattern = "/^(\p{L}| |'|\.)+$/u";

        $this->attributeName = $attribute;

        return preg_match($valid_pattern, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        $attributeNameTranslationKey = 'validation.attributes.' . $this->attributeName;

        return __('validation.unicode_string', [
            'attribute' => __($attributeNameTranslationKey) !== null ?
                __($attributeNameTranslationKey) :
                $this->attributeName
        ]);
    }
}
