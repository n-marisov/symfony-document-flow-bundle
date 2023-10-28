### Предикат для подсчета суммы по накладной.


```php
use Maris\Symfony\DocumentFlow\Predicate\PaidServicesMoneyTotalPredicate;
use Money\Currenc;
use Doctrine\Common\Collections\ArrayCollection;
use Maris\Symfony\DocumentFlow\Entity\PaidService;

$predicate = new PaidServicesMoneyTotalPredicate();

$services = new ArrayCollection([
    /**
     * Добавляем услуги.
     */
    new PaidService(),
    new PaidService(),
    new PaidService(),
]);

dump( $services->reduce( $predicate(...) ) ); //Сумма денег за все услуги.


```