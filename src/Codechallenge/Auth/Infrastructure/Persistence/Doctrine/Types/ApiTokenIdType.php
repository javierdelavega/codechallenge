<?php

namespace App\Codechallenge\Auth\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use App\Codechallenge\Auth\Domain\Model\ApiTokenId;

/**
 * ApiTokenId Type object represents ApiTokenId Value object for the doctrine mapping system
 */
class ApiTokenIdType extends Type
{
  public const TYPE_NAME = 'api_token_id';

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
   * @return ApiTokenId
   */
  public function convertToPHPValue($value, AbstractPlatform $platform) : ApiTokenId
  {
    return new ApiTokenId($value);
  }

  /**
   * @see Type::convertToDatabaseValue()
   * @param ApiTokenId $value
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