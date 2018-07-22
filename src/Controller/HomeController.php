<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\SearchType;
use App\Repository\TicketRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    const SALE_START = 25;
    const SALE_END = 5;
    const DAY_HELD_IN_SMALL_HALL = 60;
    const DAY_START_DISCOUNT = 80;
    const TICKETS_IN_BIG_HALL = 200;
    const TICKETS_IN_SMALL_HALL = 100;
    const TICKETS_AVAILABLE_IN_BIG_HALL = 10;
    const TICKETS_AVAILABLE_IN_SMALL_HALL = 5;
    const DISCOUNT_PERCENTAGE = 20;
    const OPEN_SALE_STATUS = 'Open for sale';
    const OUT_SALE_STATUS  = 'Sold out';
    const PAST_SALE_STATUS  = 'In the past';
    const NOT_STARTED_SALE_STATUS = 'Sale not started';

    /**
     * @Route("/", name="home")
     */
    public function index(TicketRepository $ticketRepository)
    {
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
    public function search(Request $request, TicketRepository $ticketRepository, ObjectManager $entityManager)
    {
        $form = $this->getSearchForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $shows = $ticketRepository->findByDate($form->getData());
            $showInfo = $this->getTicketsInfo($shows, $form->getData());
            return $this->render(
                'search/index.html.twig',
                [
                    'form' => $form->createView(),
                    'shows' => $shows,
                    'data' => $showInfo
                ]
            );
        }
    }

    public function getTicketsInfo($results, $searchDataValue)
    {
        $data = array();

        foreach ( $results as $value ) {
            if ($value instanceof Ticket ) {

                $searchDate  = new \DateTime(sprintf('%s', $searchDataValue['showDate']->format('Y-m-d')));
                $dateSaleStart = clone $searchDate;
                $dateSaleEnd = clone $searchDate;

                $dateSaleStart = $dateSaleStart->modify('-'.self::SALE_START.' day');
                $dateSaleEnd = $dateSaleEnd->modify('-'.self::SALE_END.' day');

                $today = new \DateTime();

                if (($dateSaleStart <= $today) && ($dateSaleEnd >= $today)) {
                    $showStartDate = $value->getDate();
                    $startInSmallHall = new \DateTime($showStartDate->format('Y-m-d'));
                    $startInSmallHall = $startInSmallHall->modify('+'.self::DAY_HELD_IN_SMALL_HALL.' day');
                    $dayStartDiscount = clone $showStartDate;
                    $dayStartDiscount = $dayStartDiscount->modify('+'.self::DAY_START_DISCOUNT.' day');
                    $startDateToTimestamp = $dateSaleStart->getTimestamp();
                    $todayToTimestamp = $today->getTimestamp();
                    $day = floor(($todayToTimestamp - $startDateToTimestamp) / (60 * 60 * 24));
                    $data[$value->getId()] = [
                        'status' => self::OPEN_SALE_STATUS,
                        'search_date' => $searchDataValue['showDate'],
                    ];
                    if ($startInSmallHall < $searchDate) {
                        $ticketsLeft = self::TICKETS_IN_BIG_HALL - $day*self::TICKETS_AVAILABLE_IN_BIG_HALL;
                        $data[$value->getId()] += [
                        'tickets_available' => self::TICKETS_AVAILABLE_IN_BIG_HALL,
                        'tickets_left' => $ticketsLeft
                        ];
                    } else {
                        $ticketsLeft = self::TICKETS_IN_SMALL_HALL - $day*self::TICKETS_AVAILABLE_IN_SMALL_HALL;
                        $data[$value->getId()] += [
                            'tickets_available' => self::TICKETS_AVAILABLE_IN_SMALL_HALL,
                            'tickets_left' => $ticketsLeft
                        ];
                    }

                    if( $dayStartDiscount < $searchDate ) {
                        $data[$value->getId()] += [
                            'discount' => self::DISCOUNT_PERCENTAGE
                        ];
                    }

                } elseif (($dateSaleEnd < $today) && ($searchDate >= $today)) {
                    $data[$value->getId()] = array(
                        'status' => self::OUT_SALE_STATUS,
                        'tickets_available' => 0,
                        'tickets_left' => 0
                    );
                } elseif ( $searchDate < $today ) {
                    $data[$value->getId()] = array(
                        'status' => self::PAST_SALE_STATUS,
                        'tickets_available' => 0,
                        'tickets_left' => 0
                    );
                }  else {
                    $data[$value->getId()] = array(
                        'status' => self::NOT_STARTED_SALE_STATUS,
                        'tickets_available' => 0,
                        'tickets_left' => self::TICKETS_IN_BIG_HALL
                    );
                }

            }
        }
        return $data;
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
