<?php


namespace App\Service\Provider;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Class UbuntuCertifiedHardwareSite
 * @package App\Service\Provider
 *
 * @see https://certification.ubuntu.com/
 */
class UbuntuCertifiedHardwareSite
{

    public function desktop()
    {
        $list = [];
        $list[] = ['make' => 'HP', 'desktop' => 0, 'laptop' => 0];
        $html = file_get_contents('https://certification.ubuntu.com/desktop');
        $crawler = new Crawler($html);
        foreach ($crawler as $domElement) {
            var_dump($domElement->nodeName);
        }
    }
}
