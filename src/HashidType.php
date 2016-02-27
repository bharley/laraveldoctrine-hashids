<?php

namespace Blake\Dbal\Types;

use Doctrine\DBAL\Exception\InvalidArgumentException as DbalInvalidArgumentException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;
use Hashids;
use InvalidArgumentException;

class HashidType extends IntegerType
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'hashid';
    }

    /**
     * @param int              $value
     * @param AbstractPlatform $platform
     *
     * @return null|string
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return (null === $value) ? null : $this->getHashidsConnection()->encode($value);
    }

    /**
     * @param string           $value
     * @param AbstractPlatform $platform
     *
     * @return null|string
     *
     * @throws DbalInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value !== null) {
            $value = $this->getHashidsConnection()->decode($value);
            if (is_array($value)) {
                $value = array_shift($value);
            }
        }

        return $value;
    }

    /**
     * @return \Hashids\Hashids An instance of Hashids
     */
    protected function getHashidsConnection()
    {
        try {
            $connection = Hashids::connection('doctrine');
        } catch (InvalidArgumentException $e) {
            $connection = Hashids::connection();
        }

        return $connection;
    }
}

