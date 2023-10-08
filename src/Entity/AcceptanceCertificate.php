<?php

namespace Maris\Symfony\DocumentFlow\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;

/***
 * Сущность акта выполненных работ.
 */
#[Entity]
class AcceptanceCertificate extends PrimaryDocument
{
    /***
     * Список оказанных услуг.
     * @var Collection<PaidService>
     */
    #[OneToMany(mappedBy: 'certificate', targetEntity: PaidService::class, cascade: ['persist'])]
    protected Collection $services;
}