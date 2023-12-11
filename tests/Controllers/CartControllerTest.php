<?php

namespace App\Tests\Controllers;

use App\Codechallenge\Auth\Application\Service\User\CreateGuestUserService;
use App\Codechallenge\Auth\Application\Service\User\CreateTokenService;
use App\Codechallenge\Auth\Application\Service\User\SignUpUserRequest;
use App\Codechallenge\Auth\Application\Service\User\SignUpUserService;
use App\Codechallenge\Billing\Application\Command\AddProductCommand;
use App\Codechallenge\Billing\Application\Command\UpdateProductCommand;
use App\Codechallenge\Billing\Application\Service\Cart\AddProductRequest;
use App\Codechallenge\Billing\Application\Service\Cart\AddProductService;
use App\Codechallenge\Billing\Application\Service\Cart\UpdateProductRequest;
use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartFactory;
use App\Codechallenge\Billing\Infrastructure\Domain\Model\Cart\DoctrineCartRepository;
use App\Codechallenge\Catalog\Domain\Model\Currency;
use App\Codechallenge\Catalog\Domain\Model\Money;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Infrastructure\Domain\Model\DoctrineProductRepository;
use App\Codechallenge\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{

  private $client;
  private $user;
  private $createGuestUserService;
  private $createTokenService;
  private $doctrineProductRepository;
  private $doctrineCartFactory;
  private $doctrineCartRepository;
  private $product;
  private CommandBus $commandBus;
  private AddProductCommand $addProductCommand;
  private UpdateProductCommand $updateProductCommand;
  private $signUpUserService;
  private $signUpUserRequest;
  private $quantity;
  private $newQuantity;

  protected function setUp() : void
  {
    $this->client = static::createClient();
    $container = $this->getContainer();

    $this->createGuestUserService = $container->get(CreateGuestUserService::class);
    $this->createTokenService = $container->get(CreateTokenService::class);

    $this->doctrineProductRepository = $container->get(DoctrineProductRepository::class);
    $this->doctrineCartFactory = $container->get(DoctrineCartFactory::class);
    $this->doctrineCartRepository = $container->get(DoctrineCartRepository::class);
    $this->commandBus = $container->get(CommandBus::class);
    $this->signUpUserService = $container->get(SignUpUserService::class);
    $this->signUpUserRequest = new SignUpUserRequest("testName", "test@email.com", "testPassword", "testAddress");

    $this->product = new Product(new ProductId(), "testReference", "testName", "testDescription", 
                                new Money(10.50, new Currency("EUR")));
    $this->doctrineProductRepository->save($this->product);

    $this->user = $this->createGuestUserService->execute();
    $this->quantity = 5;
    $this->newQuantity = 10;
    $this->addProductCommand = new AddProductCommand($this->user->id(), $this->product->id(), $this->quantity);
    $this->updateProductCommand = new UpdateProductCommand($this->user->id(), $this->product->id(), $this->newQuantity);

  }

  /** @test */
  public function canGetCartItems()
  {
    
    $token = $this->createTokenService->execute($this->user->id());

    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build(new CartId());
    $this->doctrineCartRepository->save($cart);
    $cart->addProduct($this->product->id(), $this->quantity);
    $this->doctrineCartRepository->save($cart);

    $crawler = $this->client->request('GET', '/api/cart/products',
      [],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token()
      ]
    );

    $this->assertResponseIsSuccessful();
  }

  /** @test */
  public function canGetItemCount()
  {
    
    $token = $this->createTokenService->execute($this->user->id());

    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build(new CartId());
    $this->doctrineCartRepository->save($cart);
    $cart->addProduct($this->product->id(), $this->quantity);
    $this->doctrineCartRepository->save($cart);

    $crawler = $this->client->request('GET', '/api/cart/products/count',
      [],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token()
      ]
    );
    $response = $this->client->getResponse();
    $responseData = json_decode($response->getContent(), true);

    $this->assertResponseIsSuccessful();
    $this->assertEquals($this->quantity, $responseData['count']);
    
  }

  /** @test */
  public function canGetCartTotal()
  {
    
    $token = $this->createTokenService->execute($this->user->id());

    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build(new CartId());
    $this->doctrineCartRepository->save($cart);
    
    $this->commandBus->dispatch($this->addProductCommand);

    $crawler = $this->client->request('GET', '/api/cart/products/total',
      [],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token()
      ]
    );
    $response = $this->client->getResponse();
    $responseData = json_decode($response->getContent(), true);

    $this->assertResponseIsSuccessful();
    $this->assertEquals($this->quantity * $this->product->price()->amount(), $responseData['total']);
  }

  /** @test */
  public function canAddProduct()
  {
    
    $token = $this->createTokenService->execute($this->user->id());

    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build(new CartId());
    $this->doctrineCartRepository->save($cart);
    

    $crawler = $this->client->request('POST', '/api/cart/product',
      [],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token(),
        'CONTENT_TYPE' => 'application/json'
      ],
      '{"id": "'.$this->addProductCommand->productId.'", "quantity": "'.$this->addProductCommand->quantity.'"}'
    );
    $response = $this->client->getResponse();
    $responseData = json_decode($response->getContent(), true);

    $this->assertResponseIsSuccessful();
  }

  /** @test */
  public function canUpdateProduct()
  {
    
    $token = $this->createTokenService->execute($this->user->id());

    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build(new CartId());
    $this->doctrineCartRepository->save($cart);
    

    $crawler = $this->client->request('POST', '/api/cart/product',
      [],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token(),
        'CONTENT_TYPE' => 'application/json'
      ],
      '{"id": "'.$this->addProductCommand->productId.'", "quantity": "'.$this->addProductCommand->quantity.'"}'
    );
    $this->assertResponseIsSuccessful();

    $crawler = $this->client->request('PUT', '/api/cart/product/'.$this->addProductCommand->productId,
      [],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token(),
        'CONTENT_TYPE' => 'application/json'
      ],
      '{"id": "'.$this->addProductCommand->productId.'", "quantity": "'.$this->addProductCommand->quantity.'"}'
    );
    $this->assertResponseIsSuccessful();
  }

  /** @test */
  public function canRemoveProduct()
  {
    
    $token = $this->createTokenService->execute($this->user->id());

    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build(new CartId());
    $this->doctrineCartRepository->save($cart);
    

    $crawler = $this->client->request('POST', '/api/cart/product',
      [],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token(),
        'CONTENT_TYPE' => 'application/json'
      ],
      '{"id": "'.$this->addProductCommand->productId.'", "quantity": "'.$this->addProductCommand->quantity.'"}'
    );
    $this->assertResponseIsSuccessful();

    $crawler = $this->client->request('DELETE', '/api/cart/product/'.$this->addProductCommand->productId,
      [],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token(),
        'CONTENT_TYPE' => 'application/json'
      ],
    );
    $this->assertResponseIsSuccessful();
  }

  /** @test */
  public function registeredUserCanConfirCartToPlaceOrder()
  {
    
    $token = $this->createTokenService->execute($this->user->id());
    $this->signUpUserService->execute($this->user->id(), $this->signUpUserRequest);

    $cart = $this->doctrineCartFactory->ofUser($this->user->id())->build(new CartId());
    $this->doctrineCartRepository->save($cart);
    

    $crawler = $this->client->request('POST', '/api/cart/product',
      [],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token(),
        'CONTENT_TYPE' => 'application/json'
      ],
      '{"id": "'.$this->addProductCommand->productId.'", "quantity": "'.$this->addProductCommand->quantity.'"}'
    );
    $this->assertResponseIsSuccessful();

    $crawler = $this->client->request('POST', '/api/cart/confirm',
      [],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token(),
        'CONTENT_TYPE' => 'application/json'
      ],
    );
    $this->assertResponseIsSuccessful();
  }
}