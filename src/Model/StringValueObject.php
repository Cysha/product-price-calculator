<?php

namespace Cysha\ProductPriceCalculator\Model;

use Assert\AssertionFailedException;

abstract class StringValueObject implements Stringable, Equatable, ValueObject
{
    const NAME = 'default';

    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $value
     *
     * @throws AssertionFailedException
     */
    protected function __construct($value)
    {
        $this->validate($value);

        $this->value = $value;
    }

    // Model

    /**
     * @param string $value
     *
     * @return static
     */
    public static function fromString($value)
    {
        return new static($value);
    }

    /**
     * @return string
     */
    public function value()
    {
        return (string) $this->value;
    }

    /**
     * @param string $value
     */
    protected function validate($value)
    {
        Assert::notBlank($value, static::NAME . ' cannot be empty');
    }

    // Equatable

    /**
     * @param object $object
     *
     * @return bool
     */
    public function equals($object)
    {
        return get_class($object) === static::class && $this->value() === $object->value();
    }

    /**
     * @return string
     */
    public function toString()
    {
        return (string) $this->value();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
