# Dynamic View Value Render

[![Latest Version on Packagist](https://img.shields.io/packagist/v/shishima/dynamic-view-value-render.svg?style=flat-square)](https://packagist.org/packages/shishima/dynamic-view-value-render)
[![Total Downloads](https://img.shields.io/packagist/dt/shishima/dynamic-view-value-render.svg?style=flat-square)](https://packagist.org/packages/shishima/dynamic-view-value-render)

This package supports displaying values in views files according to predefined configurations. You don't need to directly modify the blade file, just adjust the config, and this package will handle the conversion for you

## Installation

You can install the package via composer:

```bash
composer require shishima/dynamic-view-value-render
```

## Usage

1. Create a configuration file in the `config` directory

   E.g: Create a configuration file for home blade

    ```php
    return [
        'delivery_date' => [
            'type' => [\Shishima\ConvertExport\Pipeline\DB::class],
            'value' => 'location.name'
            ]
        ];
        ...
    ```

2. In the controllers or similar, before returning the view, use the `convert_export_set_data` function to assign the input data

    E.g: **app/Controllers/HomeController.php**

    ```php
    $data = [
        'estimate' => $estimate,
        'company' => $company
   ]

    convert_export_set_data($data); // <-- Here
   
    return view($view, ...));
    ```

   You can use the compact function to collect data into an array and then pass it to the `convert_export_set_data` function

    ```php
    $data = compact('estimate', 'company');

    convert_export_set_data($data);  // <-- Here

    return view($view, ...));
    ```

3. In the blade file, at the positions where values need to show, use the `convert_export_value` function to output values based on the initial configuration

    ```html
    <div>
        <p>{{ convert_export_value(config('transform.delivery_date')) }}</p>
    </div>
   ```

## Config

In the config file, there are two configurable values: `type` and `value`

### Type
The configured parameters will be an array. Within the array will be classes for transforming output data

There are 2 classes available:

- **\Shishima\ConvertExport\Pipeline\DB::class**

    This class is used to retrieve data from within a variable. Nested data at multiple levels can be accessed through the dot `.` notation

    Use the value config to specify the key to retrieve the value

    E.g:

    ```php
    'delivery_date' => [
        'type' => [\Shishima\ConvertExport\Pipeline\DB::class],
        'value' => 'location.city.name'
        ]
    ];
   ```

-  **\Shishima\ConvertExport\Pipeline\Fixed::class**

   This class is used to print out predefined values configured in the `value`

   E.g:

    ```php
    'delivery_date' => [
        'type' => [\Shishima\ConvertExport\Pipeline\Fixed::class],
        'value' => 'Hello world!'
        ]
    ];
   ```

#### Custom Transform

In practical cases, data conversion may involve special cases. In such scenarios, to handle these cases, we can create a dedicated processing class and pass this class into type for the package to handle automatically

Since `type` is an array, multiple processing classes can be passed here. The data will be processed in the order from left to right

The output data of this class will be the input data for the next class

E.g: Create UpperCase class

```php
namespace App\Transform;

use Illuminate\Support\Arr;
use Shishima\ConvertExport\Pipeline\ConvertExportBase;

class UpperCase extends ConvertExportBase
{
    public function __invoke($payload) {
        $value = Arr::get($payload, 'value', '');
        $dataInput = Arr::get($payload, 'dataInput');
        $config = Arr::get($payload, 'config');

        // transform value
        return strtoupper($value);
    }
}
```

__IMPORTANT!__ If this class only performs a only task, you must use the `__invoke` method

Custom class must extend the `ConvertExportBase` class

The parameter `$payload` is an array consisting of 3 values:

- value: Input data of previous processing steps
- dataInput: All the data assigned when using the function `convert_export_set_data`
- config: The configuration passed through the function `convert_export_value`

After creating the custom class, configure this class in the config file

```php
'delivery_date' => [
    'type' => [
        \Shishima\ConvertExport\Pipeline\Fixed::class,
        \App\Transform\UpperCase::class,
    ],
    'value' => 'Hello world!'
    ]
];
```

#### Custom Class contains multiple processing methods

The package also supports writing multiple processing functions within one class

To do this, methods must start with the prefix `convert`

E.g:

```php
namespace App\Transform;

use Illuminate\Support\Arr;
use Shishima\ConvertExport\Pipeline\ConvertExportBase;

class Transform extends ConvertExportBase
{
    public function convertUpper($payload) {
        // ...
    }
    
    public function convertLower($payload) {
        // ...
    }
}
```


To use these functions in the config file, they will be configured under the type. No need for the `convert` prefix

```php
'delivery_date' => [
    'type' => [
        '\App\Transform\Transform:Upper,Lower',
    ],
    'value' => ''
    ]
];
```

### Value

Used for `DB::class` and `Fixed::class` to retrieve data

If not using the above 2 classes, it can be left blank

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities

## Credits

- [Phuoc Nguyen](https://github.com/shishima123)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information
