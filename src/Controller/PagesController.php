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
        return $this->render('pages/home.html.twig',[
            'password_default_length' => $this->getParameter('app.password_default_length'),
            'password_min_length' => $this->getParameter('app.password_min_length'),
            'password_max_length' => $this->getParameter('app.password_max_length'),
        ]);
    }

    #[Route('/generate-password', name: 'app_generate_password')]
    public function generatePassword(Request $request, PasswordGenerator $passwordGenerator): Response
    {
        // We make sure that the password length is always
        // at minimum {app.password_min_length}
        // and at maximum {app.password_max_length}.
        $length = min(max($request->query->get('length'), $this->getParameter('app.password_min_length')), $this->getParameter('app.password_max_length'));
        
        $password = $passwordGenerator->generate(
            length: $length,
            uppercaseLetters: $request->query->getBoolean('uppercaseLetters'),
            digits: $request->query->getBoolean('digits'),
            specialCharacters: $request->query->getBoolean('specialCharacters'),
        );
        
        return $this->render('pages/generate_password.html.twig', compact('password'));
    }

    
}