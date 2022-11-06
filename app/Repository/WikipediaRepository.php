<?php

namespace App\Repository;

use App\Services\CurrencyInformationInput;
use App\Services\CurrencyInformationOutput;
use Weidner\Goutte\GoutteFacade;

class WikipediaRepository implements CurrencyDataSource
{
    const URL = "https://pt.wikipedia.org/wiki/ISO_4217";

    private $crawler;

    public function __construct()
    {
        $this->crawler = GoutteFacade::request('GET', self::URL);
    }

    public function find(CurrencyInformationInput $input): CurrencyInformationOutput
    {
       $crawlerResults =  $this->crawler->filter('.wikitable')->eq(0)->filter('tr')->each(function ($tr) {
           return $tr->filter('td')->each(function ($td, $columnIndex)  {
               if ($columnIndex == 4) {
                  return $this->getCirculationPlaces($td);
               }
               return trim($td->text());
           });
        });

       $crawlerResults = collect($crawlerResults);
       $crawlerResults->pull(0);
       $crawlerResults = $crawlerResults
           ->filter(function ($result) use ($input) {
               return in_array($result[0], $input->codes) || in_array($result[1], $input->numbers);
           })
           ->map(function ($result) {
               return [
                   'code' => $result[0],
                   'number' => (int) $result[1],
                   'decimal' => (int) $result[2],
                   'currency' => $result[3],
                   'currency_locations' => $result[4]
               ];
           });
        $crawlerResults = $crawlerResults->values();
        $output = new CurrencyInformationOutput();
        $output->data = $crawlerResults->toArray();
        return $output;
    }

    private function getCirculationPlaces($tableData): array
    {
        $countries = explode(",", $tableData->text());
        $locals = [];
        $quantityImages = count($tableData->filter('img'));

        if ($quantityImages >= 1) {
            $locals = $tableData->filter('img')->each(function ($img, $i) use (&$countries){
                $local = [
                    'location' => trim(str_replace(" ", " ", $countries[$i] ?? "")),
                    'icon' =>  "https:{$img->attr('src')}"
                ];
                unset($countries[$i]);
                return $local;
            });
        }

        foreach ($countries as $country) {
            $locals[] = [
                'location' => trim(str_replace(" ", " ", $country)),
                'icon' =>  ""
            ];
        }
        return $locals;
    }
}