<?php

namespace App\Controller;

use App\Repository\InventoryRepository;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(
        Request $request,
        InventoryRepository $inventoryRepo,
        ItemRepository $itemRepo,
    ): Response
    {
        $query = $request->query->get('q', '');

        if(trim($query) == ''){
            return $this->redirectToRoute('search/results.html.twig', [
                'query' => '',
                'inventories' => [],
                'items' => [],
            ]);
        }

        $inventories = $inventoryRepo->search($query);

        $items = $itemRepo->search($query);

        return $this->render('search/result.html.twig', [
            'query' => $query,
            'inventories' => $inventories,
            'items' => $items,
        ]);
    }
}
