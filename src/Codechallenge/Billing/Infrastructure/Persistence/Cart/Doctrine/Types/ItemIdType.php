<?php

namespace App\Codechallenge\Billing\Infrastructure\Persistence\Cart\Doctrine\Types;

use App\Codechallenge\Billing\Domain\Model\Cart\ItemId;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * ItemId Type object represents ItemId Value object for the doctrine mapping system
 */
class ItemIdType extends Type
{
  public const TYPE_NAME = "item_id";

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
   * @return ItemId
   */
  public function convertToPHPValue($value, AbstractPlatform $platform) : ItemId
  {
    return new ItemId($value);
  }

  /**
   * @see Type::convertToDatabaseValue()
   * @param ItemId $value
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