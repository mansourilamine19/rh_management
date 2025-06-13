<?php

namespace App\EventListener\Doctrine;

/**
 * Description of PrePersistListener
 *
 * @author Lamine Mansouri <mansourilamine19@gmail.com>
 */
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::prePersist, connection: 'default')]
#[AsDoctrineListener(event: Events::preUpdate, connection: 'default')]
#[AsDoctrineListener(event: Events::postPersist, connection: 'default')]
#[AsDoctrineListener(event: Events::postUpdate, connection: 'default')]
class PrePersistListener {

    public function __construct(
            private Security $security
    ) {
        
    }

    public function prePersist(PrePersistEventArgs $args): void {
        $entity = $args->getObject();

        if (method_exists($entity, 'getCreatedBy')) {
            if (!empty($this->security->getUser()))
                $entity->setCreatedBy($this->security->getUser());
            $entity->setCreatedAt(new \DateTimeImmutable());
        }
    }

    public function preUpdate(PreUpdateEventArgs $args): void {
        $entity = $args->getObject();

        if (method_exists($entity, 'getUpdatedBy')) {
            if (!empty($this->security->getUser()))
                $entity->setUpdatedBy($this->security->getUser());
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }
    }

    public function postPersist(PostPersistEventArgs $args): void {
        
    }

    public function postUpdate(PostUpdateEventArgs $args): void {
        
    }
}
