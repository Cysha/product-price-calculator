<?php
namespace Product;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Model\Percentage;
use Money\Money;

class Product
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Money
     */
    private $cost;

    /**
     * @var Percentage
     */
    private $profitMargin;

    /**
     * Product constructor.
     *
     * @param string     $name
     * @param Money      $cost
     * @param Percentage $profitMargin
     *
     * @throws AssertionFailedException
     */
    public function __construct($name, Money $cost, Percentage $profitMargin)
    {
        $this->validate($name, $cost, $profitMargin);

        $this->name         = $name;
        $this->cost         = $cost;
        $this->profitMargin = $profitMargin;
    }

    /**
     * @param string     $name
     * @param Money      $purchasePrice
     * @param Percentage $profitMargin
     *
     * @return Product
     * @throws AssertionFailedException
     */
    public static function create($name, Money $purchasePrice, Percentage $profitMargin)
    {
        return new self($name, $purchasePrice, $profitMargin);
    }

    /**
     * @param string     $name
     * @param Money      $cost
     * @param Percentage $profitMargin
     *
     * @throws AssertionFailedException
     */
    private function validate($name, Money $cost, Percentage $profitMargin)
    {
        Assertion::false($profitMargin->isNegative(), 'Profit margin cannot be negative');
        Assertion::notBlank($name, 'Product name cannot be blank');
        Assertion::false($cost->isNegative(), 'Product cost price cannot be negative');
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return Money
     */
    public function cost()
    {
        return $this->cost;
    }

    /**
     * @return Percentage
     */
    public function profitMargin()
    {
        return $this->profitMargin;
    }
}
