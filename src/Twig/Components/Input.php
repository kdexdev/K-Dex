<?php

namespace App\Twig\Components;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent()]
final class Input
{
    // Input stying properties
    public string $icon;
    // Global HTML properties
    public string $type;
    public string $formId;
    public string $name;
    public string $placeholder;
    public string $value;
    // Global verification properties
    public bool $required;
    public int $maxVal;
    public int $minVal;
    public int $maxLen;
    public int $minLen;
    public string $pattern;
    public bool $autocomplete;
    public bool $disabled;
    // Input type-specific properties
    public bool $checked;
    public string $filetype;

    #[PreMount()]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();

        /***
         * Input stying properties
         */
        $resolver->setDefined('icon');
        $resolver->setAllowedTypes('icon', 'string');

        /***
         * Global HTML properties
         */

        // Define the input type
        $resolver->setDefined('type');
        $resolver->isRequired('type');
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedValues('type', [
            'text', 'password', 'email', 'url',
            'number', 'date',
            'checkbox', 'radio',
            'file', 'hidden'
        ]);

        // Define the form ID
        $resolver->setDefined('formId');
        $resolver->isRequired('formId');
        $resolver->setAllowedTypes('formId', 'string');

        // Define the input name
        $resolver->setDefined('name');
        $resolver->isRequired('name');
        $resolver->setAllowedTypes('name', 'string');

        // Define the input value
        $resolver->setDefined('value');
        $resolver->setAllowedTypes('value', 'string');

        /***
         * Global verification properties
         */

        // Check if input is required
        $resolver->setDefined('required');
        $resolver->setAllowedTypes('required', 'boolean');
        $resolver->setDefault('required', false);

        // Define the maximum value
        $resolver->setDefined('maxVal');
        $resolver->setAllowedTypes('maxVal', 'number');

        // Define the minimum value
        $resolver->setDefined('minVal');
        $resolver->setAllowedTypes('minVal', 'number');

        // Define the maximum length
        $resolver->setDefined('maxLen');
        $resolver->setAllowedTypes('maxLen', 'number');

        // Define the minimum length
        $resolver->setDefined('minLen');
        $resolver->setAllowedTypes('minLen', 'number');

        // Define the input pattern
        $resolver->setDefined('pattern');
        $resolver->setAllowedTypes('pattern', 'string');

        // Define the input autocomplete behavior
        $resolver->setDefined('autocomplete');
        $resolver->setAllowedTypes('autocomplete', 'boolean');

        // Check if input is disabled
        $resolver->setDefined('disabled');
        $resolver->setAllowedTypes('disabled', 'boolean');
        $resolver->setDefault('disabled', false);

        if ($data['type'] !== "hidden") {
            // Define the input placeholder
            $resolver->setDefined('placeholder');
            $resolver->setRequired('placeholder');
            $resolver->setAllowedTypes('placeholder', 'string');
        }


        return $resolver->resolve($data);
    }
}
