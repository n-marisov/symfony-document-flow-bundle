<?php

namespace Maris\Symfony\DocumentFlow\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Maris\Symfony\Address\Entity\Address;
use Maris\Symfony\Company\Entity\Business\Business;

/***
 * Сущность накладной.
 */
#[Entity]
#[Table(name: 'way_bills')]
class WayBill
{
    /**
     * Идентификатор.
     * @var int|null
     */
    #[Id,GeneratedValue]
    #[Column(options: ['unsigned'=>true])]
    private ?int $id = null;

    /**
     * Номер накладной
     * @var string
     */
    #[Column]
    private string $number;

    /***
     * Дата накладной
     * @var DateTimeImmutable
     */
    #[Column(type: 'date_immutable')]
    private DateTimeImmutable $date;

    /**
     * Грузоотправитель
     * @var Business
     */
    #[ManyToOne(targetEntity: Business::class,cascade: ['persist'])]
    #[JoinColumn( name:'consignee_id',  nullable: true)]
    private Business $shipper;

    /**
     * Грузополучатель
     * @var Business
     */
    #[ManyToOne(targetEntity: Business::class,cascade: ['persist'])]
    #[JoinColumn( name:'consignee_id',  nullable: true)]
    private Business $consignee;

    /***
     * Адрес доставки.
     * @var Address
     */
    #[ManyToOne(targetEntity: Address::class,cascade: ['persist'])]
    #[JoinColumn( name:'address_id',  nullable: true)]
    private Address $address;

    /***
     * Первичный документ которому принадлежит ТТН
     * @var PrimaryDocument
     */
    #[ManyToOne(targetEntity: PrimaryDocument::class,cascade: ['persist'])]
    #[JoinColumn( name:'document_id',  nullable: true)]
    private PrimaryDocument $document;

}