<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TicketsController extends Controller
{
    /**
     * @Route("/", name="tickets")
     */
    public function index()
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'TicketsController',
        ]);
    }
}
