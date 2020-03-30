<?php


namespace minipoBundle\Entity;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class EnumEtatType extends Type
{
    const ENUM_ETAT= 'enumetat';
    const STATUS_ENCOURS = 'en cours';
    const STATUS_LIVREE = 'livree';
    const STATUS_NONLIVREE = 'non livree';
    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('en cours', 'livre')";
    }
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, array(self::STATUS_ENCOURS , self::STATUS_LIVREE, self::STATUS_NONLIVREE))) {
            throw new \InvalidArgumentException("Invalid status");
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::ENUM_ETAT;
    }
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}