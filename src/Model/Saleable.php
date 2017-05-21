<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 21/05/17
 * Time: 00:38
 */

namespace Cysha\ProductPriceCalculator\Model;


interface Saleable extends Taxable
{
    public function name();
}