<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Infrastructure\Persistence\Doctrine\Types;

use App\Codechallenge\Auth\Domain\Model\UserId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Uid\Uuid;

/**
 * UserId Type object represents UserId Value object for the doctrine mapping system.
 */
class UserIdType extends Type
{
    public const TYPE_NAME = 'user_id';

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
    public function convertToPHPValue($value, AbstractPlatform $platform): UserId
    {
        return new UserId(new Uuid($value));
    }

    /**
     * @see Type::convertToDatabaseValue()
     *
     * @param UserId $value
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
