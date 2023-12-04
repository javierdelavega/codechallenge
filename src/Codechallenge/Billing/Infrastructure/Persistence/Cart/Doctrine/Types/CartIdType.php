<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Infrastructure\Persistence\Cart\Doctrine\Types;

use App\Codechallenge\Billing\Domain\Model\Cart\CartId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Uid\Uuid;

/**
 * CartId Type object represents CartId Value object for the doctrine mapping system.
 */
class CartIdType extends Type
{
    public const TYPE_NAME = 'cart_id';

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
    public function convertToPHPValue($value, AbstractPlatform $platform): CartId
    {
        return new CartId(new Uuid($value));
    }

    /**
     * @see Type::convertToDatabaseValue()
     *
     * @param CartId $value
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
