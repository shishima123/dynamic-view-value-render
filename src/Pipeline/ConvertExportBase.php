<?php

namespace ES\ConvertExport\Pipeline;

use Closure;
use Illuminate\Support\Arr;

abstract class ConvertExportBase
{
    public function handle($payload, Closure $next, ...$args)
    {
        $value = Arr::get($payload, 'config.value', '');

        if (empty($args)) {
            $value = static::__invoke($payload);
        } else {
            foreach ($args as $arg) {
                $methodName = '_convert_' . $arg;

                if (method_exists($this, $methodName)) {
                    $value = call_user_func_array(array($this, $methodName), array($payload));
                }
            }
        }

        Arr::set($payload, 'value', $value);

        return $next($payload);
    }
}
