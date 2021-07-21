<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Writer;

use ExtendsFramework\Logger\Decorator\DecoratorInterface;
use ExtendsFramework\Logger\Filter\FilterInterface;
use ExtendsFramework\Logger\LogInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;

abstract class AbstractWriter implements WriterInterface, StaticFactoryInterface
{
    /**
     * Filters.
     *
     * @var FilterInterface[]
     */
    private array $filters = [];

    /**
     * Decorators.
     *
     * @var DecoratorInterface[]
     */
    private array $decorators = [];

    /**
     * Add filter.
     *
     * @param FilterInterface $filter
     *
     * @return AbstractWriter
     */
    public function addFilter(FilterInterface $filter): AbstractWriter
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Add decorator.
     *
     * @param DecoratorInterface $decorator
     *
     * @return AbstractWriter
     */
    public function addDecorator(DecoratorInterface $decorator): AbstractWriter
    {
        $this->decorators[] = $decorator;

        return $this;
    }

    /**
     * Decorate log and return new instance.
     *
     * @param LogInterface $log
     *
     * @return LogInterface
     */
    protected function decorate(LogInterface $log): LogInterface
    {
        foreach ($this->decorators as $decorator) {
            $log = $decorator->decorate($log);
        }

        return $log;
    }

    /**
     * Check if log must be filtered.
     *
     * @param LogInterface $log
     *
     * @return bool
     */
    protected function filter(LogInterface $log): bool
    {
        foreach ($this->filters as $filter) {
            if ($filter->filter($log)) {
                return true;
            }
        }

        return false;
    }
}
