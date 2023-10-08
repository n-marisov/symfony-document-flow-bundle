<?php

namespace Maris\Symfony\DocumentFlow\Predicate;

use Maris\Symfony\DocumentFlow\Entity\PaidService;
use Money\Currency;

/**
 * Предикат для определения,
 * что все элементы коллекции
 * имеют одинаковую валюту,
 * переданную в конструктор.
 */
class CurrencyFullPredicate
{
    protected Currency $firstCurrency;

    /**
     * @param Currency $firstCurrency
     */
    public function __construct( Currency $firstCurrency )
    {
        $this->firstCurrency = $firstCurrency;
    }

    public function __invoke( int $position, PaidService $service ):bool
    {
        return $this->firstCurrency->equals( $service->getCurrency() );
    }

}