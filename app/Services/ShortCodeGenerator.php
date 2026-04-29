<?php

namespace App\Services;

use App\Interfaces\CodeGeneratorInterface;
use App\Models\Poll;
use Illuminate\Support\Str;

class ShortCodeGenerator implements CodeGeneratorInterface
{
    public function generate(): string
    {
        do {
            $code = Str::random(6);
        } while (Poll::where('short_code', $code)->exists());
        return $code;
    }
}