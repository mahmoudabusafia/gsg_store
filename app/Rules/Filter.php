<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Filter implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

     protected $words;
     protected $filtered = [];

    public function __construct($words)
    {
        $this->words = $words;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach($this->words as $word){
            if (stripos($value, $word) !== false){
                $this->filtered[] = $word;
            }
        }
            return empty($this->filtered);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You cannt use "'. implode(',', $this->filtered ) .'" word in your input';
    }
}
