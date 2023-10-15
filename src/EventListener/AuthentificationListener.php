<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class AuthenticationListener
{
    protected $entityManager;

    public function __construct(EntityManager $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * Updates the database for the latest time the user has been active on the database
     *
     * @param InteractiveLoginEvent $event The event object.
     * @return void
     */
    #[AsEventListener(
        event: InteractiveLoginEvent::class,
        method: 'onInteractiveLogin'
    )]
    public function onInteractiveLogin(InteractiveLoginEvent $event): void {
        $user = $event->getAuthenticationToken()->getUser();
        if ($user instanceof User) {
            // Update the last time user visited the website
            $user->setLastVisitedAt(new \DateTimeImmutable());

            // Update the database entry
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
}
