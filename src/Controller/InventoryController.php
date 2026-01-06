<?php

namespace App\Controller;

use App\Entity\Inventory;
use App\Entity\User;
use App\Form\InventoryType;
use App\Repository\InventoryRepository;
use App\Service\AccessManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/inventory')]
final class InventoryController extends AbstractController
{
    #[Route(name: 'app_inventory_index', methods: ['GET'])]
    public function index(Request $request, InventoryRepository $inventoryRepository, PaginatorInterface $paginator): Response
    {
        $query = $inventoryRepository->getPaginatedQueryBuilder()->getQuery();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /* page number */
            10 /* limit per page */
        );

        return $this->render('inventory/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_inventory_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $inventory = new Inventory();
        $form = $this->createForm(InventoryType::class, $inventory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inventory->setOwner($this->getUser());
            $inventory->setUpdatedAt(new \DateTime());

            $entityManager->persist($inventory);
            $entityManager->flush();

            return $this->redirectToRoute('app_inventory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inventory/new.html.twig', [
            'inventory' => $inventory,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_inventory_show', methods: ['GET'])]
    public function show(Inventory $inventory): Response
    {
        $this->denyAccessUnlessGranted('VIEW', $inventory);
        return $this->render('inventory/show.html.twig', [
            'inventory' => $inventory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_inventory_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, Inventory $inventory, InventoryRepository $repo, EntityManagerInterface $entityManager): Response
    {
        $inventory = $repo->find($id);
        if (!$inventory) {
            throw $this->createNotFoundException('Inventory not found');
        }

        $this->denyAccessUnlessGranted('EDIT', $inventory);

        $form = $this->createForm(InventoryType::class, $inventory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Inventory has been saved');
                return $this->redirectToRoute('app_inventory_index', ['id' => $id]);

            } catch (OptimisticLockException $e) {
                $this->addFlash('error', 'Someone else modified this inventory. Please reload and try again.');

                $entityManager->refresh($inventory);
            }
        }


        return $this->render('inventory/edit.html.twig', [
            'inventory' => $inventory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_inventory_delete', methods: ['POST'])]
    public function delete(Request $request, Inventory $inventory, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $inventory);
        if ($this->isCsrfTokenValid('delete' . $inventory->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($inventory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_inventory_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/inventory/{id}/share', name: 'app_inventory_share', methods: ['POST'])]
    public function share(Inventory $inventory, Request $request, AccessManager $accessManager, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('MANAGE_ACCESS', $inventory);

        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findOneBy(['username' => $request->request->get('username')]);

        if (!$user) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('app_inventory_show', ['id' => $inventory->getId()]);
        }

        $accessManager->grantAccess($inventory, $user, $request->request->get('permission'));
        $this->addFlash('success', 'Access granted');

        return $this->redirectToRoute('app_inventory_show', ['id' => $inventory->getId()]);
    }

    #[Route('/inventory/{id}/revoke', name: 'app_inventory_revoke', methods: ['POST'])]
    public function revoke(Inventory $inventory, int $userId, AccessManager $accessManager)
    {
        $this->denyAccessUnlessGranted('MANAGE_ACCESS', $inventory);

        $user = $this->getDoctrine()->gerRepository(User::class)->find($userId);
        $accessManager->revokeAccess($inventory, $user);

        $this->addFlash('success', 'Access revoked');
        return $this->redirectToRoute('app_inventory_show', ['id' => $inventory->getId()]);

    }
}
