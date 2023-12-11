<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Infrastructure\Delivery\API;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\SecurityUser;
use App\Codechallenge\Billing\Application\Command\AddProductCommand;
use App\Codechallenge\Billing\Application\Command\RemoveProductCommand;
use App\Codechallenge\Billing\Application\Command\UpdateProductCommand;
use App\Codechallenge\Billing\Application\Query\GetCartTotalQuery;
use App\Codechallenge\Billing\Application\Query\GetItemCountQuery;
use App\Codechallenge\Billing\Application\Query\GetItemsQuery;
use App\Codechallenge\Billing\Application\Service\Order\CreateOrderFromCartService;
use App\Codechallenge\Shared\Domain\Bus\Command\CommandBus;
use App\Codechallenge\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class CartController extends AbstractController
{
    #[Route('/api/cart/products', methods: ['GET'])]
    public function items(#[CurrentUser] ?SecurityUser $securityUser,
        QueryBus $queryBus): JsonResponse
    {
        $items = $queryBus->dispatch(new GetItemsQuery(new UserId($securityUser->getUserUuid())));

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
        $jsonArray['count'] = $queryBus->dispatch(new GetItemCountQuery(new UserId($securityUser->getUserUuid())));
        $jsonArray['total'] = $queryBus->dispatch(new GetCartTotalQuery(new UserId($securityUser->getUserUuid())));

        return new JsonResponse($jsonArray);
    }

    #[Route('/api/cart/products/count', methods: ['GET'])]
    public function itemsCount(#[CurrentUser] ?SecurityUser $securityUser,
        QueryBus $queryBus): JsonResponse
    {
        $jsonArray['count'] = $queryBus->dispatch(new GetItemCountQuery(new UserId($securityUser->getUserUuid())));

        return new JsonResponse($jsonArray);
    }

    #[Route('/api/cart/products/total', methods: ['GET'])]
    public function itemsTotal(#[CurrentUser] ?SecurityUser $securityUser,
        QueryBus $queryBus): JsonResponse
    {
        $jsonArray['total'] = $queryBus->dispatch(new GetCartTotalQuery(new UserId($securityUser->getUserUuid())));

        return new JsonResponse($jsonArray);
    }

    #[Route('/api/cart/product', methods: ['POST'])]
    public function addProduct(#[CurrentUser] ?SecurityUser $securityUser, Request $request,
        CommandBus $commandBus): JsonResponse
    {
        $request = $request->getPayload();

        $commandBus->dispatch(
            new AddProductCommand(
                new UserId($securityUser->getUserUuid()),
                $request->get('id'),
                $request->getInt('quantity')
            )
        );

        return new JsonResponse();
    }

    #[Route('/api/cart/product/{id}', methods: ['PUT'])]
    public function updateProduct(#[CurrentUser] ?SecurityUser $securityUser, Request $request, string $id,
        CommandBus $commandBus): JsonResponse
    {
        $request = $request->getPayload();

        $commandBus->dispatch(
            new UpdateProductCommand(
                new UserId($securityUser->getUserUuid()),
                $request->get('id'),
                $request->getInt('quantity')
            )
        );

        return new JsonResponse();
    }

    #[Route('/api/cart/product/{id}', methods: ['DELETE'])]
    public function removeProduct(#[CurrentUser] ?SecurityUser $securityUser, string $id,
        CommandBus $commandBus): JsonResponse
    {
        $commandBus->dispatch(
            new RemoveProductCommand(
                new UserId($securityUser->getUserUuid()),
                $id
            )
        );

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
