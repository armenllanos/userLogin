<?php

namespace UserLoginService\Application;



use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\FacebookSessionManager;

class UserLoginService
{
    private array $loggedUsers = [];
    private int $numberSessions;
    private SessionManager $sessionManager;

    /**
     * @param SessionManager $sessionManager
     */
    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    /**
     * @return array
     */
    public function getLoggedUsers(): array
    {
        return $this->loggedUsers;
    }

    public function manualLogin(User $user): void
    {
        $this->loggedUsers[] = $user;
    }
    public function countExternalSessions():int{
        $this->numberSessions = $this->sessionManager->getSessions();
        return $this->numberSessions;
    }

    /**
     * @return int
     */
    public function getNumberSessions(): int
    {
        return $this->numberSessions;
    }

    /**
     * @return SessionManager
     */
    public function getSessionManager(): SessionManager
    {
        return $this->sessionManager;
    }

    public function login(string $userName, string $passWord)
    {
        if($this->sessionManager->login($userName,$passWord)){
            $this->manualLogin(new User($userName));
        }
    }


}