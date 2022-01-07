<?php
declare(strict_types=1);

namespace ExtendsFramework\Router;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Router\Exception\GroupRouteExpected;
use ExtendsFramework\Router\Exception\RouteNotFound;
use ExtendsFramework\Router\Route\Method\Exception\MethodNotAllowed;
use ExtendsFramework\Router\Route\Method\MethodRoute;
use ExtendsFramework\Router\Route\RouteInterface;
use ExtendsFramework\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class RoutesTest extends TestCase
{
    /**
     * Dummy class with Routes trait.
     *
     * @var object
     */
    private $routes;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $this->routes = new class {
            use Routes;

            /**
             * @param RequestInterface $request
             *
             * @return RouteMatchInterface|null
             */
            public function match(RequestInterface $request): ?RouteMatchInterface
            {
                return $this->matchRoutes($request, 0);
            }

            /**
             * @param string    $name
             * @param bool|null $groupRoute
             *
             * @return RouteInterface
             * @throws GroupRouteExpected
             * @throws RouteNotFound
             */
            public function route(string $name, bool $groupRoute = null): RouteInterface
            {
                return $this->getRoute($name, $groupRoute);
            }
        };
    }

    /**
     * Match.
     *
     * Test that route will be matched and returned.
     *
     * @covers \ExtendsFramework\Router\Routes::addRoute()
     * @covers \ExtendsFramework\Router\Routes::matchRoutes()
     */
    public function testMatch(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);

        $route1 = $this->createMock(RouteInterface::class);
        $route1
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn(null);

        $route2 = $this->createMock(RouteInterface::class);
        $route2
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn($match);

        /**
         * @var RequestInterface $request
         * @var RouteInterface   $route1
         * @var RouteInterface   $route2
         */
        $matched = $this->routes
            ->addRoute($route1, 'route1')
            ->addRoute($route2, 'route2')
            ->match($request);

        $this->assertSame($match, $matched);
    }

    /**
     * Not match.
     *
     * Test that no route will be matched and null will be returned.
     *
     * @covers \ExtendsFramework\Router\Routes::addRoute()
     * @covers \ExtendsFramework\Router\Routes::matchRoutes()
     */
    public function testNoMatch(): void
    {
        $request = $this->createMock(RequestInterface::class);

        /**
         * @var RequestInterface $request
         */
        $this->assertNull($this->routes->match($request));
    }

    /**
     * Method not allowed.
     *
     * Test that none of the method routes is allowed and exception will be thrown with allowed methods.
     *
     * @covers \ExtendsFramework\Router\Routes::addRoute()
     * @covers \ExtendsFramework\Router\Routes::matchRoutes()
     */
    public function testMethodNotAllowed(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $route1 = $this->createMock(MethodRoute::class);
        $route1
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willThrowException(new MethodNotAllowed('GET', ['POST', 'PUT']));

        $route2 = $this->createMock(MethodRoute::class);
        $route2
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willThrowException(new MethodNotAllowed('GET', ['DELETE']));

        /**
         * @var RouteInterface   $route1
         * @var RouteInterface   $route2
         * @var RequestInterface $request
         */
        $this->routes
            ->addRoute($route1, 'route1')
            ->addRoute($route2, 'route2');

        try {
            $this->routes->match($request);
        } catch (MethodNotAllowed $exception) {
            $this->assertSame(['POST', 'PUT', 'DELETE'], $exception->getAllowedMethods());
        }
    }

    /**
     * Method allowed.
     *
     * Test that second method route is allowed and first exception not will be thrown.
     *
     * @covers \ExtendsFramework\Router\Routes::addRoute()
     * @covers \ExtendsFramework\Router\Routes::matchRoutes()
     */
    public function testMethodAllowed(): void
    {
        $match = $this->createMock(RouteMatchInterface::class);

        $request = $this->createMock(RequestInterface::class);

        $route1 = $this->createMock(MethodRoute::class);
        $route1
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willThrowException(new MethodNotAllowed('GET', ['POST', 'PUT']));

        $route2 = $this->createMock(MethodRoute::class);
        $route2
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn($match);

        /**
         * @var RouteInterface   $route1
         * @var RouteInterface   $route2
         * @var RequestInterface $request
         */
        $matched = $this->routes
            ->addRoute($route1, 'route1')
            ->addRoute($route2, 'route2')
            ->match($request);

        $this->assertSame($match, $matched);
    }

    /**
     * Get route.
     *
     * Test that route with name will be returned.
     *
     * @covers \ExtendsFramework\Router\Routes::getRoute()
     */
    public function testGetRoute(): void
    {
        $route = $this->createMock(RouteInterface::class);

        /**
         * @var RouteInterface $route
         */
        $found = $this->routes
            ->addRoute($route, 'foo')
            ->route('foo', false);

        $this->assertSame($route, $found);
    }

    /**
     * Route not found.
     *
     * Test that route can not be found and an exception will be thrown.
     *
     * @covers \ExtendsFramework\Router\Routes::getRoute()
     * @covers \ExtendsFramework\Router\Exception\RouteNotFound::__construct()
     *
     */
    public function testRouteNotFound(): void
    {
        $this->expectException(RouteNotFound::class);
        $this->expectExceptionMessage('Route for name "foo" can not be found.');

        $this->routes->route('foo');
    }

    /**
     * Group route expected.
     *
     * Test that and exception will be thrown when group route is expected but not returned.
     *
     * @covers \ExtendsFramework\Router\Routes::getRoute()
     * @covers \ExtendsFramework\Router\Exception\GroupRouteExpected::__construct()
     */
    public function testGroupRouteExpected(): void
    {
        $this->expectException(GroupRouteExpected::class);
        $this->expectExceptionMessageMatches(
            '/^A group route was expected, but an instance of "([^"]+)" was returned.$/'
        );

        $route = $this->createMock(RouteInterface::class);

        /**
         * @var RouteInterface $route
         */
        $this->routes
            ->addRoute($route, 'foo')
            ->route('foo', true);
    }
}
