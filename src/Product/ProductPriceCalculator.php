<?php

namespace Cysha\ProductPriceCalculator\Product;

use Cysha\ProductPriceCalculator\Model\Factory\MoneyFactory;
use Cysha\ProductPriceCalculator\Model\Percentage;
use Cysha\ProductPriceCalculator\Model\Taxable;
use Money\Money;
use Cysha\ProductPriceCalculator\Tax\TaxCollection;
use Cysha\ProductPriceCalculator\Tax\TaxRate;

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
    public function calculatePriceFromProduct(Taxable $product)
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
