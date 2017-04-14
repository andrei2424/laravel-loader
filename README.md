# laravel-loader

## License

Laravel loader is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

## Documentation
To get started with Loader, use Composer to add the package to your project's dependencies:
```php
composer require webdev/laravel-loader
```

## Configuration
```php
<?php

namespace App\Models;

use WebDev\Loader\LoaderMacro;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use LoaderMacro;
}
```

## Basic Usage
```php
$product = Product::findOrFail($id);

return $product->loader(
       new ProductTranslate(new Language()),
       new ProductFeature(new Language()),
       'categories'
);
```

### Product -> translate
```php
class ProductTranslate extends Loader
{

    protected $name = 'translate';

    // Translate
    public function init()
    {
        return ProductTranslate::where('product_id', $this->getModel()->id)
            ->where('language_id' => auth()->user()->language_id)
            ->firts()
            ->toArray();
    }
}
```

### Product -> Product features
```php
class ProductFeature extends Loader
{

    protected $name = 'features';

    // Features
    public function init()
    {
        return ProductFeature::where('product_id', $this->getModel()->id)->get()->toArray();
    }
}
```

### Product -> translate -> language && Product -> product features -> language
```php
class Language extends Loader
{

    protected $name = 'language';

    // Feature
    public function init()
    {
        return Language::findOrFail($this->getPrevious()->language_id)->toArray();
    }
}
```

### Result
```php
array:2 [
  "translate" => array:5 [
    "id" => 4
    "name" => "Product name"
    "description" => "Description"
    "language_id" => 40
    "language" => array:6 [
      "id" => 40
      "code" => "en"
      "flag" => null
      "name" => "English"
      "native_name" => "English"
      "is_active" => 1
    ]
  ]
  "features" => array:2 [
    0 => array:2 [
      "id" => 23
      "name" => "Feature1"
      "feature_id" => 14
      "language_id" => 40
      "language" => array:6 [
        "id" => 40
        "code" => "en"
        "flag" => null
        "name" => "English"
        "native_name" => "English"
        "is_active" => 1
      ]
    ]
    1 => array:5 [
      "id" => 24
      "name" => "TH"
      "feature_id" => 14
      "language_id" => 156
      "language" => array:6 [
        "id" => 156
        "code" => "th"
        "flag" => null
        "name" => "Thai"
        "native_name" => "ไทย"
        "is_active" => 1
      ]
    ]
  ],
  "categories" => ...
]
```
