<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 18.7.13
 * Time: 00.16
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Ticket;
use App\Entity\Genre;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class TicketsFixtures extends Fixture
{
    const PRICE = [70, 50, 40];
    const GENRE = ['MUSICAL', 'COMEDY', 'DRAMA'];

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $entityManager)
    {
        $fp = fopen('data/shows.csv', 'r');
        $prices = ['MUSICAL' => 70,
            'COMEDY' => 50,
            'DRAMA' => 40
        ];

        foreach($prices as $key => $value) {
            $entityGenre = new Genre();
            $entityGenre->setGenre($key);
            $entityGenre->setPrice($value);
            $entityManager->persist($entityGenre);
            $entityManager->flush();
        }

        while (($data = fgetcsv($fp, 1000, ",")) !== FALSE) {
            $entityTicket = new Ticket();
            $entityTicket->setTitle($data[0]);
            $date = new \DateTime($data[1]);
            $entityTicket->setDate($date);
            $entityTicket->setTicketsAvailable('1');
            $entityTicket->setGenre($this->addGenre(trim($data[2]), $entityManager));
            $entityTicket->setStatus('Sale not started');
            $entityManager->persist($entityTicket);
            $entityManager->flush();
        }
    }

    public function addGenre($data, ObjectManager $entityManager)
    {
        $genre = $entityManager->getRepository(Genre::class)->findOneByGenre($data);

        return $genre;
    }
}