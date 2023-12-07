<?php

namespace App\Tests\Controllers;

use App\Codechallenge\Auth\Application\Service\User\CreateGuestUserService;
use App\Codechallenge\Auth\Application\Service\User\CreateTokenService;
use App\Codechallenge\Auth\Application\Service\User\SignUpUserRequest;
use App\Codechallenge\Auth\Application\Service\User\SignUpUserService;
use App\Codechallenge\Auth\Domain\Model\ApiToken;
use App\Codechallenge\Auth\Domain\Model\ApiTokenId;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\DoctrineApiTokenRepository;
use App\Codechallenge\Billing\Domain\Model\Order\Order;
use App\Codechallenge\Billing\Domain\Model\Order\OrderId;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Order\DoctrineOrderRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersControllerTest extends WebTestCase
{
  private $client;
  private $createGuestUserService;
  private $createTokenService;
  private $signUpUserService;
  private $doctrineOrderRepository;
  private $signUpUserRequest;
  private $invalidSignUpUserRequest;
  private $doctrineApiTokenRepository;

  private $registeredUser;
  private $guestUser;
  private $email = "test@email.com";
  private $plainPassword = "testPassword";

  protected function setUp() : void
  {
    $this->client = static::createClient();
    $container = $this->getContainer();

    $this->createGuestUserService = $container->get(CreateGuestUserService::class);
    $this->signUpUserService = $container->get(SignUpUserService::class);
    $this->createTokenService = $container->get(CreateTokenService::class);
    $this->doctrineApiTokenRepository = $container->get(DoctrineApiTokenRepository::class);
    $this->doctrineOrderRepository = $container->get(DoctrineOrderRepository::class);
    $this->signUpUserRequest = new SignUpUserRequest("testName", $this->email, $this->plainPassword, "testAddress");
    $this->invalidSignUpUserRequest = new SignUpUserRequest("testName", "invalidEmail", $this->plainPassword, "testAddress");
  }

  /** @test */
  public function firstTimeVisitorCanGetAccessToken()
  {

    $crawler = $this->client->request('GET', '/api/token');

    $this->assertResponseIsSuccessful();
  }

  /** @test */
  public function canNotAccessApiProvidingInvalidToken()
  {
    $token = new ApiToken(new ApiTokenId(), new UserId(), "testTokenString");

    $crawler = $this->client->request('POST', '/api/user/login',
      [
        "username" => $this->email,
        "password" => $this->plainPassword,
      ],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token()
      ]
    );
    $this->assertResponseStatusCodeSame(401);
  }

  /** @test */
  public function registeredUserCanLogin()
  {
    $this->guestUser = $this->createGuestUserService->execute();
    $token = $this->createTokenService->execute($this->guestUser->id());
    $this->registeredUser = $this->signUpUserService->execute($this->guestUser->id(), $this->signUpUserRequest);

    $crawler = $this->client->request('POST', '/api/user/login',
      [],
      [],
      ['CONTENT_TYPE' => 'application/json'],
      '{"username": "'.$this->email.'", "password": "'.$this->plainPassword.'"}'
    );
    $this->assertResponseIsSuccessful();
  }

  /** @test */
  public function notExistingUserCannotLogin()
  {
    $token = new ApiToken(new ApiTokenId(), new UserId(), "testTokenString");
    $this->doctrineApiTokenRepository->save($token);


    $crawler = $this->client->request('POST', '/api/user/login',
      [
        "username" => $this->email,
        "password" => $this->plainPassword,
      ],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token()
      ]
    );
    $this->assertResponseStatusCodeSame(401);
  }

  /** @test */
  public function canNotLoginWithWrongCredentials()
  {
    $this->guestUser = $this->createGuestUserService->execute();
    $token = $this->createTokenService->execute($this->guestUser->id());
    $this->registeredUser = $this->signUpUserService->execute($this->guestUser->id(), $this->signUpUserRequest);

    $secondGuestUser = $this->createGuestUserService->execute();
    $token = $this->createTokenService->execute($secondGuestUser->id());

    $crawler = $this->client->request('POST', '/api/user/login',
      [],
      [],
      ['CONTENT_TYPE' => 'application/json'],
      '{"username": "'.$this->email.'", "password": "badPassword"}'
    );
    $this->assertResponseStatusCodeSame(401);
  }

  /** @test */
  public function guestUsersCanSignUp()
  {
    $this->guestUser = $this->createGuestUserService->execute();
    $token = $this->createTokenService->execute($this->guestUser->id());
    
    $crawler = $this->client->request('POST', '/api/user/register',
      [
        "name" => $this->signUpUserRequest->name,
        "email" => $this->signUpUserRequest->email,
        "password" => $this->signUpUserRequest->password,
        "address" => $this->signUpUserRequest->address,
      ],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token()
      ]
    );

    $this->assertResponseIsSuccessful();
  }

  /** @test */
  public function guestUsersCanNotSignUpProvidingRegisteredEmail()
  {
    $this->guestUser = $this->createGuestUserService->execute();
    $this->registeredUser = $this->signUpUserService->execute($this->guestUser->id(), $this->signUpUserRequest);

    $anotherGuestUser = $this->createGuestUserService->execute();
    $token = $this->createTokenService->execute($anotherGuestUser->id());
    
    $crawler = $this->client->request('POST', '/api/user/register',
      [
        "name" => $this->signUpUserRequest->name,
        "email" => $this->signUpUserRequest->email,
        "password" => $this->signUpUserRequest->password,
        "address" => $this->signUpUserRequest->address,
      ],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token()
      ]
    );
    $this->assertResponseStatusCodeSame(400);
  }

  /** @test */
  public function guestUsersCanNotSignUpProvidingInvalidrequest()
  {
    $this->guestUser = $this->createGuestUserService->execute();
    $token = $this->createTokenService->execute($this->guestUser->id());
    
    $crawler = $this->client->request('POST', '/api/user/register',
      [
        "name" => $this->invalidSignUpUserRequest->name,
        "email" => $this->invalidSignUpUserRequest->email,
        "password" => $this->invalidSignUpUserRequest->password,
        "address" => $this->invalidSignUpUserRequest->address,
      ],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token()
      ]
    );
    $this->assertResponseStatusCodeSame(400);
  }

  /** @test */
  public function canGetGuestUserInformation()
  {
    $this->guestUser = $this->createGuestUserService->execute();
    $token = $this->createTokenService->execute($this->guestUser->id());

    $crawler = $this->client->request('GET', '/api/user',
      [],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token()
      ]
    );

    $this->assertResponseIsSuccessful();
  }

  /** @test */
  public function canGetRegisteredUserInformation()
  {
    $this->guestUser = $this->createGuestUserService->execute();
    $this->registeredUser = $this->signUpUserService->execute($this->guestUser->id(), $this->signUpUserRequest);
    $token = $this->createTokenService->execute($this->guestUser->id());

    $crawler = $this->client->request('GET', '/api/user',
      [],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token()
      ]
    );

    $this->assertResponseIsSuccessful();
  }

  /** @test */
  public function canNotGetUnexistingUserInformation()
  {
    $token = new ApiToken(new ApiTokenId(), new UserId(), "testTokenString");
    $this->doctrineApiTokenRepository->save($token);

    $crawler = $this->client->request('GET', '/api/user',
      [],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token()
      ]
    );

    $this->assertResponseStatusCodeSame(401);
  }

  /** @test */
  public function canGetUserOrders()
  {
    $this->guestUser = $this->createGuestUserService->execute();
    $token = $this->createTokenService->execute($this->guestUser->id());
    $this->registeredUser = $this->signUpUserService->execute($this->guestUser->id(), $this->signUpUserRequest);
    $order = new Order(new OrderId(), $this->guestUser->id(), $this->registeredUser->address);
    $this->doctrineOrderRepository->save($order);


    $crawler = $this->client->request('GET', '/api/user/orders',
      [],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token()
      ]
    );

    $this->assertResponseIsSuccessful();
  }
}