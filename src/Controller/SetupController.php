<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
final class SetupController extends AbstractController
{
    #[Route('/setup', name: 'app_setup')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        if($request->query->get('key') !== 'mys123') {
            throw new AccessDeniedException('Wrong key');
        }

        $process = new Process(['php', 'bin/console', 'doctrine:migrations:migrate', '--no-interaction']);
        $process->run();

        $output = $process->getOutput();
        $error = $process->getErrorOutput();
        $exitCode = $process->getExitCode();

        return new Response(
            'Exit Code: ' . $exitCode . '<br><br>' .
            'Output: <pre>' . $output . '</pre><br>' .
            'Errors: <pre>' . $error . '</pre>'
    }
}
