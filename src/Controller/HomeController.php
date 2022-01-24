<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ChartBuilderInterface $chartBuilder, EntityManagerInterface $em): Response
    {

        $products = $em->getRepository(Product::class)->findAll();
        $totalStock = $em->getRepository(Product::class)->countNumberItems();

        $labels = [];
        $data = [];
        $backgroundColors = [];
        $colors = [];
        foreach ($products as $product) {
            $labels[] = $product->getName();
            $data[] = $product->getStock();
            rsort($data);
        }

        for($i=0; $i<count($products); $i++)
        {


            $first = rand(0, 255);
            $second = rand(0, 255);
            $third = rand(0, 255);

            $backgroundColor = 'rgba(' . $first .','. $second . ','. $third. ',0.5)';
            $color = 'rgba(' . $first .','. $second . ','. $third. ',1)';
            $backgroundColors[] = $backgroundColor;
            $colors[] = $color;

        }


        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Stock',
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $colors,
                    'borderWidth' => 1,
                    'data' => $data
                ],
            ],
        ]);

        $chart->setOptions([
                'plugins' => [
                    'title' => [
                        'display'=>true,
                        'text' => 'Total stock: ' . $totalStock,
                        'color' => 'black',
                        'padding' =>20,
                        'font' => [
                            'size'=> 25
                        ],
                    ],
                    'legend' => [
                        'display' => false,
                    ],
                ],
    ]);

        return $this->render('home/index.html.twig', [
            'chart' => $chart
        ]);
    }

    /**
     * @Route("/show/{maxProductsPerPage}",name="show")
     */

     public function show(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator, int $maxProductsPerPage=5 ): Response
     {
        $productsData = $em->getRepository(Product::class)->findAllForPagination();


        $products = $paginator->paginate(
            $productsData,
            $request->query->getInt('page', 1),
            $maxProductsPerPage
        );

         return $this->render('home/show.html.twig', [
            'products' => $products,
            'maxProductsPerPage' => $maxProductsPerPage

         ]);
     }

}
