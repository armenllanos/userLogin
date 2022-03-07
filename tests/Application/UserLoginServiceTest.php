<?php

declare(strict_types=1);

namespace UserLoginService\Tests\Application;

use PHPUnit\Framework\TestCase;
use UserLoginService\Application\UserLoginService;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\FacebookSessionManager;
use UserLoginService\Tests\Doubles\DummySessionManager;
use UserLoginService\Tests\Doubles\StubSessionManager;

final class UserLoginServiceTest extends TestCase
{

    /**
     * @test
     */
    public function userIsLoggedIn()
    {
        $user = new User('user_name');
        $userLoginService = new UserLoginService(new DummySessionManager());
        $userLoginService->manualLogin($user);
        $this->assertEquals([$user], $userLoginService->getLoggedUsers());
    }
    /**
     * @test
     */
    public function noUsersIsEmpty()
    {
        $userLoginService = new UserLoginService(new DummySessionManager());
        $this->assertEmpty( $userLoginService->getLoggedUsers());
    }
    /**
     * @test
     */
    public function returnNumberOfSessions()
    {
        $userLoginService = new UserLoginService(new StubSessionManager());
        $externalSessions = $userLoginService->countExternalSessions();
        $this->assertEquals($externalSessions, 10);
    }

}
