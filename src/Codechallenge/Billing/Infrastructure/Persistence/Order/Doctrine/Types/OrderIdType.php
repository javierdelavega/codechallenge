<?php

namespace App\Codechallenge\Billing\Infrastructure\Persistence\Order\Doctrine\Types;

use App\Codechallenge\Billing\Domain\Model\Order\OrderId;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * OrderId Type object represents OrderId Value object for the doctrine mapping system
 */
class OrderIdType extends Type
{
  public const TYPE_NAME = "order_id";
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
   * @return OrderId
   */
  public function convertToPHPValue($value, AbstractPlatform $platform) : OrderId
  {
    return new OrderId($value);
  }

  /**
   * @see Type::convertToDatabaseValue()
   * @param OrderId $value
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