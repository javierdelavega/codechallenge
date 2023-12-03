<?php

namespace App\Codechallenge\Catalog\Infrastructure\Persistence\Doctrine\Types;

use App\Codechallenge\Catalog\Domain\Model\ProductId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * ProductId Type object represents ProductId Value object for the doctrine mapping system.
 */
class ProductIdType extends Type
{
    public const TYPE_NAME = 'product_id';

    /**
     * @see Type::getSQLDeclaration()
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @see Type::convertToPHPValue()
     *
     * @param string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ProductId
    {
        return new ProductId($value);
    }

    /**
     * @see Type::convertToDatabaseValue()
     *
     * @param ProductId $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value;
    }

    /**
     * @see Type::getName()
     */
    public function getName(): string
    {
        return $this::TYPE_NAME;
    }
}
