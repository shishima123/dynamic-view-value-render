<?php

use Shishima\ConvertExport\ConvertExport;

if (! function_exists('dynamic_render_value')) {
    function dynamic_render_value(array $config)
    {
        return app(ConvertExport::class)
            ->setConfig(config: $config);
    }
}

if (! function_exists('dynamic_render_set_data')) {
    function dynamic_render_set_data(array $data)
    {
        return app(ConvertExport::class)->setDataInput(dataInput: $data);
    }
}
