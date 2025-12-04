<?php

declare(strict_types=1);

namespace App\Codechallenge\Catalog\Domain\Model;

use Symfony\Component\Uid\Uuid;

/**
 * Value Object for Product id management.
 */
class ProductId
{
    private Uuid $id;

    /**
     * Constructor.
     *
     * @param Uuid|null an Uuid product identity
     */
    public function __construct(Uuid $id = null)
    {
        $this->id = $id ?: Uuid::v4();
    }

    /**
     * Static method for create an ProductId object.
     *
     * @param Uuid|null an Uuid item identity
     *
     * @return ProductId new OrderId object
     */
    public static function create(Uuid $anId = null): ProductId
    {
        return new static($anId);
    }

    /**
     * Get the id string.
     *
     * @return string the id string
     */
    public function __toString(): string
    {
        return $this->id->__toString();
    }

    /**
     * Get the id.
     *
     * @return string the id string
     */
    public function id(): string
    {
        return $this->id->__toString();
    }

    /**
     * Compare given ProductId with this ProductId.
     *
     * @param ProductId $productId the product id to compare
     *
     * @return bool true if the id are equals, false if different
     */
    public function equals(ProductId $productId): bool
    {
        return $this->id() === $productId->id();
    }
}
