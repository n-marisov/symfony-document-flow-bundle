<?php

namespace Maris\Symfony\DocumentFlow\Factory;

use Money\Currency;
use Money\Money;

/***
 * Фабрика для создания объектов валюты.
 */
class MoneyFactory
{
    protected Currency $currency;

    /**
     * @param string $currencyCode
     */
    public function __construct( string $currencyCode = "RUB")
    {
        $this->currency = new Currency( $currencyCode );
    }

    /***
     * Создает объект Money из атомарных
     * денежных единиц т.е. копейки, центы и т.п.
     * @param int $amount
     * @return Money
     */
    public function fromPenny( int $amount ):Money
    {
        return new Money( $amount, $this->currency );
    }

    /***
     * Создает объект Money из обычных
     * денежных единиц т.е. рубли, доллары, евро и т.п.
     * @param float $amount
     * @return Money
     */
    public function from( float $amount ):Money
    {
        return $this->fromPenny( $amount * 100 );
    }

}