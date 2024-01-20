<?php

namespace App\Rules;
use DB;

use Illuminate\Contracts\Validation\Rule;

class UniqueOrFieldFormForUser implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $user_id;
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
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
        // Check if the combination of user_id and ortype_id already exists
        return !DB::table('cto_payment_or_setups')
            ->where('user_id', $this->user_id)
            ->where('or_field_form', $value)
            ->exists();
    }

    public function message()
    {
        return 'System Default [OR Type] is already added.';
    }
}
