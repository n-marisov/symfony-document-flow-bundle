<?php

namespace Maris\Symfony\DocumentFlow\Formatter;

use alhimik1986\PhpExcelTemplator\params\CallbackParam;
use alhimik1986\PhpExcelTemplator\params\ExcelParam;
use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValue;
use Doctrine\Common\Collections\Collection;
use Maris\Symfony\DocumentFlow\Entity\PaidService;
use Maris\Symfony\DocumentFlow\Entity\PrimaryDocument;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelFormatter2
{
    /***
     * Шаблон для акта выполненных работ счета фактуры и т.п. .
     * @var Spreadsheet
     */
    protected readonly Spreadsheet $parent;

    protected readonly BusinessFormatter $businessFormatter;

    /**
     * Форматировщик дат.
     * @var DateFormatter
     */
    private readonly DateFormatter $dateFormatter;

    /***
     * Форматировщик денежных единиц.
     * @var MoneyFormatter
     */
    private readonly MoneyFormatter $moneyFormatter;

    /**
     * @param Spreadsheet $parent
     */
    public function __construct(Spreadsheet $parent)
    {
        $this->parent = $parent;
        $this->businessFormatter = new BusinessFormatter();
        $this->dateFormatter = new DateFormatter();
        $this->moneyFormatter = new MoneyFormatter();
    }


    public function format( PrimaryDocument $document  ):Spreadsheet
    {
        $spreadsheet = $this->parent->copy();

        foreach ($spreadsheet->getAllSheets() as $worksheet )
            PhpExcelTemplator::renderWorksheet( $worksheet, $worksheet->toArray(), [

                "{number}" => $document->getNumber(),
                "{employer}" => $this->businessFormatter->format($document->getEmployer()),
                "{executor}" => $this->businessFormatter->format($document->getExecutor()),
                "{basis}" => $document->getBasis(),
                "{services.count}" => $document->getServices()->count(),
                "{date.day}" => $this->dateFormatter->formatDay( $document->getDate() ),
                "{date.month}" => $this->dateFormatter->formatMonth( $document->getDate() ),
                "{date.year}" => $this->dateFormatter->formatYear( $document->getDate() ),
                "{document.total}" => $this->moneyFormatter->formatValue( $document->getTotal() ),
                "{document.total.string}" => $this->moneyFormatter->formatString($document->getTotal(),true),
                /*"[service.position]" => range(1, $document->getServices()->count()),
                "[service.basis]" => $document->getServices()->map(function ( PaidService $service ){
                    return $service->getBasis();
                })->toArray(),
                "[service.quantity]" => $document->getServices()->map(function ( PaidService $service ){
                    return $service->getQuantity();
                })->toArray(),
                "[service.unit]" => $document->getServices()->map(function ( PaidService $service ){
                    return $service->getUnit();
                })->toArray(),
                "[service.price]" => $document->getServices()->map(function ( PaidService $service ){
                    return  $this->moneyFormatter->formatValue( $service->getPrice() );
                })->toArray(),
                "[service.total]" => $document->getServices()->map(function ( PaidService $service ){
                    return $this->moneyFormatter->formatValue( $service->getTotal() );
                })->toArray()*/


                /*"[service.position]" => $this->createServiceParam($document->getServices(),function () use ($document){
                    return range(1, $document->getServices()->count());
                }),*/
                "[service.position]" => new ExcelParam(CellSetterArrayValue::class,range(1, $document->getServices()->count())),
                "[service.basis]" => $this->createServiceParam($document->getServices(),function ( PaidService $service ){
                    return $service->getBasis();
                }),
                "[service.quantity]" => $this->createServiceParam($document->getServices(),function ( PaidService $service ){
                    return $service->getQuantity();
                }),
                "[service.unit]" => $this->createServiceParam($document->getServices(),function ( PaidService $service ){
                    return $service->getUnit();
                }),
                "[service.price]" => $this->createServiceParam($document->getServices(),function ( PaidService $service ){
                    return  $this->moneyFormatter->formatValue( $service->getPrice() );
                }),
                "[service.total]" => $this->createServiceParam($document->getServices(),function ( PaidService $service ){
                    return $this->moneyFormatter->formatValue( $service->getTotal() );
                })
            ], [
               /* "[service.position]" => function( CallbackParam $param ) use ($document)
                {
                    $sheet = $param->sheet;
                    $row = $sheet->getCell($param->coordinate)->getRow();
                    $sheet->insertNewRowBefore( $row , $document->getServices()->count() );
                    dump ($param->coordinate);
                }*/
            ],
            [
                # Событие срабатывает перед
               /* PhpExcelTemplator::BEFORE_INSERT_PARAMS => function(Worksheet $worksheet, array $vars) use ($document){
                    foreach ( $worksheet->getRowIterator() as $index => $row ){
                        foreach ($row->getCellIterator() as $cell){
                            if($cell->getValue() === "[service.position]"){
                                $worksheet->insertNewRowBefore($index + 1,$document->getServices()->count() );
                            }
                        }
                    }
                }*/
            ]);

        return $spreadsheet;
    }


   protected function createServiceParam( Collection $collection , callable $mapCall ):ExcelParam
   {
       return new ExcelParam(CellSetterArrayValue::class, $collection->map($mapCall)->toArray());
   }


}