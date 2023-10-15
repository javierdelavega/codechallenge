<?php

namespace App\Codechallenge\Billing\Infrastructure\Persistence\Cart\Doctrine\Types;

use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * CartId Type object represents CartId Value object for the doctrine mapping system
 */
class CartIdType extends Type
{
  public const TYPE_NAME = "cart_id";
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
   * @return CartId
   */
  public function convertToPHPValue($value, AbstractPlatform $platform) : CartId
  {
    return new CartId($value);
  }

  /**
   * @see Type::convertToDatabaseValue()
   * @param CartId $value
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