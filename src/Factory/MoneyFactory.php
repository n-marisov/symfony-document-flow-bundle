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
     * @param Currency $currency
     */
    public function __construct( Currency $currency )
    {
        $this->currency = $currency;
    }

    /**
     * @param string $currencyCode
     */


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