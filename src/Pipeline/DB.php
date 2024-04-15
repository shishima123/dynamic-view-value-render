<?php

namespace ES\ConvertExport\Pipeline;

use Illuminate\Support\Arr;

class DB extends ConvertExportBase
{
    public function __invoke($payload)
    {
        $config = Arr::get($payload, 'config.value');

        return Arr::get($payload, 'dataInput.' . $config, '');
    }
}
