<?php

namespace App\Twig\Components;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent()]
final class TopNavbar
{
    public array $links;
    public bool $authenticated;
    public string $username;

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

        // Allow some links to actually be active
        $resolver->setDefined('active');
        $resolver->setAllowedTypes('active', 'boolean');

        // Show different buttons depending on whether the user is logged in
        $resolver->setDefined('authenticated');
        $resolver->setAllowedTypes('authenticated', 'boolean');
        $resolver->setDefault('authenticated', false);

        // Show different buttons depending on whether the user is logged in
        $resolver->setDefined('username');
        $resolver->setAllowedTypes('username', 'string');


        return $resolver->resolve($data);
    }
}
