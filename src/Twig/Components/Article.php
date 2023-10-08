<?php

namespace App\Twig\Components;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent()]
final class Article
{
    public string $title;
    public string $content;
    public array $image;

    #[PreMount()]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();


        // Require an article to have a title
        $resolver->setDefined('title');
        $resolver->setRequired('title');
        $resolver->setAllowedTypes('title', 'string');

        // Require an article to have some content
        $resolver->setDefined('content');
        $resolver->setRequired('content');
        $resolver->setAllowedTypes('content', 'string');

        // Require an article to have a banner image
        $resolver->setDefined('image');
        $resolver->setRequired('image');
        $resolver->setAllowedTypes('image', 'array');
        $resolver->setAllowedValues('image', function ($value) {
            // The banner image has to have a URL
            if (
                !isset($value['url'])
                || !is_string($value['url'])
            ) {
                return false;
            }

            // The banner has to have an alt attribute
            if (
                !isset($value['alt'])
                || !is_string($value['alt'])
            ) {
                return false;
            }

            // Check if the (optional) image position is defined
            if (
                isset($value['position'])
                && (
                    !is_string($value['position'])
                    || !in_array($value['position'], ['left', 'right'])
                )
            ) {
                return false;
            }

            // Check if there are (optional) custom or Tailwind classes set
            if (
                isset($value['classes'])
                && !is_string($value['classes'])
            ) {
                return false;
            }

            return true;
        });


        return $resolver->resolve($data);
    }
}
