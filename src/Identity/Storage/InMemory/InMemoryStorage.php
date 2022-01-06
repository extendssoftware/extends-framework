<?php
declare(strict_types=1);

namespace ExtendsFramework\Identity\Storage\InMemory;

use ExtendsFramework\Identity\IdentityInterface;
use ExtendsFramework\Identity\Storage\StorageInterface;

class InMemoryStorage implements StorageInterface
{
    /**
     * Temporary stored identity.
     *
     * @var IdentityInterface|null
     */
    private ?IdentityInterface $identity = null;

    /**
     * @inheritDoc
     */
    public function getIdentity(): ?IdentityInterface
    {
        return $this->identity;
    }

    /**
     * @inheritDoc
     */
    public function setIdentity(IdentityInterface $identity): StorageInterface
    {
        $this->identity = $identity;

        return $this;
    }
}
