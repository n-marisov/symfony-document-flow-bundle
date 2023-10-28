<?php

namespace Maris\Symfony\DocumentFlow\Formatter\Excel;

use Maris\Symfony\DocumentFlow\Entity\PrimaryDocument;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/***
 * Форматирует первичный документ в Excel документ
 * для конкретной организации.
 */
interface IndividualFormatterExcelInterface
{
    /***
     * Форматирует первичный документ в лист Excel.
     * @param PrimaryDocument $document
     * @return Spreadsheet
     */
    public function format( PrimaryDocument $document ):Spreadsheet;
}