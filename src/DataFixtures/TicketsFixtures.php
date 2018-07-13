<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 18.7.13
 * Time: 00.16
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Ticket;
use App\Entity\Genre;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class TicketsFixtures extends Fixture
{
    const USER_NAME = 'admin';

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $entityManager)
    {
        $this->addUser($entityManager);

        $fp = fopen('src/DataFixtures/shows.csv', 'r');
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
    public function addUser(ObjectManager $entityManager)
    {
        $user = new User();
        $username = self::USER_NAME;
        $user->setUsername($username);
        $password = $this->encoder->encodePassword($user, 'testAdmin');
        $user->setEmail(strtolower(self::USER_NAME) . '@mail.com');
        $user->setPassword($password);
        $user->setEnabled(true);
        $entityManager->persist($user);
        $entityManager->flush();
    }
    public function addGenre($data, ObjectManager $entityManager)
    {
        $genre = $entityManager->getRepository(Genre::class)->findOneByGenre($data);

        return $genre;
    }
}