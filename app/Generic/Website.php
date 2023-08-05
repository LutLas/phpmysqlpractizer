<?php
namespace Generic;
interface Website {
    public function getLayoutVariables(): array ;
    public function getDefaultRoute(): string;
    public function getController(string $controllerName): ?object;
    public function checkLogin(string $uri): ?string;
}