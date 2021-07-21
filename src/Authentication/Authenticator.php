<?php
declare(strict_types=1);

namespace ExtendsFramework\Authentication;

use ExtendsFramework\Authentication\Header\HeaderInterface;
use ExtendsFramework\Authentication\Realm\RealmInterface;

class Authenticator implements AuthenticatorInterface
{
    /**
     * Realms to use for authentication.
     *
     * @var RealmInterface[]
     */
    private array $realms = [];

    /**
     * @inheritDoc
     */
    public function authenticate(HeaderInterface $header): ?AuthenticationInfoInterface
    {
        foreach ($this->realms as $realm) {
            if ($realm->canAuthenticate($header)) {
                $info = $realm->getAuthenticationInfo($header);
                if ($info instanceof AuthenticationInfoInterface) {
                    return $info;
                }
            }
        }

        return null;
    }

    /**
     * Add realm to authenticator.
     *
     * @param RealmInterface $realm
     *
     * @return Authenticator
     */
    public function addRealm(RealmInterface $realm): Authenticator
    {
        $this->realms[] = $realm;

        return $this;
    }
}
