<?php

namespace App\Utils;


interface IRequest
{
    public function getRequestMethod(): string;
    public function get(): ?string;
}
