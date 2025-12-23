<?php

namespace App\Controller;

use App\Entity\Inventory;
use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\InventoryRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class InventoryItemController extends AbstractController
{
    #[Route('/inventory/{inventoryId}/items', name: 'app_inventory_items', methods: ['GET'])]
    public function index(int $inventoryId,ItemRepository $itemRepository,InventoryRepository $inventoryRepository,): Response
    {

        $inventory = $inventoryRepository->find($inventoryId);
        if($inventory === null) {
            throw $this->createNotFoundException();
        }

        $items = $itemRepository->findBy(['inventory' => $inventory]);

        return $this->render('inventory_item/index.html.twig',[
            'inventory' => $inventory,
            'items' => $items,

        ]);
    }

    #[Route('/inventory/{inventoryId}/createItem', name: 'app_inventory_item_create')]
     public function createItem(int $inventoryId, Request $request, EntityManagerInterface $em): Response
    {
        $inventory = $this->findInventoryOrThrow($inventoryId, $em);
        $this->checkAccess($inventory);

        $item = new Item();
        $item->setInventory($inventory);
        $item->setCreatedBy($this->getUser());

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('app_inventory_items', [
                'inventoryId' => $inventoryId,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inventory_item/create.html.twig', [
            'form' => $form->createView(),
            'inventory' => $inventory,
            'item' => $item,
        ]);
    }
    #[Route('/inventory/{inventoryId}/item/{id}/edit', name: 'app_inventory_item_edit')]
     public function edit(int $inventoryId,Item $item, Request $request, EntityManagerInterface $em): Response
    {
        $inventory = $item->getInventory();

        $this->checkAccess($inventory);

        $response = $this->handleItemForm($item, $request, $em);
        if($response){
            return $response;
        }

        return $this->render('inventory_item/edit.html.twig', [
            'form' => $this->createForm(ItemType::class, $item)->createView(),
            'inventory' => $inventory,
            'item' => $item,
        ]);
    }

    #[Route('/inventory/{inventoryId}/item/{id}/delete', name: 'app_inventory_item_delete', methods: ['POST'])]
    public function delete(Item $item, EntityManagerInterface $em,): Response
    {

        $inventory = $item->getInventory();
        $this->checkAccess($inventory);
        $em->remove($item);
        $em->flush();
        return $this->redirectToRoute('app_inventory_item_index', ['inventoryId' => $inventory->getId()]);
    }
    private function checkAccess(Inventory $inventory): void
    {
        $user = $this->getUser();
        if($inventory->getOwner() !== $user && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
    }

    private function handleItemForm(Item $item, Request $request, EntityManagerInterface $em): ?Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!$em->contains($item)){
                $em->persist($item);
            }
            $em->flush();

            return $this->redirectToRoute('app_inventory_items',[
                'inventoryId' => $item->getInventory()->getId()
            ]);

        }
        return null;
    }
    private function findInventoryOrThrow(Int $id, EntityManagerInterface $em): Inventory
    {
        $inventory = $em->getRepository(Inventory::class)->find($id);
        if($inventory === null) {
            throw $this->createNotFoundException();
        }
        return $inventory;
    }

    private function findItemOrThrow(int $id, EntityManagerInterface $em): Item
    {
        $item = $em->getRepository(Item::class)->find($id);
        if(!$item) {
            throw $this->createNotFoundException();
        }
        return $item;
    }
}
