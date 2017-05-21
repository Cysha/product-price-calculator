<?php

namespace Cysha\ProductPriceCalculator\Model;

interface ValueObject
{
    /**
     * @return mixed
     */
    public function value();

    /**
     * @param string $value
     *
     * @return mixed
     */
    public static function fromString($value);
}
