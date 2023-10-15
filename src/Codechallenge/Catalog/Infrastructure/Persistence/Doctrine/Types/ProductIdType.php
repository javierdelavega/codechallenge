<?php

namespace App\Codechallenge\Catalog\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use App\Codechallenge\Catalog\Domain\Model\ProductId;

/**
 * ProductId Type object represents ProductId Value object for the doctrine mapping system
 */
class ProductIdType extends Type
{
  public const TYPE_NAME = 'product_id';
  /**
   * @see Type::getSQLDeclaration()
   * @return string
   */
  public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform) : string
  {
    return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
  }

  /**
   * @see Type::convertToPHPValue()
   * @param string $value
   * @return ProductId
   */
  public function convertToPHPValue($value, AbstractPlatform $platform) : ProductId
  {
    return new ProductId($value);
  }

  /**
   * @see Type::convertToDatabaseValue()
   * @param ProductId $value
   * @return mixed
   */
  public function convertToDatabaseValue($value, AbstractPlatform $platform) : mixed
  {
    return $value;
  }

  /**
   * @see Type::getName()
   * @return string
   */
  public function getName() : string
  {
    return $this::TYPE_NAME;
  }
}