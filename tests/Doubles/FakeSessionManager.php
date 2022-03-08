<?php

namespace UserLoginService\Tests\Doubles;

use UserLoginService\Application\SessionManager;

class FakeSessionManager implements SessionManager
{
    public function getSessions(): int
    {
        return 10;
    }

    public function login(string $userName, string $password): bool
    {
        if(strcmp($userName,'user_name')==0 and strcmp($password,'1234')==0){
            return true;
        }
        return false;
    }

    public function logout(string $userName)
    {

    }
    public function secureLogin(){}


}