<?php

namespace Maris\Symfony\DocumentFlow\Entity;

/**
 * Сущность платной услуги.
 */
class PaidService
{
    /**
     * Идентификатор.
     * @var int|null
     */
    private ?int $id = null;

    /**
     * Акт выполненных работ
     * @var AcceptanceCertificate|null
     */
    private ?AcceptanceCertificate $certificate = null;

    /**
     * Счет-фактура.
     * @var PaymentInvoice|null
     */
    private ?PaymentInvoice $invoice = null;

    /**
     * Основание для оплаты.
     * @var string
     */
    private string $basis;

    /***
     * Количество оказанных услуг.
     * @var positive-int
     */
    private int $quantity = 1;

    /***
     * Единица измерения
     * @var string
     */
    private string $unit;

    /***
     * Цена за 1 единицу.
     * @var float
     */
    private float $price;

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
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Устанавливает акт выполненных работ.
     * @param AcceptanceCertificate|null $certificate
     * @return $this
     */
    public function setCertificate(?AcceptanceCertificate $certificate): self
    {
        $this->certificate = $certificate;
        return $this;
    }

    /**
     * Устанавливает счет-фактуру.
     * @param PaymentInvoice|null $invoice
     * @return $this
     */
    public function setInvoice(?PaymentInvoice $invoice): self
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
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Возвращает акт выполненных работ.
     * @return AcceptanceCertificate|null
     */
    public function getCertificate(): ?AcceptanceCertificate
    {
        return $this->certificate;
    }

    /**
     * Возвращает счет-фактуру.
     * @return PaymentInvoice|null
     */
    public function getInvoice(): ?PaymentInvoice
    {
        return $this->invoice;
    }



    /***
     * Возвращает сумму за количество услуг.
     * @return float
     */
    public function getSum():float
    {
        return $this->getQuantity() * $this->getPrice();
    }
}