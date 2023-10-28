<?php

namespace Maris\Symfony\DocumentFlow\Formatter\Excel;

use Maris\Symfony\DocumentFlow\Entity\PrimaryDocument;
use Maris\Symfony\DocumentFlow\Formatter\DateFormatter;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/***
 * Форматирует акт выполненных работ для организации Вист-Ростов.
 */
class VistCertificateFormatter implements IndividualFormatterExcelInterface
{

    const TEMPLATE = __DIR__.'/../../../resoursces/tempalates/excel/vist-rostov.xlsx';

    protected Spreadsheet $template;

    public function __construct()
    {
        $this->template = IOFactory::load(self::TEMPLATE);
    }


    /**
     * @throws Exception
     */
    public function format( PrimaryDocument $document ): Spreadsheet
    {
        $spreadsheet = $this->template->copy();
        $sheetCertificate = $spreadsheet->getSheet(0);

        $this->initCertificateNumber( $sheetCertificate->getCell("A6"), $document );
        $this->initCertificateDate( $sheetCertificate->getCell("G6"), $document );
        $this->initCertificateTotal( $sheetCertificate->getCell("J15"), $document );

        $this->initCertificateTotalStringOne( $sheetCertificate->getCell("A18"), $document );
        $this->initCertificateTotalStringTwo( $sheetCertificate->getCell("A20"), $document );

        /***
         * Добавить добовления услуг.
         */
        return $spreadsheet;
    }

    /**
     * Устанавливает значение ячейки номер акта.
     * @param Cell $cell
     * @param PrimaryDocument $document
     * @return void
     * @throws Exception
     */
    protected function initCertificateNumber( Cell $cell, PrimaryDocument $document ):void
    {
        $cell->setValue("АКТ ПРИЕМКИ ВЫПОЛНЕННЫХ РАБОТ  № {$document->getNumber()}");
    }

    /**
     * Устанавливает дату документа
     * @param Cell $cell
     * @param PrimaryDocument $document
     * @return void
     * @throws Exception
     */
    protected function initCertificateDate( Cell $cell, PrimaryDocument $document ):void
    {
        $cell->setValue( $document->getDate()->format("d.m.Y"));
    }

    /**
     * Устанавливает итого:
     * @throws Exception
     */
    protected function initCertificateTotal(Cell $cell, PrimaryDocument $document ):void
    {
        $cell->setValue( $this->getTotalInt($document) );
    }

    /**
     * Устанавливает итого:
     * @throws Exception
     */
    protected function initCertificateTotalStringOne(Cell $cell, PrimaryDocument $document ):void
    {
        $total = $this->getTotalInt($document);
        $int = intval($total);
        $flout = $total - $int;
        $flout *= 100;
        if($flout < 10)
            $flout = "0" . $flout;
        elseif( $flout % 10)
            $flout .= "0";

        $cell->setValue( "Всего оказано услуг на сумму: {$int}  рублей {$flout} коп." );
    }

    /**
     * Устанавливает итого:
     * @throws Exception
     */
    protected function initCertificateTotalStringTwo(Cell $cell, PrimaryDocument $document ):void
    {
        $total = $this->getTotalInt($document);
        $int = intval($total);
        $flout = $total - $int;
        $flout *= 100;
        if($flout < 10)
            $flout = "0" . $flout;
        elseif( $flout % 10)
            $flout .= "0";
        $cell->setValue( "Всего оказано услуг на сумму: {$int}  рублей {$flout} коп.(НДС не облагается)" );
    }

    protected function getTotalInt( PrimaryDocument $document ):float
    {
        return $document->getTotal()->getAmount() / 100;
    }
}