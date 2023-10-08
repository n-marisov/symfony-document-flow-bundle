<?php

namespace Maris\Symfony\DocumentFlow\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Maris\Symfony\Company\Entity\Business;
use Maris\Symfony\DocumentFlow\Predicate\CurrencyFullPredicate;
use Money\Currency;
use Money\Money;
use RuntimeException;

/***
 * Любой первичный документ.
 */
#[Entity]
#[Table(name: 'primary_documents')]
#[InheritanceType('SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'document_type',type: 'integer')]
#[DiscriminatorMap([ PrimaryDocument::class ,AcceptanceCertificate::class, PaymentInvoice::class ])]
abstract class PrimaryDocument
{
    /***
     * Идентификатор.
     * @var int|null
     */
    #[Id,GeneratedValue]
    #[Column(options: ['unsigned'=>true])]
    protected ?int $id = null;

    /**
     * Заказчик
     * @var Business
     */
    #[ManyToOne(targetEntity: Business::class,cascade: ['persist'])]
    #[JoinColumn(name: 'employer_id',nullable: false)]
    protected Business $employer;

    /**
     * Исполнитель
     * @var Business
     */
    #[ManyToOne(targetEntity: Business::class,cascade: ['persist'])]
    #[JoinColumn(name: 'executor_id',nullable: false)]
    protected Business $executor;

    /**
     * Основание к работам (например договор).
     * @var string|null
     */
    #[Column(name: 'basis', type: 'string', nullable: true)]
    protected ?string $basis = null;

    /**
     * Номер акта.
     * @var string
     */
    #[Column(name: 'number')]
    protected string $number;

    /**
     * Дата акта.
     * @var DateTimeImmutable
     */
    #[Column(name: 'date',type: 'date_immutable')]
    protected DateTimeImmutable $date;

    /***
     * Список оказанных услуг.
     * @var Collection<PaidService>
     */
    protected Collection $services;


    /**
     * Валюта в которой указаны деньги.
     * Необязательно хранить в БД.
     * @var Currency|null
     */
    //#[Column(name: 'currency',type: 'currency')] ???
    protected ?Currency $currency = null;


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Business
     */
    public function getEmployer(): Business
    {
        return $this->employer;
    }

    /**
     * @param Business $employer
     * @return $this
     */
    public function setEmployer(Business $employer): self
    {
        $this->employer = $employer;
        return $this;
    }

    /**
     * @return Business
     */
    public function getExecutor(): Business
    {
        return $this->executor;
    }

    /**
     * @param Business $executor
     * @return $this
     */
    public function setExecutor(Business $executor): self
    {
        $this->executor = $executor;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBasis(): ?string
    {
        return $this->basis;
    }

    /**
     * @param string|null $basis
     * @return $this
     */
    public function setBasis(?string $basis): self
    {
        $this->basis = $basis;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return $this
     */
    public function setNumber(string $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param DateTimeImmutable $date
     * @return $this
     */
    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    /**
     * @param Collection<int,PaidService> $services
     * @return $this
     */
    public function setServices(Collection $services): self
    {
        if(!$services->forAll((new CurrencyFullPredicate($services->first()->getCurrency()))(...)) )
            throw new RuntimeException("Первичный документ не может иметь услуги с разными валютами.");

        $this->services = $services;
        return $this;
    }

    /**
     * Возвращает суммы всех услуг по документу
     * @return Money
     */
    public function getTotal():Money
    {
        return $this->getServices()
            ->reduce( fn( PaidService $accumulator , PaidService $item) => $accumulator->getTotal()->add( $item->getTotal() ), new Money(0,$this->currency));
    }

}