<?php

use Gemini\Data\FunctionDeclaration;
use Obelaw\Obi\Declaration;
use Obelaw\Obi\Filament\ObiDeclarations;

return new class extends Declaration
{
    public function declaration(): FunctionDeclaration
    {
        return new FunctionDeclaration(
            name: 'obeName',
            description: 'Your name as ai agent.',
        );
    }

    public function targetClass(): string
    {
        return ObiDeclarations::class;
    }

    public function targetMethod(): string
    {
        return 'name';
    }
};
