<?php

namespace UserLoginService\Tests\Doubles;



use PHPUnit\Util\Exception;
use UserLoginService\Application\SessionManager;

class MockSessionManager implements SessionManager
{
    public int $times;
    public string $userName;
    public string $exception;
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
        // TODO: Implement logout() method.
    }

    public function secureLogin(string $userName)
    {
        $this->calls++;
        throw(new Exception($this->exception));

    }
    public function times(int $numberCalls){
        $this->times = $numberCalls;
    }
    public function withArguments(string $userName){
        $this->userName = $userName;
    }
    public function andThrowException(string $exception){
        $this->exception =  $exception;
    }
    public function verifyValid():bool
    {
        if($this->times == $this->calls){
            return true;
        }
        return false;
    }


}