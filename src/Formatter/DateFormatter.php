<?php

namespace Maris\Symfony\DocumentFlow\Formatter;

use DateTimeInterface;

/***
 * Форматирует дату.
 */
class DateFormatter
{
    const MONTH = ["Января","Февраля","Марта","Апреля","Мая","Июня","Июля","Августа","Сентября","Октября","Ноября","Декабря"];

    /**
     * Форматирует дату целиком.
     * @param DateTimeInterface $date
     * @return string
     */
    public function format( DateTimeInterface $date ):string
    {
        return "{$this->formatDay($date)} {$this->formatMonth($date)} {$this->formatYear($date)}г.";
    }

    /**
     * Форматирует день.
     * @param DateTimeInterface $date
     * @return string
     */
    public function formatDay( DateTimeInterface $date ):string
    {
        return $date->format("d");
    }

    /**
     * Форматирует месяц
     * @param DateTimeInterface $date
     * @return string
     */
    public function formatMonth( DateTimeInterface $date ):string
    {
        return self::MONTH[ (int)$date->format("m") + 1 ];
    }

    /**
     * Форматирует год
     * @param DateTimeInterface $date
     * @return string
     */
    public function formatYear( DateTimeInterface $date ):string
    {
        return $date->format("Y");
    }
}