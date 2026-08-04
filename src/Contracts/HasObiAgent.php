<?php

namespace Obelaw\Obi\Filament\Contracts;

interface HasObiAgent
{
    public static function obiAgent(): callable;
}
