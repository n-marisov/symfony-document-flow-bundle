<?php

namespace Maris\Symfony\DocumentFlow\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Maris\Symfony\Company\Entity\Business;

/***
 * Любой первичный документ.
 */
abstract class PrimaryDocument
{
    /***
     * Идентификатор.
     * @var int|null
     */
    private ?int $id = null;

    /**
     * Заказчик
     * @var Business
     */
    private Business $employer;

    /**
     * Исполнитель
     * @var Business
     */
    private Business $executor;

    /**
     * Основание к работам (например договор).
     * @var string|null
     */
    private ?string $basis = null;

    /**
     * Номер акта.
     * @var string
     */
    private string $number;

    /**
     * Дата акта.
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $date;

    /***
     * Список оказанных услуг.
     * @var Collection<PaidService>
     */
    private Collection $services;

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
     * @param Collection $services
     * @return $this
     */
    public function setServices(Collection $services): self
    {
        $this->services = $services;
        return $this;
    }


}