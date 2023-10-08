<?php

namespace Maris\Symfony\DocumentFlow\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Сущность счета на оплату.
 */
#[Entity]
class PaymentInvoice extends PrimaryDocument
{
    /***
     * Список оказанных услуг.
     * @var Collection<PaidService>
     */
    #[OneToMany(mappedBy: 'invoice', targetEntity: PaidService::class, cascade: ['persist'])]
    protected Collection $services;
}