<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[AsTwigComponent()]
final class Footer
{
    public array $links;

    #[PreMount()]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();


        // Require links in the navbar
        $resolver->setDefined('links');
        $resolver->setRequired('links');
        $resolver->setAllowedTypes('links', 'array');
        $resolver->setAllowedValues('links', function ($value) {
            foreach ($value as $link) {
                // Check that values have been passed
                if (!is_array($link)
                 || !array_key_exists('name', $link)
                 || !array_key_exists('href', $link)) {
                    return false;
                }
                // Check if values have the correct types
                if (!is_string($link['name'])
                 || !is_string($link['href'])) {
                    return false;
                }
            }
            return true;
        });


        return $resolver->resolve($data);
    }
}
