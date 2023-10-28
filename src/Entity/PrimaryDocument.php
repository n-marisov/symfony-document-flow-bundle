<?php

namespace Maris\Symfony\DocumentFlow\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Maris\Symfony\Company\Entity\BankAccount;
use Maris\Symfony\Company\Entity\Business\Business;
use Maris\Symfony\DocumentFlow\Predicate\CurrencyFullPredicate;
use Maris\Symfony\DocumentFlow\Predicate\PaidServicesMoneyTotalPredicate;
use Money\Currency;
use Money\Money;
use RuntimeException;

/***
 * Любой первичный документ.
 * Акт выполненных работ или счет на оплату.
 */
#[Entity]
#[Table(name: 'primary_documents')]
class PrimaryDocument
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
    #[OneToMany(mappedBy: 'certificate', targetEntity: PaidService::class, cascade: ['persist'])]
    protected Collection $services;

    /**
     * Реквизиты исполнителя.
     * @var BankAccount
     */
    #[ManyToOne( targetEntity: BankAccount::class, cascade: ['persist']) ]
    private BankAccount $bankAccount;


    /**
     * Валюта в которой указаны деньги.
     * Необязательно хранить в БД.
     * Можно вычислить исходя из валюты услуги.
     * @var Currency|null
     */
    //#[Column(name: 'currency',type: 'currency')] ???
    protected ?Currency $currency = null;

    /**
     * @param Currency|null $currency
     */
    public function __construct( ?Currency $currency = null )
    {
        $this->currency = $currency;
    }


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
        if(!$services->forAll((new CurrencyFullPredicate($this->currency))(...)) )
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
        return $this->getServices()->reduce(
            (new PaidServicesMoneyTotalPredicate())(...),
            new Money(0,$this->currency)
        );
    }

}