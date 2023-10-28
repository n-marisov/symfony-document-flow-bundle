### Предикат для определения, что все сервисы имеют одинаковую валюту расчета.

```php
use Maris\Symfony\DocumentFlow\Predicate\CurrencyFullPredicate;
use Money\Currenc;
use Doctrine\Common\Collections\ArrayCollection;
use Maris\Symfony\DocumentFlow\Entity\PaidService;

$predicate = new CurrencyFullPredicate(new Currency("RUB"));

$services = new ArrayCollection([
    /**
     * Добавляем услуги.
     */
    new PaidService(),
    new PaidService(),
    new PaidService(),
]);

dump( $services->forAll( $predicate(...) ) ); //true


```