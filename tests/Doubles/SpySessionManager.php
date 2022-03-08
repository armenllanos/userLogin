<?php

namespace UserLoginService\Tests\Doubles;

use PHPUnit\Util\Exception;
use UserLoginService\Application\SessionManager;
use function PHPUnit\Framework\throwException;

class SpySessionManager implements SessionManager
{
    private int $calls = 0;
    public function getSessions(): int
    {
        // TODO: Implement getSessions() method.
    }

    public function login(string $userName, string $password): bool
    {
        // TODO: Implement login() method.
    }

    public function logout(string $userName)
    {
        $this->calls++;
    }

    /**
     * @return int
     */
    public function getCalls(): int
    {
        return $this->calls;
    }

    public function varifyCalls(int $callsExpected)
    {
        if($this->calls != $callsExpected){
            throw(new Exception("Numero de llamadas incorrecto,hay $callsExpected pero se esperaban $this->calls"));
        }
    }
    public function secureLogin(){}


}