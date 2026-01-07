<?php

namespace App\Controller;

use App\Entity\Inventory;
use App\Entity\Item;
use App\Entity\ItemAttributeValue;
use App\Enum\AttributeType;
use App\Form\ItemType;
use App\Repository\InventoryRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;



final class InventoryItemController extends AbstractController
{
    #[Route('/inventory/{inventoryId}/items', name: 'app_inventory_items', methods: ['GET'])]
    public function index(Request $request,int $inventoryId,ItemRepository $itemRepository,InventoryRepository $inventoryRepository,PaginatorInterface $paginator): Response
    {

        $inventory = $inventoryRepository->find($inventoryId);
        if(!$inventory) {
            throw $this->createNotFoundException();
        }

        $query = $itemRepository->createQueryBuilder('i')
            ->leftJoin('i.itemAttributeValues', 'av')
            ->addSelect('av')
            ->leftJoin('av.attribute', 'a')
            ->addSelect('a')
            ->where('i.inventory = :inventory')
            ->setParameter('inventory', $inventory)
            ->orderBy('i.createdAt', 'DESC')
            ->getQuery();


            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                20
            );

        return $this->render('inventory_item/index.html.twig',[
            'inventory' => $inventory,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/inventory/{inventoryId}/createItem', name: 'app_inventory_item_create')]
     public function createItem(int $inventoryId, Request $request, EntityManagerInterface $em): Response
    {
        $inventory = $this->findInventoryOrThrow($inventoryId, $em);

        $this->denyAccessUnlessGranted('EDIT', $inventory);

        $item = new Item();
        $item->setInventory($inventory);
        $item->setCreatedBy($this->getUser());

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(empty($item->getCustomId())){
                $item->setCustomId($this->generateUniqueId());
            }

          try{
            foreach($inventory->getInventoryAttributes() as $attribute) {
                $fieldName = 'attr_' . $attribute->getId();
                $value = $form->get($fieldName)->getData();

                if($value === null || $value === '') {
                    continue;
                }

                if($attribute->getType() === AttributeType::BOOLEAN) {
                    $value = $value ? '1' : '0';
                }

                $attrValue = new ItemAttributeValue();
                $attrValue->setAttribute($attribute);
                $attrValue->setValue((string) $value);

                $item->addItemAttributeValue($attrValue);

                $em->persist($attrValue);
            }
            $em->persist($item);
            $em->flush();

            $this->addFlash('success', 'Item created.');

            return $this->redirectToRoute('app_inventory_items', [
                'inventoryId' => $inventoryId,
            ]);
          } catch (OptimisticLockException $e) {
              $this->addFlash('error', 'Inventory was modified by someone else.');
              $em->refresh($inventory);
          }
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
        $this->denyAccessUnlessGranted('EDIT', $inventory);

        $form = $this->createForm(ItemType::class, $item);
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

    #[Route('/inventory/{inventoryId}/item/{id}/delete', name: 'app_inventory_item_delete')]
    public function delete(int $inventoryId,int $id, EntityManagerInterface $em): Response
    {
        $item = $em->getRepository(Item::class)->find($id);

        $this->denyAccessUnlessGranted('DELETE', $item);

        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        if ($item->getInventory()->getId() !== $inventoryId) {
            throw $this->createNotFoundException('Item does not belong to this inventory');
        }


        $em->remove($item);
        $em->flush();
        return $this->redirectToRoute('app_inventory_items', ['inventoryId' => $inventoryId]);
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

    public function generateUniqueId(): string
    {
        return bin2hex(random_bytes(8));
    }
}
