<?php

namespace App\Controller;

use App\Service\PasswordGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('pages/home.html.twig');
    }

    #[Route('/generate-password', name: 'app_generate_password')]
    public function generatePassword(Request $request, PasswordGenerator $passwordGenerator): Response
    {

        $length = $request->query->get('length');

        $password = $passwordGenerator->generate(
            length: min(max($request->query->get('length'), 8), 60),
            uppercaseLetters: $request->query->getBoolean('uppercaseLetters'),
            digits: $request->query->getBoolean('digits'),
            specialCharacters: $request->query->getBoolean('specialCharacters'),
        );
        
        return $this->render('pages/generate_password.html.twig', compact('password'));
    }

    
}