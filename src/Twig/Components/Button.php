<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[AsTwigComponent(exposePublicProps: false)]
final class Button
{
    // Properties for class styles
    public string $text;
    public string $use;
    public string $style;

    private bool $isDisabled = false;

    public string $buttonObject;

    // Properties for <button> HTML tags
    public string $type;
    public string $form;
    public string $name;
    public string $value;

    // Properties for <a> HTML tags
    public string $href;
    public string $target;
    public bool   $download;

    #[PreMount()]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();


        /***
         * Style properties
         */

        // Make sure there is button text
        $resolver->setDefined('text');
        $resolver->setAllowedTypes('text', 'string');
        $resolver->setRequired('text');

        // Define the purpose of the button
        $resolver->setDefined('use');
        $resolver->setAllowedValues('use', ['normal', 'confirm', 'warning', 'danger']);
        $resolver->setDefaults(['use' => 'normal']);

        // Specify the styling
        $resolver->setDefined('style');
        $resolver->setAllowedValues('style', ['solid', 'outline']);
        $resolver->setDefaults(['style' => 'solid']);


        /***
         * HTML properties
         */

        // Check if button has been disabled
        $resolver->setDefined('disabled');
        $resolver->setAllowedTypes('disabled', 'boolean');
        $this->isDisabled = (isset($data['disabled'])
            && $data['disabled'] !== false);

        // Check what HTML element is the button
        $resolver->setDefined('buttonObject');
        $resolver->setAllowedValues('buttonObject', ['button', 'a']);
        $resolver->setDefaults(['buttonObject' => 'button']);

        // Attribute split between button and anchor
        if (!isset($data['buttonObject']) || $data['buttonObject'] === 'button') {
            // Define the HTML usecase type
            $resolver->setDefined('type');
            $resolver->setDefaults(['type' => 'button']);
            $resolver->setAllowedValues('type', ['button', 'submit', 'reset']);

            // Define the form id that the button belongs to
            $resolver->setDefined('form');
            $resolver->setAllowedTypes('form', 'string');

            // Define the button name, used in forms
            $resolver->setDefined('name');
            $resolver->setAllowedTypes('name', 'string');

            // Define the button value, used in forms
            $resolver->setDefined('value');
            $resolver->setAllowedTypes('value', 'string');
        }
        elseif ($data['buttonObject'] === 'a') {
            // Require the link that needs to be referred to
            $resolver->setDefined('href');
            $resolver->setRequired('href');
            $resolver->setAllowedTypes('href', 'string');

            // Define where the page needs to be opened
            $resolver->setDefined('target');
            $resolver->setDefaults(['target' => '_blank']);
            $resolver->setAllowedValues('target', ['_blank', '_self', '_parent', '_top']);

            // Define if there is a download
            $resolver->setDefined('download');
            $resolver->setAllowedTypes('download', 'boolean');
        }


        return $resolver->resolve($data);
    }

    public function getTypeClasses(): string
    {
        $templateClasses =
            "focus:ring-COLOR-500 focus:ring-offset-COLOR-200" . ' ' .
            "hover:bg-COLOR-500 hover:text-white" . ' ' .
            "active:bg-COLOR-800 active:ring-COLOR-800 active:ring-offset-COLOR-500 active:text-white";

        // Set the styling classes
        switch ($this->style) {
            case 'outline':
                $templateClasses .= ' ' .
                    "bg-transparent text-COLOR" . ' ' .
                    "border border-COLOR-700 hover:border-COLOR-500";
                break;

            case 'solid':
            default:
                $templateClasses .= ' ' .
                    "bg-COLOR-700 text-white";
                break;
        }

        // Set the colors to be used
        switch ($this->use) {
            case 'confirm':
                $color = "green";
                break;
            case 'warning':
                $color = "orange";
                break;
            case 'danger':
                $color = "red";
                break;

            case 'normal':
            default:
                $color = "grey";
                break;
        }

        // Add disabled button styles
        if ($this->isDisabled) {
            $templateClasses .= ' ' .
                "cursor-not-allowed opacity-70";
        }

        $classesComplete = str_replace("COLOR", $color, $templateClasses);
        return $classesComplete;
    }
}
