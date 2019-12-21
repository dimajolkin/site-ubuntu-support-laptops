<?php


namespace App\Service;


class SearchEngine
{
    public function getYandex()
    {
        return new YandexMarket();
    }
}