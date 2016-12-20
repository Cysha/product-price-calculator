<?php

namespace spec\Product;

use Model\Factory\MoneyFactory;
use Model\Percentage;
use Product\Product;
use PhpSpec\ObjectBehavior;
use Tax\TaxCollection;
use Tax\TaxRate;

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
     * @param Product       $product
     * @param TaxCollection $taxes
     */
    public function it_calculates_profit_margin_on_a_base_cost(Product $product, $taxes)
    {
        $costPrice    = MoneyFactory::create(90, 'GBP');
        $profitMargin = Percentage::fromDecimal(0.1);
        $profit       = $profitMargin->add(Percentage::oneHundredPercent());
        $expectedCost = $costPrice->add($costPrice->multiply($profit->value()));

        $product->cost()->willReturn($costPrice);
        $product->name()->willReturn('Nintendo 3DS');
        $product->profitMargin()->willReturn($profitMargin);

        $this->beConstructedWith($taxes, $profit);
        $this->calculatePriceFromProduct($product)->shouldBeLike($expectedCost);
    }

    public function it_handles_a_custom_tax_rate(Product $product)
    {
        $lithuanianVATRate = TaxRate::fromDecimal(0.21);
        $taxes             = TaxCollection::make()->push($lithuanianVATRate);
        $costPrice         = MoneyFactory::create(90, 'GBP');
        $profitMargin      = Percentage::fromDecimal(0.1);

        $profit          = $profitMargin->add(Percentage::oneHundredPercent());
        $priceWithoutVat = $costPrice->add($costPrice->multiply($profit->value()));
        $price           = $priceWithoutVat;

        $taxes->each(function (TaxRate $taxRate) use (&$price) {
            $price = $price->add($price->multiply($taxRate->multiply(Percentage::oneHundredPercent())->value()));
        });

        $product->cost()->willReturn($costPrice);
        $product->name()->willReturn('Nintendo 3DS');
        $product->profitMargin()->willReturn($profitMargin);

        $this->beConstructedWith($taxes);
        $this->calculatepriceFromProduct($product)->shouldBeLike($price);
    }
}
