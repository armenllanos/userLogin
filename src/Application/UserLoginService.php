<?php

namespace UserLoginService\Application;



use PHPUnit\Exception;
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

    public function login(string $userName, string $passWord): string
    {
        if($this->sessionManager->login($userName,$passWord)){
            $this->manualLogin(new User($userName));
            return "Login correcto";
        }
        return "Login incorrecto";
    }

    public function logOut(User $user): string
    {
        if(in_array($user,$this->loggedUsers)){
            unset($this->loggedUsers[array_search($user,$this->loggedUsers)]);
            $this->sessionManager->logout($user->getUserName());
            return "Ok";
        }
        return "Usuario no logeado";
    }

    public function secureLogin(User $user): string
    {
        try{
            $this->sessionManager->secureLogin($user->getUserName());
        }catch (Exception $exception){
            if($exception->getMessage() === "user does not exist"){
                return "Usuario no existe";
            }
            if($exception->getMessage() === "user incorrect credentials"){
                return "Usuario no existe";
            }
            if($exception->getMessage() === 'Service not found'){
                return "Servicio no responde";
            }
        }

    }


}