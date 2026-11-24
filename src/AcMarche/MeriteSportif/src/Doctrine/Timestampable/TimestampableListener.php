<?php

namespace AcMarche\MeriteSportif\Doctrine\Timestampable;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::loadClassMetadata)]
final class TimestampableListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();
        if ($classMetadata->reflClass === null || $classMetadata->isMappedSuperclass) {
            return;
        }

        if (!is_a($classMetadata->reflClass->getName(), TimestampableInterface::class, true)) {
            return;
        }

        $classMetadata->addLifecycleCallback('updateTimestamps', Events::prePersist);
        $classMetadata->addLifecycleCallback('updateTimestamps', Events::preUpdate);

        foreach (['createdAt', 'updatedAt'] as $field) {
            if (!$classMetadata->hasField($field)) {
                $classMetadata->mapField([
                    'fieldName' => $field,
                    'type' => Types::DATETIME_MUTABLE,
                    'nullable' => true,
                ]);
            }
        }
    }
}
