<?php

namespace ES\ConvertExport\Pipeline;

use Illuminate\Support\Arr;
use Closure;

class Fixed extends ConvertExportBase
{
    public function __invoke($payload)
    {
        return Arr::get($payload, 'config.value', '');
    }
}
