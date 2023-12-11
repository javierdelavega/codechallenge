<?php

namespace App\Codechallenge\Catalog\Infrastructure\Persistence\Doctrine\DataFixtures;

use App\Codechallenge\Catalog\Domain\Model\Currency;
use App\Codechallenge\Catalog\Domain\Model\Money;
use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

/**
 * @codeCoverageIgnore
 */
class ProductFixtures extends Fixture
{
    public function load(ObjectManager $objectManager)
    {
        $uuids = [
          '311e382e-b187-42c4-919e-814d925f08ba',
          'a84e69a1-d04b-43c3-b46b-668ddcc40fba',
          'af97cfc4-0854-4d32-89b9-5de604340596',
          'c2230dc1-fa3d-4674-bc43-4a587c25fbfa',
          'c378084d-1dcb-49aa-8c3f-00c2965959db',
          'ec52f544-b7d3-4aae-a322-5e6631740b65',
        ];

        $products = [
          ['reference' => 'SKU00001', 'name' => 'MUNDAKA', 'description' => 'Gafas de sol MUNDAKA', 'price' => '35'],
            ['reference' => 'SKU00002', 'name' => 'MACBA', 'description' => 'Gafas de sol MACBA', 'price' => '35'],
            ['reference' => 'SKU00003', 'name' => 'MONZA', 'description' => 'Gafas de sol MONZA', 'price' => '49'],
            ['reference' => 'SKU00004', 'name' => 'CAMEL', 'description' => 'Gafas de sol CAMEL', 'price' => '35'],
            ['reference' => 'SKU00005', 'name' => 'FIJI', 'description' => 'Gafas de sol FIJI', 'price' => '35'],
            ['reference' => 'SKU00006', 'name' => 'THE CITY', 'description' => 'Gafas de sol THE CITY', 'price' => '59'],
        ];

        $i = 0;
        foreach ($products as $product) {
            $fixture = new Product(
                new ProductId(new Uuid($uuids[$i])),
                $product['reference'],
                $product['name'],
                $product['description'],
                new Money($product['price'], new Currency('EUR'))
            );
            $objectManager->persist($fixture);
            ++$i;
        }
        $objectManager->flush();
    }
}
