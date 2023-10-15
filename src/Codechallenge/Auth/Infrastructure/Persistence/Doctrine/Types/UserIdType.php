<?php

namespace App\Codechallenge\Auth\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use App\Codechallenge\Auth\Domain\Model\UserId;

/**
 * UserId Type object represents UserId Value object for the doctrine mapping system
 */
class UserIdType extends Type
{
  public const TYPE_NAME = 'user_id';
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
   * @return UserId
   */
  public function convertToPHPValue($value, AbstractPlatform $platform)
  {
    return new UserId($value);
  }

  /**
   * @see Type::convertToDatabaseValue()
   * @param UserId $value
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