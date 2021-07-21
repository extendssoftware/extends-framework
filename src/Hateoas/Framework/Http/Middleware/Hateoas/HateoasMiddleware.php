<?php
declare(strict_types=1);

namespace ExtendsFramework\Hateoas\Framework\Http\Middleware\Hateoas;

use ExtendsFramework\Authorization\AuthorizerInterface;
use ExtendsFramework\Hateoas\Builder\BuilderInterface;
use ExtendsFramework\Hateoas\Builder\Exception\AttributeNotFound;
use ExtendsFramework\Hateoas\Builder\Exception\LinkNotEmbeddable;
use ExtendsFramework\Hateoas\Builder\Exception\LinkNotFound;
use ExtendsFramework\Hateoas\Expander\ExpanderInterface;
use ExtendsFramework\Hateoas\Framework\ProblemDetails\AttributeNotFoundProblemDetails;
use ExtendsFramework\Hateoas\Framework\ProblemDetails\LinkNotEmbeddableProblemDetails;
use ExtendsFramework\Hateoas\Framework\ProblemDetails\LinkNotFoundProblemDetails;
use ExtendsFramework\Hateoas\Serializer\SerializerInterface;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\Response;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Security\SecurityServiceInterface;

class HateoasMiddleware implements MiddlewareInterface
{
    /**
     * Authorizer.
     *
     * @var AuthorizerInterface
     */
    private AuthorizerInterface $authorizer;

    /**
     * Resource expander.
     *
     * @var ExpanderInterface
     */
    private ExpanderInterface $expander;

    /**
     * Serializer.
     *
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var SecurityServiceInterface
     */
    private SecurityServiceInterface $securityService;

    /**
     * HateoasMiddleware constructor.
     *
     * @param AuthorizerInterface      $authorizer
     * @param ExpanderInterface        $expander
     * @param SerializerInterface      $serializer
     * @param SecurityServiceInterface $securityService
     */
    public function __construct(
        AuthorizerInterface $authorizer,
        ExpanderInterface $expander,
        SerializerInterface $serializer,
        SecurityServiceInterface $securityService
    )
    {
        $this->authorizer = $authorizer;
        $this->expander = $expander;
        $this->serializer = $serializer;
        $this->securityService = $securityService;
    }

    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $clonedRequest = clone $request;

        $uri = $request->getUri();
        $query = $uri->getQuery();

        $expand = $query['expand'] ?? null;
        $project = $query['project'] ?? null;

        unset($query['expand'], $query['project']);
        $request = $request->withUri($uri->withQuery($query));

        $response = $chain->proceed($request);
        $builder = $response->getBody();
        if ($builder instanceof BuilderInterface) {
            try {
                $serialized = $this
                    ->serializer
                    ->serialize(
                        $builder
                            ->setExpander($this->expander)
                            ->setAuthorizer($this->authorizer)
                            ->setIdentity($this->securityService->getIdentity())
                            ->setToExpand($expand)
                            ->setToProject($project)
                            ->build()
                    );

                $response = $response
                    ->withHeader('Content-Type', 'application/hal+json')
                    ->withHeader('Content-Length', (string)strlen($serialized))
                    ->withBody($serialized);
            } catch (LinkNotFound $exception) {
                return (new Response())->withBody(
                    new LinkNotFoundProblemDetails($clonedRequest, $exception)
                );
            } catch (LinkNotEmbeddable $exception) {
                return (new Response())->withBody(
                    new LinkNotEmbeddableProblemDetails($clonedRequest, $exception)
                );
            } catch (AttributeNotFound $exception) {
                return (new Response())->withBody(
                    new AttributeNotFoundProblemDetails($clonedRequest, $exception)
                );
            }
        }

        return $response;
    }
}
