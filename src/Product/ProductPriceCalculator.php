<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 20/12/16
 * Time: 20:32
 */

namespace Product;

use Model\Factory\MoneyFactory;
use Model\Percentage;
use Money\Money;
use Tax\TaxCollection;
use Tax\TaxRate;

class ProductPriceCalculator
{
    /**
     * @var TaxCollection
     */
    private $taxes;

    /**
     * ProductPriceCalculator constructor.
     *
     * @param TaxCollection $taxes
     */
    public function __construct(TaxCollection $taxes = null)
    {
        $this->taxes = $taxes;
    }

    /**
     * @param Product $product
     *
     * @return Money
     */
    public function calculatePriceFromProduct(Product $product)
    {
        $profitPercentage = $product->profitMargin()->add(Percentage::oneHundredPercent());
        $priceWithoutVat  = $product->cost()->multiply($profitPercentage->value());
        $price            = $priceWithoutVat;

        if (null === $this->taxes || $this->taxes->isEmpty()) {
            return $price;
        }

        $this->taxes->each(function (TaxRate $taxRate) use (&$price) {
            $price = $price->add($price->multiply($taxRate->multiply(Percentage::oneHundredPercent())->value()));
        });

        return $price;
    }
}
