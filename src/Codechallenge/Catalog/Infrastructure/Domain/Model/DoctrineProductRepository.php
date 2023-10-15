<?php

namespace App\Codechallenge\Catalog\Infrastructure\Domain\Model;

use App\Codechallenge\Catalog\Domain\Model\Product;
use App\Codechallenge\Catalog\Domain\Model\ProductId;
use App\Codechallenge\Catalog\Domain\Model\ProductRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for manage the products using Doctrine ORM
 */
class DoctrineProductRepository extends ServiceEntityRepository implements ProductRepository
{
  private $entityManager;

  /**
   * Constructor
   * 
   * @param ManagerRegistry $registry the doctrine manager registry
   */
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Product::class);
    $this->entityManager = $this->getEntityManager();
  }

  /**
   * Adds a product and persist in the database
   * 
   * @param Product $product
   */
  public function save(Product $product)
  {
    $this->entityManager->persist($product);
    $this->entityManager->flush();
  }

  /**
   * Removes a product and delete from the database
   * 
   * @param Product $product
   */
  public function remove(Product $product)
  {
    $this->entityManager->remove($product);
    $this->entityManager->flush();
  }

  /**
   * Get the lists of products from the catalog
   * 
   * @return Array the products from the catalog
   */
  public function products() : Array
  {

    return $this->createQueryBuilder('p')
          ->orderBy('p.reference')
          ->getQuery()
          ->getResult()
      ;
  }

  /**
   * Retrieves a product of the given id from the database
   * 
   * @param ProductId $productId the product id
   * @return Product|null the product, null if does not exist
   */
  public function productOfId(ProductId $productId) : Product|null
  {
    return $this->entityManager->find('App\Codechallenge\Catalog\Domain\Model\Product', $productId);
  }

  /**
   * 
   * Retrieves a product with the given reference from the database
   * 
   * @param string $reference the product reference
   * @return Product
   */
  public function productOfReference($reference) : Product
  {
    return $this->findOneBy(['reference' => $reference]);
  }

  /**
   * Gets a new unique Product id
   * 
   * @return ProductId
   */
  public function nextIdentity() : ProductId
  {
    return new ProductId();
  }
}