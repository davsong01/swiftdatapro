<?php 
namespace App\Guards;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Contracts\Auth\Authenticatable;

class AdminGuard implements Guard
{
    protected $request;
    protected $provider;
    protected $user;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        // Custom logic to retrieve the admin user based on the request
        $token = $this->request->bearerToken();

        if ($token) {
            // Find the user by token
            $user = $this->provider->retrieveByCredentials(['api_token' => $token]);
            if ($user && $this->provider->validateCredentials($user, ['api_token' => $token])) {
                return $this->user = $user;
            }
        }

        return null;
    }

    public function check()
    {
        return !is_null($this->user());
    }

    public function guest()
    {
        return !$this->check();
    }

    public function id()
    {
        if ($this->user()) {
            return $this->user()->getAuthIdentifier();
        }
    }

    public function validate(array $credentials = [])
    {
        // Custom validation logic
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }
}
