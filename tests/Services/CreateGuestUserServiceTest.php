<?php

namespace App\Tests\Services;

use App\Codechallenge\Auth\Application\Service\User\CreateGuestUserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateGuestUserServiceTest extends KernelTestCase
{
  private $createGuestUserService;

  protected function setUp(): void
  {
    self::bootKernel();

    $container = static::getContainer();

    $this->createGuestUserService = $container->get(CreateGuestUserService::class);

  }

  /** @test */
  public function canCreateGuestUser()
  {
    $user = $this->createGuestUserService->execute();

    $this->assertFalse($user->registered());
  }
}