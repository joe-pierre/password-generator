<?php
namespace App\Service;

class PasswordGenerator
{
    public function generate(int $length, bool $uppercaseLetters = false, bool $digits = false, bool $specialCharacters = false): string
    {
        // Define alphabet
        $lowercaseLettersAlphabet = range("a", "z") ;
        $uppercaseLettersAlphabet = range("A", "Z");
        $digitsAlphabet = range(0, 9);
        $specialCharactersAlphabet = array_merge(
            range('!', '/'), 
            range(':', '@'), 
            range('[', '`'), 
            range('{', '~')
        );
        
        // Final alphabet default to all lowercase letters alphabet
        $finalAlphabet = [$lowercaseLettersAlphabet];

        // Start by adding a random lowercase letter
        $password = [$this->pickRandomItemFromAlphabet($lowercaseLettersAlphabet)];

        // Map constraints to associated alphabets
        $constraintsMapping = [
            [$uppercaseLetters, $uppercaseLettersAlphabet],
            [$digits, $digitsAlphabet],
            [$specialCharacters, $specialCharactersAlphabet],
        ];

        // We make sure that the final password contains at list
        // one {uppercase letter and/or digit and/or special character}
        // base on user's requested constraints.
        // We also grow at the same time the final alphabet
        // with the alphabet  of the requested constraint(s).
        foreach ($constraintsMapping as [$constraintEnabled, $constraintAlphabet]) {
            if ($constraintEnabled) {
                $finalAlphabet[] = $constraintAlphabet;

                $password[] = $this->pickRandomItemFromAlphabet($constraintAlphabet);
            }
        }

        $finalAlphabet = array_merge(...$finalAlphabet);

        $numberOfCharactersRemaining = $length - count($password);
        
        for ($i=0; $i < $numberOfCharactersRemaining; $i++) { 
            $password[] = $this->pickRandomItemFromAlphabet($finalAlphabet);
        }

        // We shuffle the array to make the password characters order unpredictable
        $this->secureShuffle($password);

        $password = implode("", $password);

        return $password;
    }

        
    private function secureShuffle (array $arr): void
    {
        
        // Source: https://github.com/lamansky/secure-shuffle/blob/master/src/functions.php
        $length = count($arr);
        
        for ($i = $length - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
        }

    }

    private function pickRandomItemFromAlphabet(array $alphabet): string
    {
        return $alphabet[random_int(0, count($alphabet) - 1)];
    }
}