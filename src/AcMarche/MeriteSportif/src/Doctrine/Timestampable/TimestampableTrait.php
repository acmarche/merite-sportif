<?php

namespace AcMarche\MeriteSportif\Doctrine\Timestampable;

use DateTime;
use DateTimeInterface;

/**
 * Replaces knplabs/doctrine-behaviors TimestampableTrait.
 * Fields are mapped by TimestampableListener for classes implementing TimestampableInterface.
 */
trait TimestampableTrait
{
    protected ?DateTimeInterface $createdAt = null;

    protected ?DateTimeInterface $updatedAt = null;

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function updateTimestamps(): void
    {
        $dateTime = new DateTime();

        if ($this->createdAt === null) {
            $this->createdAt = $dateTime;
        }

        $this->updatedAt = $dateTime;
    }
}
