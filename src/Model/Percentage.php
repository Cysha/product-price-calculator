<?php

namespace Model;

use Assert\Assertion;
use Assert\AssertionFailedException;

/** @noinspection MissingClassSpec */
class Percentage extends StringValueObject
{
    /**
     * @param float $value
     *
     * @throws AssertionFailedException
     */
    protected function __construct($value)
    {
        $this->validate($value);

        $this->value = (float) $value;
    }

    /**
     * @param bool $asFloat
     *
     * @return float
     */
    public function value($asFloat = false)
    {
        return $asFloat ? (float) $this->value : sprintf('%.8f', $this->value);
    }

    /**
     * @return float
     */
    public function inverseValue()
    {
        return 1.0 - $this->value();
    }

    /**
     * @return Percentage
     */
    public function inversePercentage()
    {
        return static::fromDecimal($this->inverseValue());
    }

    /**
     * @param Percentage $other
     *
     * @return int
     */
    public function compare(Percentage $other)
    {
        if ($this->value() < $other->value()) {
            return -1;
        } elseif ($this->value() === $other->value()) {
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * @param Percentage $other
     *
     * @return bool
     */
    public function greaterThan(Percentage $other)
    {
        return 1 === $this->compare($other);
    }

    /**
     * @param Percentage $other
     *
     * @return bool
     */
    public function greaterThanOrEqual(Percentage $other)
    {
        return 0 <= $this->compare($other);
    }

    /**
     * @param Percentage $other
     *
     * @return bool
     */
    public function lessThan(Percentage $other)
    {
        return -1 === $this->compare($other);
    }

    /**
     * @param Percentage $other
     *
     * @return bool
     */
    public function lessThanOrEqual(Percentage $other)
    {
        return 0 >= $this->compare($other);
    }

    /**
     * @param Percentage $addend
     *
     * @return Percentage
     */
    public function add(Percentage $addend)
    {
        return new static($this->value() + $addend->value());
    }

    /**
     * @param Percentage $subtrahend
     *
     * @return Percentage
     */
    public function subtract(Percentage $subtrahend)
    {
        return new static($this->value() - $subtrahend->value());
    }

    /**
     * @param Percentage $multiplier
     *
     * @return Percentage
     */
    public function multiply(Percentage $multiplier)
    {
        return new static($this->value() * $multiplier->value());
    }

    /**
     * @param Percentage $divisor
     *
     * @return Percentage
     */
    public function divide(Percentage $divisor)
    {
        return new static($this->value() / $divisor->value());
    }

    /**
     * @return bool
     */
    public function isPositive()
    {
        return $this->value() > 0;
    }

    /**
     * @return bool
     */
    public function isNegative()
    {
        return $this->value() < 0;
    }

    /**
     * @return bool
     */
    public function isZero()
    {
        return 0.0 === $this->value();
    }

    /**
     * @return Percentage
     */
    public function negate()
    {
        return new static(-$this->value());
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->value() * 100.0 . '%';
    }

    /**
     * @param float $percentage
     *
     * @return Percentage
     */
    public static function fromPercentage($percentage)
    {
        return new static($percentage / 100.0);
    }

    /**
     * @param float $fraction
     *
     * @return Percentage
     */
    public static function fromDecimal($fraction)
    {
        return new static($fraction);
    }

    /**
     * @param string $value
     *
     * @return Percentage
     */
    public static function fromString($value)
    {
        return new static((float) $value);
    }

    /**
     * @return Percentage
     */
    public static function zeroPercent()
    {
        return new static(0.0);
    }

    /**
     * @return Percentage
     */
    public static function oneHundredPercent()
    {
        return new static(1.0);
    }

    /**
     * @param float $value
     *
     * @throws AssertionFailedException
     */
    protected function validate($value)
    {
        Assertion::numeric($value, sprintf('Percentage "%s" must be a number.', $value));
    }
}
