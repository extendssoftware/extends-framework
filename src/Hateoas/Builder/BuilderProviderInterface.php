<?php
declare(strict_types=1);

namespace ExtendsFramework\Hateoas\Builder;

use ExtendsFramework\Router\RouterInterface;

interface BuilderProviderInterface
{
    /**
     * Get builder.
     *
     * @param RouterInterface $router
     *
     * @return BuilderInterface
     */
    public function getBuilder(RouterInterface $router): BuilderInterface;
}
