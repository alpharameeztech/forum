<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class UniqueShopAndEmail implements Rule
{

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
    public function passes($attribute, $value)
    {
            $shop_id = Cache::get('shop_id');

            $email = $value;

             $result  = User::where('shop_id',$shop_id)
                    ->where('email', $email)
                    ->exists();
             $result = !$result;

             return $result;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This email address already exist.';
    }
}
