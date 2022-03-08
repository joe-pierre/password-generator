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
        
        $finalAlphabet = [$lowercaseLettersAlphabet];

        // Add lowercase letter chosed randomly
        $password = [$this->pickRandomItemFromAlphabet($lowercaseLettersAlphabet)];

        $constraintsMapping = [
            [$uppercaseLetters, $uppercaseLettersAlphabet],
            [$digits, $digitsAlphabet],
            [$specialCharacters, $specialCharactersAlphabet],
        ];

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

        $this->secureShuffle($password);

        $password = implode("", $password);

        return $password;
    }

        
    // Source: https://github.com/lamansky/secure-shuffle/blob/master/src/functions.php
    private function secureShuffle (array $arr): void
    {
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