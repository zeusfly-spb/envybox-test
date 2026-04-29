<?php

namespace Database\Factories;

use App\Interfaces\CodeGeneratorInterface;
use App\Services\ShortCodeGenerator;
use App\Models\Poll;
use App\Models\Option;

class PollFactory
{
    private CodeGeneratorInterface $codeGenerator;

    public function __construct(CodeGeneratorInterface $codeGenerator = null)
    {
        $this->codeGenerator = $codeGenerator ?? new ShortCodeGenerator();
    }

    public function create(string $title, array $optionsArray)
    {
        $shortCode = $this->codeGenerator->generate();
        $poll = Poll::create([
            'title' => $title,
            'short_code' => $shortCode,
        ]);
        foreach ($optionsArray as $optionText) {
            Option::create([
                'poll_id' => $poll->id,
                'text' => $optionText,
            ]);
        }
        return $poll;    
    }

}
