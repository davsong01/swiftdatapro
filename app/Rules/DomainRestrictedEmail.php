<?php

namespace App\Rules;

use App\Models\WebSetting;
use Illuminate\Contracts\Validation\Rule;

class DomainRestrictedEmail implements Rule
{
    private $allowedDomains;

    
    public function __construct()
    {
        $allowedDomains = $allowedDomains = WebSetting::first('allowed_domain_emails_for_registration');
        $this->allowedDomains = !empty($allowedDomains) ? json_decode($allowedDomains, true) : [];
        
    }
    /**
     * List of allowed email domains.
     *
     * @var array
     */
    

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $domain = substr(strrchr($value, "@"), 1);
        
        return !in_array($domain, $this->allowedDomains);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The email must be a valid email address from allowed domains: ' . implode(',', $this->allowedDomains);
    }
}
