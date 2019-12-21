<?php


namespace App\Service\Provider;

use App\Entity\Category;
use App\Entity\Hardware;
use App\Entity\Vendor;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class UbuntuCertifiedHardwareSite
 * @package App\Service\Provider
 *
 * @see https://certification.ubuntu.com/
 */
class UbuntuCertifiedHardwareSite
{

    /**
     * @return iterable|Vendor[]
     */
    public function parseVendors(): iterable
    {
        $list = [];
        $html = file_get_contents('https://certification.ubuntu.com/desktop');
        $crawler = new Crawler($html);

        $trElements = $crawler->filter('.catalog tr');
        // iterate over filter results
        foreach ($trElements as $i => $content) {
            $tds = [];
            // create crawler instance for result
            $crawler = new Crawler($content);
            //iterate again
            foreach ($crawler->filter('td') as $i => $node) {
                // extract the value
                $tds[] = trim($node->nodeValue);
            }
            if ($tds && $i) {
                $vendor = new Vendor();
                $vendor->setName($tds[0]);
                $list[] = $vendor;
            }

            if (!$tds && $i) {
                break;
            }
        }
        return $list;
    }

    /**
     * @param Category $category
     * @param Vendor $vendor
     * @param int $page
     * @return Hardware[]
     */
    public function getHardwares(Category $category, Vendor $vendor, int $page)
    {
        $filters = [
            'category' => $category->getName(), // 'Desktop', //Laptop
            'vendors' => $vendor->getName(),
            'page' => $page,
        ];
        $html = file_get_contents('https://certification.ubuntu.com/desktop/models?' . http_build_query($filters));

        $crawler = new Crawler($html);
        $list = [];
        foreach ($crawler->filter('ul[class="model-list"] li') as $node) {
            $node = new Crawler($node);

            $link = $node->filter('a')->attr('href');
            $name = $node->filter('a')->text();

            $hardware = new Hardware();
            $hardware->setName($name);
            $hardware->setLink($link);
            $hardware->setCategory($category);
            $hardware->setVendor($vendor);

            $list[] = $hardware;
        }

        return $list;
    }

    public function hardwares()
    {
        $category = new Category();
        $category->setName('Laptop');

        $vendor = new Vendor();
        $vendor->setName('Dell');

        $hardwares = $this->getHardwares($category, $vendor);

        dump($hardwares);
    }
}
