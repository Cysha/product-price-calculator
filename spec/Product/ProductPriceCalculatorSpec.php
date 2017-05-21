<?php

namespace spec\Cysha\ProductPriceCalculator\Product;

use Cysha\ProductPriceCalculator\Tax\TaxCollection;
use Cysha\ProductPriceCalculator\Model\Factory\MoneyFactory;
use Cysha\ProductPriceCalculator\Model\Percentage;
use Cysha\ProductPriceCalculator\Model\Saleable;
use Cysha\ProductPriceCalculator\Model\Taxable;
use Cysha\ProductPriceCalculator\Tax\TaxRate;
use Money\Money;
use PhpSpec\ObjectBehavior;

/**
 * Class ProductPriceCalculatorSpec
 *
 * @package spec\Product
 *
 * @Todo    Accept Taxes
 * @Todo    Accept Expressions
 * @Todo    Profit Margins
 * @Todo    ...
 */
class ProductPriceCalculatorSpec extends ObjectBehavior
{
    public function let(TaxCollection $taxes)
    {
    }

    /**
     * @param Saleable $product
     * @param TaxCollection $taxes
     */
    public function it_calculates_profit_margin_on_a_base_cost(Saleable $product, $taxes)
    {
        $costPrice = MoneyFactory::create(90, 'GBP'); // £90
        $profitMargin = Percentage::fromDecimal(0.1); // 10%
        $expectedSalePriceBeforeTax = MoneyFactory::create(99, 'GBP'); // £99
//        $finalSalePriceInclusiveOfTax = MoneyFactory::create()

        $this->createProduct($product, $costPrice, $profitMargin);

        $this->beConstructedWith($taxes);
        $actualSalesPriceInclusiveOfTax = $this->calculatePriceFromProduct($product);
        $actualSalesPriceInclusiveOfTax->shouldBeLike($expectedSalePriceBeforeTax);
    }

    public function it_handles_a_custom_tax_rate(Saleable $product)
    {
        $lithuanianVATRate = TaxRate::fromDecimal(0.21); // 21%
        $taxes = TaxCollection::make()->push($lithuanianVATRate); // [21%]
        $costPrice = MoneyFactory::create(90, 'GBP'); // £90
        $profitMargin = Percentage::fromDecimal(0.1); // 10%

        $this->createProduct($product, $costPrice, $profitMargin);

        $expectedSalesPrice = MoneyFactory::create(119.79, 'GBP'); // 90 + 9 (profit) = 99 + taxes (21%) = 119.79

        $this->beConstructedWith($taxes);
        $calculatedPrice = $this->calculatePriceFromProduct($product);
        $calculatedPrice->shouldBeLike($expectedSalesPrice);
    }

    /**
     * @param Saleable $product
     * @param $costPrice
     * @param $profitMargin
     */
    private function createProduct(Saleable $product, Money $costPrice, Percentage $profitMargin)
    {
        $product->cost()->willReturn($costPrice);
        $product->name()->willReturn('Nintendo 3DS');
        $product->profitMargin()->willReturn($profitMargin);
    }
}
