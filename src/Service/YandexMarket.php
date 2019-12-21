<?php

namespace App\Service;

use App\Entity\Hardware;

class YandexMarket
{
    public function getTitle()
    {
        return 'Yandex Marker ';
    }

    public function getLink(Hardware $hardware): string
    {
        return 'https://market.yandex.ru/search?text=' . $hardware->getFullName();
    }
}