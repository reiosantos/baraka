<?php

namespace App\Utils;


interface IRequest
{
    public function getRequestMethod(): ?string;
    public function getQueryString(): ?string;
    public function getHost(): ?string;
    public function getRequestUri(): ?string;
    public function getControllerName(): ?string;
    public function getAction(): ?string;
    public function getRequestURIAttributes(): ?array;
    public function getObjectPk(): ?string;
    public function get(string $param, string $default = null): ?string;
}
