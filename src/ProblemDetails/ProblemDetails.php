<?php
declare(strict_types=1);

namespace ExtendsFramework\ProblemDetails;

class ProblemDetails implements ProblemDetailsInterface
{
    /**
     * Type.
     *
     * @var string
     */
    private string $type;

    /**
     * Title.
     *
     * @var string
     */
    private string $title;

    /**
     * Detail.
     *
     * @var string
     */
    private string $detail;

    /**
     * HTTP status code.
     *
     * @var int
     */
    private int $status;

    /**
     * Instance.
     *
     * @var string|null
     */
    private ?string $instance;

    /**
     * Additional members.
     *
     * @var array|null
     */
    private ?array $additional;

    /**
     * Problem constructor.
     *
     * @param string      $type
     * @param string      $title
     * @param string      $detail
     * @param int         $status
     * @param string|null $instance
     * @param array|null  $additional
     */
    public function __construct(
        string $type,
        string $title,
        string $detail,
        int $status,
        string $instance = null,
        array $additional = null
    ) {
        $this->type = $type;
        $this->title = $title;
        $this->detail = $detail;
        $this->status = $status;
        $this->instance = $instance;
        $this->additional = $additional;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @inheritDoc
     */
    public function getDetail(): string
    {
        return $this->detail;
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function getInstance(): ?string
    {
        return $this->instance;
    }

    /**
     * @inheritDoc
     */
    public function getAdditional(): ?array
    {
        return $this->additional;
    }
}
