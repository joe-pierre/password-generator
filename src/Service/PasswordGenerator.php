<?php
namespace App\Service;

class PasswordGenerator
{
    public function generate(int $length, bool $uppercaseLetters = false, bool $digits = false, bool $specialCharacters = false): string
    {
        $lowercaseLettersAlphabet = range("a", "z") ;
        $uppercaseLettersAlphabet = range("A", "Z");
        $digitsAlphabet = range(0, 9);
        $specialCharactersAlphabet = array_merge(
            range('!', '/'), 
            range(':', '@'), 
            range('[', '`'), 
            range('{', '~')
        );
        
        $finalAlphabet = $lowercaseLettersAlphabet;

        // Add lowercase letter chosed randomly
        $password = [$this->pickRandomAlphabet($lowercaseLettersAlphabet)];
        
        if ($uppercaseLetters) {
            $finalAlphabet = array_merge($finalAlphabet, $uppercaseLettersAlphabet);
            
            // Add uppercase letter chosed randomly
            $password[] = $this->pickRandomAlphabet($uppercaseLettersAlphabet);
        }
        
        if ($digits) {
            $finalAlphabet = array_merge($finalAlphabet, $digitsAlphabet);
            
            // Add digit chosed randomly
            $password[] = $this->pickRandomAlphabet($digitsAlphabet);
        }
        
        if ($specialCharacters) {
            $finalAlphabet = array_merge($finalAlphabet, $specialCharactersAlphabet);
            
            // Add special character letter chosed randomly
            $password[] = $this->pickRandomAlphabet($specialCharactersAlphabet);
        }
        
        $numberOfCharactersRemaining = $length - count($password);
        
        for ($i=0; $i < $numberOfCharactersRemaining; $i++) { 
            $password[] = $this->pickRandomAlphabet($finalAlphabet);
        }

        $this->secureShuffle($password);

        $password = implode("", $password);

        return $password;
    }
        
    // Source: https://github.com/lamansky/secure-shuffle/blob/master/src/functions.php
     private function secureShuffle (array $arr): void {
        $length = count($arr);
        
        for ($i = $length - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
        }

    }

    private function pickRandomAlphabet(array $alphabet): string
    {
        return $alphabet[random_int(0, count($alphabet) - 1)];
    }
}