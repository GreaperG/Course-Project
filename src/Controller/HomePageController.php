<?php

namespace App\Controller;

use App\Entity\Inventory;
use App\Repository\InventoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomePageController extends AbstractController
{
    #[Route('/home/page', name: 'app_home_page')]
    public function index(Request $request,EntityManagerInterface $entityManager,PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        $latestQuery = $entityManager->getRepository(Inventory::class)
            ->createQueryBuilder('i')
            ->leftJoin('i.owner', 'u')
            ->addSelect('u')
            ->orderBy('i.createdAt', 'DESC')
            ->getQuery();

        $latestPagination = $paginator->paginate(
            $latestQuery,
            $request->query->getInt('page', 1),
            10
        );

        $popularInventories = $entityManager->getRepository(Inventory::class)
            ->createQueryBuilder('i')
            ->leftJoin('i.items', 'it')
            ->addSelect('COUNT(it.id) AS itemsCount')
            ->groupBy('i.id')
            ->orderBy('itemsCount', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();


        return $this->render('home_page/index.html.twig', [
            'latestPagination' => $latestPagination,
            'popularInventories' => $popularInventories,
        ]);
    }
    #[Route('/home/userPage', name: 'app_home_userPage')]
    public function userPage(EntityManagerInterface $entityManager,InventoryRepository $inventoryRepository): Response
    {
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        $inventories = $inventoryRepository->findBy(['owner' => $user]);

        return $this->render('home_page/user.html.twig', [
            'user' => $user,
            'inventories' => $inventories,
        ]);
    }
}
