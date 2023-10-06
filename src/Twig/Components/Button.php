<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[AsTwigComponent(exposePublicProps: false)]
final class Button
{
    public string $text;
    public string $type;
    public string $style;

    private bool $isDisabled = false;

    #[PreMount()]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();

        // Make sure there is button text
        $resolver->setRequired('text');
        $resolver->setAllowedTypes('text', 'string');

        // Define the purpose of the button
        $resolver->setDefaults(['type' => 'normal']);
        $resolver->setAllowedValues('type', ['normal', 'confirm', 'warning', 'danger']);

        // Specify the styling
        $resolver->setDefaults(['style' => 'solid']);
        $resolver->setAllowedValues('style', ['solid', 'outline']);

        // Check if button has been disabled
        $resolver->setDefined('disabled');
        $resolver->setAllowedTypes('disabled', 'boolean');
        $this->isDisabled = (isset($data['disabled'])
            && $data['disabled'] !== false);

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
        switch ($this->type) {
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
