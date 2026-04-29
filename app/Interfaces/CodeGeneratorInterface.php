<?php

namespace App\Interfaces;

interface CodeGeneratorInterface
{
    public function generate(): string;
}