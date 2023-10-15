<?php

namespace App\Codechallenge\Billing\Infrastructure\Delivery\API;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\SecurityUser;
use App\Codechallenge\Billing\Application\Service\Cart\AddProductService;
use App\Codechallenge\Billing\Application\Service\Cart\AddProductRequest;
use App\Codechallenge\Billing\Application\Service\Cart\GetItemCountService;
use App\Codechallenge\Billing\Application\Service\Cart\GetItemsService;
use App\Codechallenge\Billing\Application\Service\Cart\GetCartTotalService;
use App\Codechallenge\Billing\Application\Service\Cart\RemoveProductService;
use App\Codechallenge\Billing\Application\Service\Cart\UpdateProductRequest;
use App\Codechallenge\Billing\Application\Service\Cart\UpdateProductService;
use App\Codechallenge\Billing\Application\Service\Order\CreateOrderFromCartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
  #[Route('/api/cart/products', methods: ['GET'])]
  public function items(#[CurrentUser] ?SecurityUser $securityUser, 
            GetItemsService $getItemsService, GetItemCountService $getItemCountService,
            GetCartTotalService $getCartTotalService)
  {
    $items = $getItemsService->execute(new UserId($securityUser->getUserIdentifier()));

    $jsonArray = array();

    $i = 0;
    foreach($items as $item) {
      $jsonArray["products"][$i] = 
      [
        'id' => $item->productId(),
        'reference' => $item->reference(),
        'name' => $item->name(),
        'description' => $item->description(),
        'price' => $item->price(),
        'quantity' => $item->quantity(),
      ];
      $i++;
    }
    $jsonArray['count'] = $getItemCountService->execute(new UserId($securityUser->getUserIdentifier()));
    $jsonArray['total'] = $getCartTotalService->execute(new UserId($securityUser->getUserIdentifier()));

    return new JsonResponse($jsonArray);
    
  }

  #[Route('/api/cart/products/count', methods: ['GET'])]
  public function itemsCount(#[CurrentUser] ?SecurityUser $securityUser, 
                                    GetItemCountService $getItemCountService)
  {
    $jsonArray['count'] = $getItemCountService->execute(new UserId($securityUser->getUserIdentifier()));
    return new JsonResponse($jsonArray);
  }

  #[Route('/api/cart/products/total', methods: ['GET'])]
  public function itemsTotal(#[CurrentUser] ?SecurityUser $securityUser, 
                                    GetCartTotalService $getCartTotalService)
  {
    $jsonArray['total'] = $getCartTotalService->execute(new UserId($securityUser->getUserIdentifier()));
    return new JsonResponse($jsonArray);
  }

  #[Route('/api/cart/product', methods: ['POST'])]
  public function addProduct(#[CurrentUser] ?SecurityUser $securityUser, Request $request, 
                            AddProductService $addProductService)
  {
    $request = $request->getPayload();
    $addProductRequest = new AddProductRequest($request->get("id"), $request->get("quantity"));
    $addProductService->execute(new UserId($securityUser->getUserIdentifier()), $addProductRequest);

    return new JsonResponse();
  }

  #[Route('/api/cart/product/{id}', methods: ['PUT'])]
  public function updateProduct(#[CurrentUser] ?SecurityUser $securityUser, Request $request, string $id, 
                            UpdateProductService $updateProductService)
  {
    $request = $request->getPayload();
    $updateProductRequest = new UpdateProductRequest($id, $request->get("quantity"));
    $updateProductService->execute(new UserId($securityUser->getUserIdentifier()), $updateProductRequest);

    return new JsonResponse();
  }

  #[Route('/api/cart/product/{id}', methods: ['DELETE'])]
  public function removeProduct(#[CurrentUser] ?SecurityUser $securityUser, string $id, 
                            RemoveProductService $removeProductService)
  {
    $removeProductService->execute(new UserId($securityUser->getUserIdentifier()), $id);

    return new JsonResponse();
  }

  #[Route('/api/cart/confirm', methods: ['POST'])]
  public function confirm(#[CurrentUser] ?SecurityUser $securityUser, 
                          CreateOrderFromCartService $createOrderFromCartService)
  {
    $createOrderFromCartService->execute(new UserId($securityUser->getUserIdentifier()));

    return new JsonResponse();
  }


}