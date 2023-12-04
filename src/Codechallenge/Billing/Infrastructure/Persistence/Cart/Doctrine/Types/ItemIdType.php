<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Infrastructure\Persistence\Cart\Doctrine\Types;

use App\Codechallenge\Billing\Domain\Model\Cart\ItemId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Uid\Uuid;

/**
 * ItemId Type object represents ItemId Value object for the doctrine mapping system.
 */
class ItemIdType extends Type
{
    public const TYPE_NAME = 'item_id';

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
    public function convertToPHPValue($value, AbstractPlatform $platform): ItemId
    {
        return new ItemId(new Uuid($value));
    }

    /**
     * @see Type::convertToDatabaseValue()
     *
     * @param ItemId $value
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
