# Product Price Calculator
PHP 5.6+ library to make working with products and price calculation, easier, and fun!


``` php
<?php

use Model\Factory\MoneyFactory;
use Model\Percentage;
use Product\Product;
use Product\ProductPriceCalculator;
use Tax\TaxCollection;
use Tax\TaxRate;

$calculator = new ProductPriceCalculator(TaxCollection::make([
    TaxRate::fromPercentage(20)
]));

$product = Product::create(
    'Item Name',
    MoneyFactory::create(50, 'GBP'),
    Percentage::fromDecimal(0.5)
);

$calculatedAmount = $calculator->calculatePriceFromProduct($product);
```


## Install

Via Composer

``` bash
$ composer require cysha/product-price-calculator
```


## Features
    - Todo 

## Documentation
    - Todo 


## Testing

We try to follow BDD and TDD, as such we use both [phpspec](http://www.phpspec.net) and [phpunit](https://phpunit.de) to test this library.

``` bash
$ composer test
```


## Contributing
    - Todo 



## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.


## Acknowledgements
    - Todo 
