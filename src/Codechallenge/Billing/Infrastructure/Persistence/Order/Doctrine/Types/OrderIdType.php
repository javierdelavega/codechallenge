<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Infrastructure\Persistence\Order\Doctrine\Types;

use App\Codechallenge\Billing\Domain\Model\Order\OrderId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Uid\Uuid;

/**
 * OrderId Type object represents OrderId Value object for the doctrine mapping system.
 */
class OrderIdType extends Type
{
    public const TYPE_NAME = 'order_id';

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
    public function convertToPHPValue($value, AbstractPlatform $platform): OrderId
    {
        return new OrderId(new Uuid($value));
    }

    /**
     * @see Type::convertToDatabaseValue()
     *
     * @param OrderId $value
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
