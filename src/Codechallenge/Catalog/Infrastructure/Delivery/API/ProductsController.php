<?php

declare(strict_types=1);

namespace App\Codechallenge\Catalog\Infrastructure\Delivery\API;

use App\Codechallenge\Catalog\Application\Service\ListProductsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * GET api/products.
 *
 * Return a json with the products of the catalog
 *
 * @response scenario=success {
 * "data": [
 *   {
 *       "id": 1,
 *       "reference": "SKU00001",
 *       "name": "MUNDAKA",
 *       "description": "Gafas de sol MUNDAKA",
 *       "price": 35,
 *   }
 *  ]
 * }
 * @response status=401 scenario="unauthenticated" {"message": "Unauthenticated"}
 *
 * @return Illuminate\Http\JsonResponse json with the content of the cart, count of items and total price
 */
class ProductsController extends AbstractController
{
    #[Route('/api/products', name: 'api_products', methods: ['GET'])]
    public function products(ListProductsService $listProductsService): JsonResponse
    {
        $products = $listProductsService->execute();
        $jsonArray = [];

        $i = 0;
        foreach ($products as $product) {
            $jsonArray['data'][$i] =
            [
              'id' => $product->id,
              'reference' => $product->reference,
              'name' => $product->name,
              'description' => $product->description,
              'price' => $product->price,
            ];
            ++$i;
        }

        return new JsonResponse($jsonArray);
    }
}
