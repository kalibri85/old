<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Ticket;
use App\Repository\GenreRepository;
use App\Repository\TicketRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(TicketRepository $ticketRepository, GenreRepository $genreRepository)
    {
       // $show_data = $ticketRepository->findAllTickets();

        return $this->render('home/index.html.twig');
    }

}





