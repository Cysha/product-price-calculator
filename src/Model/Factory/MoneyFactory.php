<?php

namespace Model\Factory;

use InvalidArgumentException;
use Money\Currency;
use Money\Money;

final class MoneyFactory
{
    /**
     * @param float|string $amount
     * @param string       $currencyCode
     * @param string       $variableName
     *
     * @return Money
     * @throws InvalidArgumentException
     */
    public static function create($amount, $currencyCode, $variableName = 'money')
    {
        try {
            $formattedMoney = number_format((float) $amount, 2, '.', '');

            $sign      = "(?P<sign>[-\+])?";
            $digits    = "(?P<digits>\d*)";
            $separator = '(?P<separator>[.,])?';
            $decimals  = "(?P<decimal1>\d)?(?P<decimal2>\d)?";
            $pattern   = '/^' . $sign . $digits . $separator . $decimals . '$/';

            if (!preg_match($pattern, trim($formattedMoney), $matches)) {
                throw new InvalidArgumentException();
            }

            $units = $matches['sign'] === '-' ? '-' : '';
            $units .= $matches['digits'];
            $units .= isset($matches['decimal1']) ? $matches['decimal1'] : '0';
            $units .= isset($matches['decimal2']) ? $matches['decimal2'] : '0';

            if ($matches['sign'] === '-') {
                $units = '-' . ltrim(substr($units, 1), '0');
            } else {
                $units = ltrim($units, '0');
            }

            if ($units === '' || $units === '-') {
                $units = '0';
            }

            return new Money($units, new Currency($currencyCode));
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException(
                sprintf('The %s value \'%s\' is not a valid monetary value.', $variableName, $amount),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @param Money $money
     *
     * @return Money
     * @throws InvalidArgumentException
     */
    public static function round(Money $money)
    {
        return self::create(round($money->getAmount(), -2) / 100.0, $money->getCurrency()->getCode());
    }
}
