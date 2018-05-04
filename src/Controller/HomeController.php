<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Products;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/add", name="add")
     */
    public function addAction()
    {
        $category = 'Komputeriai';
        $product = ['title'=>'Toshiba',
                     'price'=>'650eur',
                     'active'=>1
        ];

        $entityCategories = new Categories();
        $entityCategories->setTitle($category);

        $entityProducts = new Products();
        $entityProducts->setTitle($product['title']);
        $entityProducts->setPrice($product['price']);
        $entityProducts->setCategory($entityCategories);
        $entityProducts->setActive($product['active']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($entityProducts);
        $entityManager->flush();

        return $this->render('home/index.html.twig',[
            'id' => $entityProducts->getId(),

        ]);



    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function removeAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($entityManager->find(Products::class, $id));
        $entityManager->flush();


        return $this->redirect($this->generateUrl('home',
            [
                'info'=>'Preke_'.$id.'_ištrinta'
            ]));


    }

}





