<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
       // $show_data = $ticketRepository->findAllTickets();
        $form = $this->getSearchForm();

        return $this->render(
            'home/index.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/search", name="search")
     * @Method("GET")
     */
    public function search(Request $request, TicketRepository $ticketRepository)
    {

    }

    public function getSearchForm()
    {
        return $this->createForm(
            SearchType::class,
            null,
            [
                'method' => 'GET',
                'action' => $this->generateUrl('search'),
            ]
        );
    }

}





