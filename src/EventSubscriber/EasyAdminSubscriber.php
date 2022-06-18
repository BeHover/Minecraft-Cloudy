<?php
namespace App\EventSubscriber;

use App\Entity\Main\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['hashUserPasswordBeforePersist'],
            BeforeEntityUpdatedEvent::class => ['hashUserPasswordBeforeUpdate'],
        ];
    }

    public function hashUserPasswordBeforePersist(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $entity,
            $entity->getPassword()
        );

        $entity->setPassword($hashedPassword);
    }

    public function hashUserPasswordBeforeUpdate(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $entity,
            $entity->getPassword()
        );

        $entity->setPassword($hashedPassword);
    }
}