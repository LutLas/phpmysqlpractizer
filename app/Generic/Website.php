<?php
namespace Generic;
interface Website {
    public function getDefaultRoute(): string;
    public function getController(string $controllerName): ?object;
}