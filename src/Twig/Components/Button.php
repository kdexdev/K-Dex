<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(exposePublicProps: false)]
final class Button
{
    // General HTML properties
    public string $buttonObject;
    private bool $isDisabled = false;

    // Properties for class styles
    public string $text;
    public string $use;
    public string $style;
    public string $additionalClasses;

    // Properties for <button> HTML tags
    #[ExposeInTemplate('buttonType')]
    public string $type = '';
    #[ExposeInTemplate('formId')]
    public string $form = '';
    #[ExposeInTemplate('buttonName')]
    public string $name = '';
    #[ExposeInTemplate('buttonValue')]
    public string $value = '';

    // Properties for <a> HTML tags
    #[ExposeInTemplate('linkUrl')]
    public string $href = '';
    #[ExposeInTemplate('linkTarget')]
    public string $target = '';
    #[ExposeInTemplate('linkIsDownload')]
    public bool   $download = false;

    #[PreMount()]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();


        /***
         * General HTML properties
         */

        // Check what HTML element is the button
        $resolver->setDefined('buttonObject');
        $resolver->setAllowedValues('buttonObject', ['button', 'a']);
        $resolver->setDefaults(['buttonObject' => 'button']);

        // Check if button has been disabled
        $resolver->setDefined('disabled');
        $resolver->setAllowedTypes('disabled', 'boolean');
        $this->isDisabled = (isset($data['disabled'])
            && $data['disabled'] !== false);


        /***
         * Style properties
         */

        // Make sure there is button text
        $resolver->setDefined('text');
        $resolver->setAllowedTypes('text', 'string');
        $resolver->setRequired('text');

        // Define the purpose of the button
        $resolver->setDefined('use');
        $resolver->setAllowedValues('use', ['primary', 'secondary', 'normal', 'confirm', 'warning', 'danger']);
        $resolver->setDefaults(['use' => 'normal']);

        // Specify the styling
        $resolver->setDefined('style');
        $resolver->setAllowedValues('style', ['solid', 'outline']);
        $resolver->setDefaults(['style' => 'solid']);

        // Add additional Tailwind (or custom) classes
        $resolver->setDefined('additionalClasses');
        $resolver->setAllowedTypes('additionalClasses', 'string');


        /***
         * Button-specific HTML properties
         */
        if (!isset($data['buttonObject'])
         || $data['buttonObject'] === 'button') {
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
        /***
         * Anchor/link-specific HTML properties
         */
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
            "focus:ring-TYPE-light focus:ring-offset-COLOR-200" . ' ' .
            "hover:bg-TYPE-light hover:text-white" . ' ' .
            "active:bg-TYPE-dark active:ring-TYPE-dark active:ring-offset-TYPE-light active:text-white";

        // Set the styling classes
        switch ($this->style) {
            case 'outline':
                $templateClasses .= ' ' .
                    "bg-transparent text-COLOR" . ' ' .
                    "border border-TYPE hover:border-TYPE-light";
                break;

            case 'solid':
            default:
                $templateClasses .= " bg-TYPE text-white";
                break;
        }

        // Set the colors to be used
        switch ($this->use) {
            case 'primary':
                $color = "orange";
                break;
            case 'confirm':
                $this->use = 'success';
            case 'success':
                $color = "green";
                break;
            case 'warning':
                $color = "yellow";
                break;
            case 'danger':
                $color = "red";
                break;

            case 'normal':
                $this->use = 'secondary';
            case 'secondary':
            default:
                $color = "brown";
                break;
        }

        // Add disabled button styles
        if ($this->isDisabled) {
            $templateClasses .= " cursor-not-allowed opacity-70";
        }

        // Add additional button classes
        if (isset($this->additionalClasses)) {
            $templateClasses .= ' ' . $this->additionalClasses;
        }


        $classesComplete = str_replace("COLOR", $color, $templateClasses);
        $classesComplete = str_replace("TYPE", $this->use, $classesComplete);
        return $classesComplete;
    }
}
