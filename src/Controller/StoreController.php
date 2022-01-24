<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;


/**
 * @IsGranted("ROLE_STOREMAN")
 */
class StoreController extends AbstractController
{



    /**
     * @Route("/add",name="add")
     */
    public function add(EntityManagerInterface $em, Request $request): Response
    {

       $product = new Product();

       $form = $this->createForm(ProductType::class, $product);
       $form->handleRequest($request);



       if($form->isSubmitted() && $form->isValid())
       {
           $this->addFlash(
               'notice-save',
               'Product "' . $product->getName() . '" has been saved'
           );

           $user = $this->getUser();
           $user->addProduct($product);
           $em->persist($product);
           $em->flush();

           return $this->redirectToRoute('show');
       }


        return $this->render('store/add.html.twig', [
           'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/delete-product/{id}", name="store_delete_product", methods={"DELETE"})
     */
    public function deleteProduct(EntityManagerInterface $em, int $id): Response
    {
        
        $product = $em->getRepository(Product::class)->find($id);


        $this->addFlash(
            'notice-delete',
            'Product "' . $product->getName() . '" has been deleted'
        );

        $em->remove($product);
        $em->flush();
        return new Response('success');
    }

    /**
     * @Route("/edit-product/{id}", name="store_edit_product", methods={"GET", "POST"})
     */

    public function editProduct(EntityManagerInterface $em, int $id, Request $request): Response
    {
        $product = $em->getRepository(Product::class)->find($id);

        
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {        
            $this->addFlash(
                'notice-edit',
                'Product "' . $product->getName() . '" has been changed'
            );

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('show');
        }


        return $this->render('store/edit_product.html.twig',[
            'form'=>$form->createView(),
        ]);

    }

    /**
     * @Route("/export/products",name="store_export_products", methods={"GET"})
     */

    public function exportProductsToCsv(EntityManagerInterface $em, Request $request, SerializerInterface $serializer)
    {
        $ids = $request->query->get('ids');
        $productsIds = explode(',', $ids);
        $products = $em->getRepository(Product::class)->findSelectedByUser($productsIds);


        $csv = $serializer->serialize(
            $products, 
            'csv', 
            [CsvEncoder::NO_HEADERS_KEY=> true,
            'groups' => 'export']
        );
        
        $response = new Response($csv);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="products.csv"');


        return $response;
    }


}
