<?php

namespace App\Controller;

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
        $length = $request->query->get('length');
        $uppercaseLetters = $request->query->getBoolean('uppercaseLetters');
        $digits = $request->query->getBoolean('digits');
        $specialCharacters = $request->query->getBoolean('specialCharacters');
        
        $lowercaseLettersSet = range("a", "z") ;
        $uppercaseLettersSet = range("A", "Z");
        $digitsSet = range(0, 9);
        $specialCharactersSet = ['!', '"', '#', '$', '%', '&', '(', ')', '*', '+', '-', '/', ':', ';', '<', '=', '>', '?', '@', '[', ']', '^', '_', '{', '|', '}', '~'];
        
        $characters = $lowercaseLettersSet;

        $password = "";

        $password .= $lowercaseLettersSet[random_int(0, count($lowercaseLettersSet) - 1)];
        
        if ($uppercaseLetters) {
            $characters = array_merge($characters, $uppercaseLettersSet);
            
            $password .= $uppercaseLettersSet[random_int(0, count($uppercaseLettersSet) - 1)];
        }
        
        if ($digits) {
            $characters = array_merge($characters, $digitsSet);
            
            $password .= $digitsSet[random_int(0, count($digitsSet) - 1)];
        }
        
        if ($specialCharacters) {
            $characters = array_merge($characters, $specialCharactersSet);

            $password .= $specialCharactersSet[random_int(0, count($specialCharactersSet) - 1)];
        }
        
        $numberOfCharactersRemaining = $length - mb_strlen($password);
        
        for ($i=0; $i < $numberOfCharactersRemaining; $i++) { 
            $password .= $characters[random_int(0, count($characters) - 1)];
        }

        $password = str_split($password);
        
        $this->secureShuffle($password);

        $password = implode(",", $password);
        
        return $this->render('pages/generate_password.html.twig', compact('password'));
    }

    // Source: https://github.com/lamansky/secure-shuffle/blob/master/src/functions.php
    private function secureShuffle (array $arr): void {
        $length = count($arr);
        
        for ($i = $length - 1; $i > 0; $i) {
            $j = random_int(0, $i);
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
        }

    }
}