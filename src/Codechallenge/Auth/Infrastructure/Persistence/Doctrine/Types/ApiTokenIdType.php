<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Infrastructure\Persistence\Doctrine\Types;

use App\Codechallenge\Auth\Domain\Model\ApiTokenId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Uid\Uuid;

/**
 * ApiTokenId Type object represents ApiTokenId Value object for the doctrine mapping system.
 */
class ApiTokenIdType extends Type
{
    public const TYPE_NAME = 'api_token_id';

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
    public function convertToPHPValue($value, AbstractPlatform $platform): ApiTokenId
    {
        return new ApiTokenId(new Uuid($value));
    }

    /**
     * @see Type::convertToDatabaseValue()
     *
     * @param ApiTokenId $value
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
