<?php

namespace App\Service;

use App\Entity\Hardware;
use App\Entity\HardwareImage;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class Yandex
{
    public function getTitle()
    {
        return 'Yandex Marker ';
    }

    public function getLink(Hardware $hardware): string
    {
        return 'https://market.yandex.ru/search?text=' . $hardware->getFullName();
    }

    public function getImages(Hardware $hardware, int $limit)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://yandex.ru/images/search?text=' . $hardware->getFullName());

        $images = [];
        $crowler = new Crawler($response->getContent());
        foreach ($crowler->filter('[role="listitem"]') as $item) {
            $element = new Crawler($item);
            $json = ($element->attr('data-bem'));
            if ($json) {
                if ($array = @json_decode($json, true)) {
                    $obj = $array['serp-item'] ?? [];
                    foreach ($obj['dups'] ?? [] as $img) {
                        $origin = $img['origin'] ?? $img;
                        $h = $origin['h'];
                        $w = $origin['w'];
                        $url = $origin['url'];
                        $image = new HardwareImage();
                        $image->setHardware($hardware);
                        $image->setName($hardware->getFullName());
                        $image->setWidth($w);
                        $image->setHeight($h);
                        $image->setLink($url);
                        $image->setPath('wait');
                        $images[] = $image;
                        if (count($images) > $limit) {
                            break 2;
                        }
                    }
                }
            }
        }
        return $images;
    }
}