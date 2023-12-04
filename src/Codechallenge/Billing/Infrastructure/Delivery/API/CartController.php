<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Infrastructure\Delivery\API;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\SecurityUser;
use App\Codechallenge\Billing\Application\Service\Cart\AddProductRequest;
use App\Codechallenge\Billing\Application\Service\Cart\AddProductService;
use App\Codechallenge\Billing\Application\Service\Cart\GetCartTotalService;
use App\Codechallenge\Billing\Application\Service\Cart\GetItemCountService;
use App\Codechallenge\Billing\Application\Service\Cart\GetItemsService;
use App\Codechallenge\Billing\Application\Service\Cart\RemoveProductRequest;
use App\Codechallenge\Billing\Application\Service\Cart\RemoveProductService;
use App\Codechallenge\Billing\Application\Service\Cart\UpdateProductRequest;
use App\Codechallenge\Billing\Application\Service\Cart\UpdateProductService;
use App\Codechallenge\Billing\Application\Service\Order\CreateOrderFromCartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class CartController extends AbstractController
{
    #[Route('/api/cart/products', methods: ['GET'])]
    public function items(#[CurrentUser] ?SecurityUser $securityUser,
        GetItemsService $getItemsService, GetItemCountService $getItemCountService,
        GetCartTotalService $getCartTotalService): JsonResponse
    {
        $items = $getItemsService->execute(new UserId($securityUser->getUserUuid()));

        $jsonArray = [];

        $i = 0;
        foreach ($items as $item) {
            $jsonArray['products'][$i] =
            [
              'id' => $item->productId,
              'reference' => $item->reference,
              'name' => $item->name,
              'description' => $item->description,
              'price' => $item->price,
              'quantity' => $item->quantity,
            ];
            ++$i;
        }
        $jsonArray['count'] = $getItemCountService->execute(new UserId($securityUser->getUserUuid()));
        $jsonArray['total'] = $getCartTotalService->execute(new UserId($securityUser->getUserUuid()));

        return new JsonResponse($jsonArray);
    }

    #[Route('/api/cart/products/count', methods: ['GET'])]
    public function itemsCount(#[CurrentUser] ?SecurityUser $securityUser,
        GetItemCountService $getItemCountService): JsonResponse
    {
        $jsonArray['count'] = $getItemCountService->execute(new UserId($securityUser->getUserUuid()));

        return new JsonResponse($jsonArray);
    }

    #[Route('/api/cart/products/total', methods: ['GET'])]
    public function itemsTotal(#[CurrentUser] ?SecurityUser $securityUser,
        GetCartTotalService $getCartTotalService): JsonResponse
    {
        $jsonArray['total'] = $getCartTotalService->execute(new UserId($securityUser->getUserUuid()));

        return new JsonResponse($jsonArray);
    }

    #[Route('/api/cart/product', methods: ['POST'])]
    public function addProduct(#[CurrentUser] ?SecurityUser $securityUser, Request $request,
        AddProductService $addProductService): JsonResponse
    {
        $request = $request->getPayload();
        $addProductRequest = new AddProductRequest($request->get('id'), $request->getInt('quantity'));
        $addProductService->execute(new UserId($securityUser->getUserUuid()), $addProductRequest);

        return new JsonResponse();
    }

    #[Route('/api/cart/product/{id}', methods: ['PUT'])]
    public function updateProduct(#[CurrentUser] ?SecurityUser $securityUser, Request $request, string $id,
        UpdateProductService $updateProductService): JsonResponse
    {
        $request = $request->getPayload();
        $updateProductRequest = new UpdateProductRequest($id, $request->getInt('quantity'));
        $updateProductService->execute(new UserId($securityUser->getUserUuid()), $updateProductRequest);

        return new JsonResponse();
    }

    #[Route('/api/cart/product/{id}', methods: ['DELETE'])]
    public function removeProduct(#[CurrentUser] ?SecurityUser $securityUser, string $id,
        RemoveProductService $removeProductService): JsonResponse
    {
        $removeProductRequest = new RemoveProductRequest($id);
        $removeProductService->execute(new UserId($securityUser->getUserUuid()), $removeProductRequest);

        return new JsonResponse();
    }

    #[Route('/api/cart/confirm', methods: ['POST'])]
    public function confirm(#[CurrentUser] ?SecurityUser $securityUser,
        CreateOrderFromCartService $createOrderFromCartService): JsonResponse
    {
        $createOrderFromCartService->execute(new UserId($securityUser->getUserUuid()));

        return new JsonResponse();
    }
}
