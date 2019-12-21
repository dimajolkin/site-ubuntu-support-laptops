<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\HardwareRepository;
use App\Repository\VendorRepository;
use App\Service\SearchEngine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     * @param VendorRepository $vendorRepository
     * @param CategoryRepository $categoryRepository
     * @param HardwareRepository $hardwareRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        SearchEngine $engine,
        VendorRepository $vendorRepository,
        CategoryRepository $categoryRepository,
        HardwareRepository $hardwareRepository
    ) {
        return $this->render('index.html.twig', [
            'searchEngine' => $engine,
            'hardwares' => $hardwareRepository->findBy([], null, 10),
            'vendors' => $vendorRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}
