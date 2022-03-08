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
    public function generatePassword(Request $request): Response
    {
       
        
        $passwordGenerator = new PasswordGenerator;

        $password = $passwordGenerator->generate(
            uppercaseLetters: $request->query->getBoolean('uppercaseLetters'),
            length: $request->query->get('length'),
            specialCharacters: $request->query->getBoolean('specialCharacters'),
            digits: $request->query->getBoolean('digits'),
        );
        
        return $this->render('pages/generate_password.html.twig', compact('password'));
    }

    
}