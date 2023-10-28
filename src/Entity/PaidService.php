<?php

namespace Maris\Symfony\DocumentFlow\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Money\Currency;
use Money\Money;

/**
 * Сущность платной услуги.
 */
#[Entity]
#[Table(name: 'paid_services')]
class PaidService
{
    /**
     * Идентификатор.
     * @var int|null
     */
    #[Id,GeneratedValue]
    #[Column(options: ['unsigned'=>true])]
    private ?int $id = null;

    /**
     * Акт выполненных работ
     * @var PrimaryDocument|null
     */
    #[ManyToOne(targetEntity: PrimaryDocument::class,cascade: ['persist'])]
    #[JoinColumn(name: 'certificate_id')]
    private ?PrimaryDocument $certificate = null;

    /**
     * Основание для оплаты.
     * @var string
     */
    #[Column(name: 'basis', type: 'text', nullable: true)]
    private string $basis;

    /***
     * Количество оказанных услуг.
     * @var positive-int
     */
    #[Column(name: 'quantity',type: 'smallint')]
    private int $quantity = 1;

    /***
     * Единица измерения
     * @var string
     */
    #[Column(name: 'unit')]
    private string $unit;

    /***
     * Цена за 1 единицу.
     * @var Money
     */
    #[Embedded(class: Money::class)]
    private Money $price;

    /**
     * Устанавливает основание для оплаты.
     * @param string $basis
     * @return $this
     */
    public function setBasis(string $basis): self
    {
        $this->basis = $basis;
        return $this;
    }

    /**
     * Устанавливает количество услуг.
     * @param int $quantity
     * @return $this
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Устанавливает единицу измерения.
     * @param string $unit
     * @return $this
     */
    public function setUnit(string $unit): self
    {
        $this->unit = $unit;
        return $this;
    }

    /**
     * Устанавливает цену за одну услугу.
     * @param Money $price
     * @return $this
     */
    public function setPrice( Money $price ): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Устанавливает акт выполненных работ.
     * @param PrimaryDocument|null $certificate
     * @return $this
     */
    public function setCertificate(?PrimaryDocument $certificate): self
    {
        $this->certificate = $certificate;
        return $this;
    }

    /**
     * Устанавливает счет-фактуру.
     * @param PrimaryDocument|null $invoice
     * @return $this
     */
    public function setInvoice(?PrimaryDocument $invoice): self
    {
        $this->invoice = $invoice;
        return $this;
    }



    /**
     * Возвращает идентификатор.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Возвращает основание для оплаты.
     * @return string
     */
    public function getBasis(): string
    {
        return $this->basis;
    }

    /**
     * Возвращает количество оказанных услуг.
     * @return positive-int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Возвращает название услуги.
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * Возвращает цену за одну услугу.
     * @return Money
     */
    public function getPrice(): Money
    {
        return $this->price;
    }

    /**
     * Возвращает акт выполненных работ.
     * @return PrimaryDocument|null
     */
    public function getCertificate(): ?PrimaryDocument
    {
        return $this->certificate;
    }

    /**
     * Возвращает счет-фактуру.
     * @return PrimaryDocument|null
     */
    public function getInvoice(): ?PrimaryDocument
    {
        return $this->invoice;
    }



    /***
     * Возвращает сумму за количество услуг.
     * @return Money
     */
    public function getTotal():Money
    {
        return   $this->getPrice()->multiply( $this->getQuantity() );
    }

    /**
     * Возвращает валюту в которой оплачивается услуга.
     * @return Currency
     */
    public function getCurrency():Currency
    {
        return $this->price->getCurrency();
    }
}