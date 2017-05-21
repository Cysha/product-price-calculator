<?php

namespace Cysha\ProductPriceCalculator\Model;


use Money\Money;

interface Taxable
{

    /**
     * @return Money
     */
    public function cost(): Money;

    /**
     * @return Percentage
     */
    public function profitMargin (): Percentage;
}