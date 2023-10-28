<?php

namespace Maris\Symfony\DocumentFlow\Formatter;

use Maris\Symfony\DocumentFlow\Entity\PaidService;
use Maris\Symfony\DocumentFlow\Entity\PrimaryDocument;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/***
 * Форматирует как документ Exel.
 * В документе Exel должно быть два листа Акт и Счет.
 */
class ExcelFormatter
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
    public function __construct( Spreadsheet $parent )
    {
        $this->parent = $parent;
        $this->businessFormatter = new BusinessFormatter();
        $this->dateFormatter = new DateFormatter();
        $this->moneyFormatter = new MoneyFormatter();
    }


    /**
     * @throws Exception
     */
    public function format( PrimaryDocument $document ):Spreadsheet
    {
        $spreadsheet = $this->parent->copy();
        # Наполняем простыми значениями лист.
        foreach ( $spreadsheet->getAllSheets() as $worksheet ) {
            foreach ( $worksheet->getRowIterator() as $rowIndex => $row ) {
                foreach ( $row->getColumnIterator() as $cell ) {
                    $this->replaceScalarPlaceholder($cell,$document);
                }
            }
        }

        # Создаем таблицу с услугами.
        foreach ( $spreadsheet->getAllSheets() as $worksheet )
        {
            $excludeRowIndexes = [];
            foreach ( $worksheet->getRowIterator() as $rowIndex => $row )
            {
                if(in_array($rowIndex,$excludeRowIndexes))
                    continue;
                foreach ( $row->getColumnIterator() as $cell )
                {
                    $value = $cell->getValue();
                    if(is_string($value) && str_contains($value, "{service::position}")){
                        $worksheet->insertNewRowBefore($row->getRowIndex()+1, $document->getServices()->count() );

                        $lineMerge = array_filter($worksheet->getMergeCells(),function( $v ) use($row,&$excludeRowIndexes, $document) {
                                [$start, $end] = explode(":", $v);
                                $rowIndexStart = (int) filter_var($start,FILTER_SANITIZE_NUMBER_INT );
                                $rowIndexEnd = (int) filter_var($end,FILTER_SANITIZE_NUMBER_INT );
                                $excludeRowIndexes = range( $rowIndexEnd + 1, $document->getServices()->count() +1,1 );
                                return $rowIndexStart == $row->getRowIndex();
                        });

                        $lineMergeFull = [];
                        for ($i = 0; $i <= $document->getServices()->count(); $i++){
                            $index = $row->getRowIndex() + $i;
                            $lineMergeFull = array_merge($lineMergeFull, array_values(str_replace( $row->getRowIndex(), $index + 1, $lineMerge )));
                        }
                        foreach ($lineMergeFull as $item)
                            $worksheet->mergeCells($item);
                    }
                }
            }
        }


        # Наполняем лист данными
        foreach ( $spreadsheet->getAllSheets() as $worksheet )
            foreach ( $worksheet->getRowIterator() as $rowIndex => $row )
                foreach ( $row->getColumnIterator() as $cell )
                {
                    $value = $cell->getValue();
                    $coordinate = $cell->getCoordinate();
                    $line = $cell->getRow();
                    if(!is_string($value)) continue;
                    if( str_contains($value, "{service::position}") ){
                        foreach ($document->getServices() as $key => $service){
                            $c = str_replace($line,$line+$key+1,$coordinate);
                           // $worksheet->getRowDimension($line+$key+1)->setRowHeight(-1);
                            $worksheet->getCell($c)->setValue($key+1);
                        }
                    }
                    if(str_contains($value, "{{service::basis}}")){
                        foreach ($document->getServices() as $key => $service){
                            $c = str_replace($line,$line+$key+1,$coordinate);
                         //   $worksheet->getRowDimension($line+$key+1)->setRowHeight(-1);
                            $worksheet->getCell($c)->setValue($service->getBasis());
                        }
                    }

                    if(str_contains($value, "{{service::quantity}}")){
                        foreach ($document->getServices() as $key => $service){
                            $c = str_replace($line,$line+$key+1,$coordinate);
                        //   $worksheet->getRowDimension($line+$key+1)->setRowHeight(-1);
                            $worksheet->getCell($c)->setValue($service->getQuantity());
                        }
                    }

                    if(str_contains($value, "{{service::unit}}")){
                        foreach ($document->getServices() as $key => $service){
                            $c = str_replace($line,$line+$key+1,$coordinate);
                           // $worksheet->getRowDimension($line+$key+1)->setRowHeight(-1);
                            $worksheet->getCell($c)->setValue($service->getUnit());
                        }
                    }

                    if(str_contains($value, "{{service::price}}")){
                        foreach ($document->getServices() as $key => $service){
                            $c = str_replace($line,$line+$key+1,$coordinate);
                            //$worksheet->getRowDimension($line+$key+1)->setRowHeight(-1);
                            $worksheet->getCell($c)->setValue($service->getPrice()->getAmount() / 100);
                        }
                    }

                    if(str_contains($value, "{{service::total}}")){
                        /***@var PaidService $service **/
                        foreach ($document->getServices() as $key => $service){
                            $c = str_replace($line,$line+$key+1,$coordinate);
                            //$worksheet->getRowDimension($line+$key+1)->setRowHeight(-1);
                            $worksheet->getCell($c)->setValue($service->getTotal()->getAmount() / 100);
                        }
                    }
                }


        # Удаляем мусор
        foreach ( $spreadsheet->getAllSheets() as $worksheet )
            foreach ( $worksheet->getRowIterator() as $rowIndex => $row )
                foreach ( $row->getColumnIterator() as $cell )
                    if( str_contains($cell->getValue(), "{service::position}") )
                        $worksheet->removeRow($rowIndex);

        return $spreadsheet;
    }

    /***
     * Заменят простые значения в ячейках.
     * @param Cell $cell
     * @param PrimaryDocument $document
     * @return void
     * @throws Exception
     */
    private function replaceScalarPlaceholder( Cell $cell, PrimaryDocument $document ):void
    {
        $cell->setValue(strtr($cell->getValue(), [
            "{{number}}" => $document->getNumber(),
            "{{employer}}" => $this->businessFormatter->format($document->getEmployer()),
            "{{executor}}" => $this->businessFormatter->format($document->getExecutor()),
            "{{basis}}" => $document->getBasis(),
            "{{services::count}}" => $document->getServices()->count(),
            "{{date::day}}" => $this->dateFormatter->formatDay( $document->getDate() ),
            "{{date::month}}" => $this->dateFormatter->formatMonth( $document->getDate() ),
            "{{date::year}}" => $this->dateFormatter->formatYear( $document->getDate() ),
            "{{document::total}}" => $this->moneyFormatter->formatValue( $document->getTotal() ),
            "{{document::total::string}}" => $this->moneyFormatter->formatString($document->getTotal(),true)
        ]));
    }
}