<?php
namespace App\Controller;

use App\Form\SalesforceExportType;
use App\Service\SalesforceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_user_profile')]
    public function profile(): Response
    {
        $user = $this->getUser();
        
        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/export-to-salesforce', name: 'app_user_export_salesforce')]
    public function exportToSalesforce(Request $request, SalesforceService $salesforceService): Response
    {
            /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $form = $this->createForm(SalesforceExportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            try {
                $result = $salesforceService->createAccountAndContact(
                    $data['company'],
                    $data['phone'],
                    $data['FirstName'],
                    $data['LastName'],
                    $user->getEmail(),
                    $data['website'] ?? null,
                    $data['industry'] ?? null
                );


                $this->addFlash('success', 'Successfully exported to Salesforce!');
                return $this->redirectToRoute('app_user_profile');

            } catch (\Exception $e) {
                $this->addFlash('error', 'Failed to export: ' . $e->getMessage());
            }
        }

        return $this->render('user/export_salesforce.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}