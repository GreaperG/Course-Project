<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

final class GithubController extends AbstractController
{
    #[Route('/connect/github', name: 'connect_github_start')]
    public function connect(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('github')
            ->redirect(['user:email'], []);
    }

    #[Route('/connect/github/check', name: 'connect_github_user')]
    public function connectCheck(
        ClientRegistry $clientRegistry,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): RedirectResponse
    {
        try {
            $client = $clientRegistry->getClient('github');
            $githibUser = $client->fetchUser();


            $userRepository = $em->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $githibUser->getEmail()]);


            if (!$user) {

                $user = new User();
                $user->setEmail($githibUser->getEmail());
                $user->setUsername($githibUser->getNickname());
                $user->setPassword($passwordHasher->hashPassword($user, bin2hex(random_bytes(16))));
                $user->setRoles(['ROLE_USER']);

                $em->persist($user);
                $em->flush();
            }

            $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
            $this->container->get('security.token_storage')->setToken($token);

            $this->addFlash('success', 'Successfully logged in with Github!');

            return $this->redirectToRoute('app_home_page');

        }catch (\Exception $exception){
            $this->addFlash('danger', 'Github login failed' . $exception->getMessage());
            return $this->redirectToRoute('app_register');
        }

    }
}
