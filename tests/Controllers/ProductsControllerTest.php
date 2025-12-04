<?php

namespace App\Tests\Controllers;

use App\Codechallenge\Auth\Application\Service\User\CreateGuestUserService;
use App\Codechallenge\Auth\Application\Service\User\CreateTokenService;
use App\Codechallenge\Catalog\Domain\Model\Currency;
use App\Codechallenge\Catalog\Domain\Model\Money;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Infrastructure\Domain\Model\DoctrineProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductsControllerTest extends WebTestCase
{
  private $client;
  private $createGuestUserService;
  private $createTokenService;
  private $productRepository;
  private $product;
  private $guestUser;

  protected function setUp() : void
  {
    $this->client = static::createClient();
    $container = $this->getContainer();

    $this->createGuestUserService = $container->get(CreateGuestUserService::class);
    $this->createTokenService = $container->get(CreateTokenService::class);

    $this->productRepository = $container->get(DoctrineProductRepository::class);
    $this->product = new Product(new ProductId(), "testReference", "testName", "testDescription", 
                                new Money(30.60, new Currency("EUR")));
    $this->productRepository->save($this->product);
  }

  /** @test */
  public function canGetProductList()
  {
    $this->guestUser = $this->createGuestUserService->execute();
    $token = $this->createTokenService->execute($this->guestUser->id());
    

    $crawler = $this->client->request('GET', '/api/products',
      [],
      [],
      [
        "HTTP_AUTHORIZATION" => "Bearer ".$token->token()
      ]
    );

    $this->assertResponseIsSuccessful();
  }
}