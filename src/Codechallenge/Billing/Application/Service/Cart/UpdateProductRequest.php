<?php

namespace App\Codechallenge\Billing\Application\Service\Cart;

/**
 * Request for update a cart product a user
 */
class UpdateProductRequest
{
  private $id;
  private $quantity;

  /**
   * Constructor
   * 
   * @param string $id the product id
   * @param int $quantity quantity
   */
  public function __construct($id, $quantity)
  {
    $this->id = $id;
    $this->quantity = $quantity;
  }

  /**
   * Get the product id
   * 
   * @return string the product id
   */
  public function id() : string
  {
    return $this->id;
  }

  /**
   * Get the quantity
   * 
   * @return int the quantity
   */
  public function quantity()
  {
    return $this->quantity;
  }

}