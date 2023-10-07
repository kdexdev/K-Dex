<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CustomFilterExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('toCamelCase', [$this, 'toCamelCase']),
        ];
    }

    /**
     * Converts a string to camel case.
     *
     * @param string $string The string to be converted.
     * @return string The converted string in camel case.
     */
    public function toCamelCase(string $string): string
    {
        $words = explode(' ', $string);
        $wordsCapitalized = array_map(
            function($s) { return ucfirst(strtolower($s)); }, $words);

        return lcfirst(implode('', $wordsCapitalized));
    }
}
