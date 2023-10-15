<?php

namespace App\Codechallenge\Billing\Infrastructure\Persistence\Order\Doctrine\Types;

use App\Codechallenge\Billing\Domain\Model\Order\OrderLineId;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * OrderLineId Type object represents OrderLineId Value object for the doctrine mapping system
 */
class OrderLineIdType extends Type
{
  public const TYPE_NAME = "order_line_id";
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
   * @return OrderLineId
   */
  public function convertToPHPValue($value, AbstractPlatform $platform) : OrderLineId
  {
    return new OrderLineId($value);
  }

  /**
   * @see Type::convertToDatabaseValue()
   * @param OrderLineId $value
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