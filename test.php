<?php

class A {
    public function __construct(string $string)
    {

    }
}

try {
    new A([]);
} catch (Throwable $throwable) {

    echo $throwable->getMessage() . PHP_EOL;

}