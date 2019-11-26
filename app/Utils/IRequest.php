<?php

namespace App\Utils;


interface IRequest
{
    public function redirectToHome(?string $to = null): void;
    public function getRequestMethod(): ?string;

    public function getBaseUrl(): string;
    public function getQueryString(): ?string;
    public function getHost(): ?string;
    public function getRequestUri(): ?string;
    public function getControllerName(): ?string;
    public function getAction(): ?string;
    public function getRequestURIAttributes(): ?array;
    public function getObjectPk(): ?string;
    public function get(string $param, string $default = null): ?string;
    public function getFile(string $param): ?array;
    public function getFilesArray(): ?array;
    public function cleanData(string $data): string;

    public function addToSession(string $key, string $value = null): void;
    public function getFromSession(string $key): ?string;

    public function generateToken(): ?string;

    public function validateToken(): bool;

    public function clearOldToken(): void;
}
