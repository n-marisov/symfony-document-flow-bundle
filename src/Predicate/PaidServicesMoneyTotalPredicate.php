<?php

namespace Maris\Symfony\DocumentFlow\Predicate;

use Maris\Symfony\DocumentFlow\Entity\PaidService;
use Money\Money;

/***
 * Предикат для подсчета суммы по накладной.
 */
class PaidServicesMoneyTotalPredicate
{
    public function __invoke( Money $accumulator , PaidService $item ):Money
    {
        return $accumulator->add( $item->getTotal() );
    }
}