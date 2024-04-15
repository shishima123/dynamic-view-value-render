<?php

use ES\ConvertExport\ConvertExport;

if (! function_exists('convert_export_value')) {
    function convert_export_value($config)
    {
        return app(ConvertExport::class)
            ->setConfig($config);
    }
}

if (! function_exists('convert_export_set_data')) {
    function convert_export_set_data(array $data)
    {
        return app(ConvertExport::class)->setDataInput($data);
    }
}
