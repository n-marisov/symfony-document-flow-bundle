<?php

namespace Maris\Symfony\DocumentFlow\Formatter;

use Maris\Symfony\Company\Entity\Business\Business;
use Maris\Symfony\Company\Entity\Business\Company;
use Maris\Symfony\Company\Entity\Business\Employed;
use Maris\Symfony\Company\Entity\Business\Entrepreneur;
use Maris\Symfony\Person\Entity\Person;

class BusinessFormatter
{
    public function format( Business $business ):string
    {
        $format = match ($business::class){
            Employed::class => $this->personFormat( $business->getPerson() ),
            Entrepreneur::class => "ИП ".$this->personFormat( $business->getPerson() ),
            Company::class => $business->getLegalForm()->getShort() . " " . $business->getName()
        };

        //$format .= " ". $business->getAddress()->getUnrestricted();

        $format .= " ,ИНН: ". $business->getInn();

        return $format;
    }


    private function personFormat( Person $person ):string
    {
        return $person->getSurname()." ".mb_substr($person->getFirstname(),0,1).".".mb_substr($person->getPatronymic(),0,1).".";
    }
}