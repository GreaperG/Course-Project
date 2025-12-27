<?php

namespace App\Controller;

use App\Entity\Inventory;
use App\Repository\InventoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomePageController extends AbstractController
{
    #[Route('/home/page', name: 'app_home_page')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        $latestInventories = $entityManager->getRepository(Inventory::class)
            ->createQueryBuilder('i')
            ->where('i.owner = :user')
            ->setParameter('user', $this->getUser())
            ->orderBy('i.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $this->render('home_page/index.html.twig', [
            'latestInventories' => $latestInventories,
        ]);
    }
    #[Route('/home/userPage', name: 'app_home_usePage')]
    public function userPage(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        return $this->render('home_page/user.html.twig', [
            'user' => $user,
        ]);
    }
}
