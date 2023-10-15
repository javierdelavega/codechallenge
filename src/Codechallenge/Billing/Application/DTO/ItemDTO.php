<?php

namespace App\Codechallenge\Billing\Application\DTO;

use App\Codechallenge\Billing\Domain\Model\Cart\Item;
use App\Codechallenge\Catalog\Domain\Model\Product;

/**
 * Data Transfer Object for delivery cart item data from Domain layer to Application layer
 */
class ItemDTO
{

  private $itemId;
  private $productId;
  private $reference;
  private $name;
  private $description;
  private $price;
  private $quantity;

  /**
   * Constructor
   * 
   * @param Item $item the cart item
   * @param Product $product the product from catalog
   */
  public function __construct(Item $item, Product $product)
  {
    $this->itemId = $item->id()->id();
    $this->productId = $product->id()->id();
    $this->reference = $product->reference();
    $this->name = $product->name();
    $this->description = $product->description();
    $this->price = $product->price()->amount();
    $this->quantity = $item->quantity();
  }

  /**
   * Get the user name
   * 
   * @return string the item id
   */
  public function id() : string
  {
    return $this->itemId;
  }

  /**
   * Get the product id
   * 
   * @return string the product id
   */
  public function productId() : string
  {
    return $this->productId;
  }

  /**
   * Get the product reference
   * 
   * @return string the product reference
   */
  public function reference() : string
  {
    return $this->reference;
  }

  /**
   * Get the product name
   * 
   * @return string the product name
   */
  public function name() : string
  {
    return $this->name;
  }

  /**
   * Get the product description
   * 
   * @return string the product description
   */
  public function description() : string
  {
    return $this->description;
  }

  /**
   * Get the product price
   * 
   * @return float the product price
   */
  public function price() : float
  {
    return $this->price;
  }

  /**
   * Get the quantity of the item in the cart
   * 
   * @return int the quantity of the item in the cart
   */
  public function quantity() : int
  {
    return $this->quantity;
  }
}