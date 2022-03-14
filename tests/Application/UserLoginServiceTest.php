<?php

declare(strict_types=1);

namespace UserLoginService\Tests\Application;

use Mockery;
use PHPUnit\Framework\TestCase;
use UserLoginService\Application\SessionManager;
use UserLoginService\Application\UserLoginService;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\FacebookSessionManager;
use UserLoginService\Tests\Doubles\DummySessionManager;
use UserLoginService\Tests\Doubles\FakeSessionManager;
use UserLoginService\Tests\Doubles\MockSessionManager;
use UserLoginService\Tests\Doubles\SpySessionManager;
use UserLoginService\Tests\Doubles\StubSessionManager;

final class UserLoginServiceTest extends TestCase
{

    /**
     * @test
     */
    public function userIsLoggedIn()
    {
        $user = new User('user_name');
        $userLoginService = new UserLoginService(Mockery::mock(SessionManager::class));
        $userLoginService->manualLogin($user);
        $this->assertEquals([$user], $userLoginService->getLoggedUsers());
    }
    /**
     * @test
     */
    public function noUsersIsEmpty()
    {
        $userLoginService = new UserLoginService(Mockery::mock(SessionManager::class));
        $this->assertEmpty( $userLoginService->getLoggedUsers());
    }
    /**
     * @test
     */
    public function returnNumberOfSessions()
    {
        $sessionMananger = Mockery::mock(SessionManager::class);
        $sessionMananger->shouldReceive('getSessions')->andReturn(10);
        $userLoginService = new UserLoginService($sessionMananger);
        $externalSessions = $userLoginService->countExternalSessions();
        $this->assertEquals($externalSessions, 10);
    }
    /**
     * @test
     */
    public function correctLoginTest()
    {
        $userLoginService = new UserLoginService(new FakeSessionManager());
        $user = new User('user_name');
        $externalSessions = $userLoginService->login('user_name','1234');
        $this->assertEquals([$user], $userLoginService->getLoggedUsers());
    }
    /**
     * @test
     */
    public function incorrectLoginTest()
    {
        $userLoginService = new UserLoginService(new FakeSessionManager());
        $user = new User('user_name1');
        $externalSessions = $userLoginService->login('user_name1','1234');
        $this->assertEmpty($userLoginService->getLoggedUsers());
    }
    /**
     * @test
     */
    public function incorrectLogOut(){
        $userLoginService = new UserLoginService(new StubSessionManager());
        $user = new User('user_name_falso');
        $respuesta = $userLoginService->logOut($user);
        $this->assertEquals($respuesta,"Usuario no logeado");
    }
    /**
     * @test
     */
    public function correctLogOut(){
        $spySessionManager = new SpySessionManager();
        $userLoginService = new UserLoginService($spySessionManager);
        $user = new User('user_name');
        $userLoginService->manualLogin($user);
        $respuesta = $userLoginService->logOut($user);
        $spySessionManager->varifyCalls(1);
        $this->assertEquals($respuesta,"Ok");
    }
    /**
     * @test
     */
    public function userNotSecureLoggedIn(){
        $sessionManager = new MockSessionManager;
        $userLoginService = new UserLoginService($sessionManager);
        $user = new User('user_name');
        $sessionManager->times(1);
        $sessionManager->withArguments('user_name');
        $sessionManager->andThrowException('Service not found');

        $respuesta = $userLoginService->secureLogin($user);
        $this->assertTrue($sessionManager->verifyValid());
        $this->assertEquals($respuesta,"Servicio no responde");
    }

}
