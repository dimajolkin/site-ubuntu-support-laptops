<?php

namespace App\Command;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\HardwareRepository;
use App\Repository\VendorRepository;
use App\Service\Provider\UbuntuCertifiedHardwareSite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ParserCommand extends Command
{
    protected static $defaultName = 'app:parser';
    /**
     * @var VendorRepository
     */
    private $vendorRepository;
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var HardwareRepository
     */
    private $hardwareRepository;

    public function __construct(
        EntityManagerInterface $manager,
        VendorRepository $vendorRepository,
        CategoryRepository $categoryRepository,
        HardwareRepository $hardwareRepository
    )
    {
        $this->vendorRepository = $vendorRepository;
        $this->manager = $manager;
        parent::__construct();
        $this->categoryRepository = $categoryRepository;
        $this->hardwareRepository = $hardwareRepository;
    }

    protected function configure()
    {
        $this
            ->setName('app:parser')
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function insertVendors(UbuntuCertifiedHardwareSite $parser)
    {
        foreach ($parser->parseVendors() as $vendor) {
            if (!$this->vendorRepository->exist($vendor)) {
                $this->manager->persist($vendor);
            }
        }
        $this->manager->flush();
    }

    protected function insertCategory()
    {
        foreach (['Desktop', 'Laptop'] as $name) {
            $category = new Category();
            $category->setName($name);
            if (!$this->categoryRepository->exist($category)) {
                $this->manager->persist($category);
            }
        }
        $this->manager->flush();
    }

    protected function hardwarePagination(callable $func)
    {
        $page = 1;
        while (true) {
            $hardwares = $func($page);
            if (count($hardwares) === 0) {
                break;
            }
            foreach ($hardwares as $hardware) {
                if (!$this->hardwareRepository->exist($hardware)) {
                    $this->manager->persist($hardware);
                }
            }
            $page++;
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $parser = new UbuntuCertifiedHardwareSite();
        $this->insertVendors($parser);
        $this->insertCategory();

        foreach ($this->categoryRepository->findAll() as $category) {
            foreach ($this->vendorRepository->findAll() as $vendor) {
                $this->hardwarePagination(function (int $page) use ($output, $category, $vendor, $parser) {
                    $output->writeln("{$category->getName()} {$vendor->getName()} Page: $page");
                    return $parser->getHardwares($category, $vendor, $page);
                });
            }
        }

        $this->manager->flush();
        return 0;
    }
}
