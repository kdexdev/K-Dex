<?php

namespace App\Twig\Components;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent()]
final class Alert
{
    public string $type = '';
    public ?string $icon;
    public string $title = '';
    public string $message = '';
    public string $style = '';
    public array $messageList = [];

    #[PreMount()]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();


        // Define the purpose of the alert
        $resolver->setDefined('type');
        $resolver->setRequired('type');
        $resolver->setAllowedValues('type', ['normal', 'info', 'success', 'warning', 'danger']);

        // Add an (optional) icon
        $resolver->setDefined('icon');
        $resolver->setAllowedTypes('icon', ['string']);

        // Require an alert to have a title
        $resolver->setDefined('title');
        $resolver->setRequired('title');
        $resolver->setAllowedTypes('title', 'string');

        // Require an alert to have some kind of a message explaining itself
        $resolver->setDefined('message');
        $resolver->setRequired('message');
        $resolver->setAllowedTypes('message', 'string');

        // Add the option for there being a list of messages
        $resolver->setDefined('messageList');
        $resolver->setAllowedTypes('messageList', 'array');

        // Allow slight future modifications of the style
        $resolver->setDefined('style');
        $resolver->setAllowedTypes('style', 'string');


        return $resolver->resolve($data);
    }

    public function getAlertStyleClasses(): string
    {
        $alertStyles =
            "bg-TYPE-light border-TYPE-dark text-TYPE-dark";

        if ($this->type === 'normal') {
            $alertStyles = str_replace("TYPE", "secondary", $alertStyles);
        }
        else {
            $alertStyles = str_replace("TYPE", $this->type, $alertStyles);
        }

        return $alertStyles . ' ' . $this->style;
    }
}
