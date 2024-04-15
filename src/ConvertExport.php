<?php

namespace Shishima\ConvertExport;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Arr;

class ConvertExport
{
    public array $config;

    public array $dataInput;

    public function setConfig(array $config): static
    {
        $this->config = $config;
        return $this;
    }

    public function setDataInput(array $dataInput): static
    {
        $this->dataInput = $dataInput;
        return $this;
    }

    public function __toString(): string
    {
        $value = '';
        $pipes = Arr::get($this->config, 'type');

        if (empty($pipes)) {
            return $value;
        }

        $pipeline = app(Pipeline::class)
            ->send([
                'value' => $value,
                'dataInput' => $this->dataInput,
                'config' => $this->config
            ])
            ->through($pipes)
            ->thenReturn();

        return Arr::get($pipeline, 'value', '');
    }
}
