
# Dynamic modal wrapper

A lightweight package that brings simple, dynamic modal handling to Livewire 3.
It provides a clean way to open and close modals from anywhere in your application without boilerplate logic, while keeping configuration and customization flexible.

With DynamicModalWrapper you can:

- Dispatch modals dynamically from your Livewire components.
- Pass parameters to modal components at runtime.
- Hook into modal lifecycle events (```OPEN_EVENT```, ```CLOSING_EVENT```, ```CLOSE_EVENT```).
- Customize modal appearance (background colors, padding, z-index, press escape-to-close) via a publishable config.

## Installation

Install via composer:

```bash
  composer require mrjokermr/dynamic-modal-wrapper
```

Place the Livewire component right after the opening body tag in your layout (for example in ```resources/views/layouts/app.blade.php```)
```
<body>
@livewire('dynamic-modal-wrappe')
```



## Usage/Examples


```php
use Mrjokermr\DynamicModalWrapper\Dtos\OpenModalDto;
use Mrjokermr\DynamicModalWrapper\Livewire\DynamicModalWrapper;

$this->dispatch(
    DynamicModalWrapper::OPEN_EVENT,
    (new OpenModalDto(
        livewireClass: CreateProductModal::class,
    ))->toArray()
);
```
It is required to transform the ```OpenModalDto``` to an array via ```->toArray()```.
Since livewire 3 doesn't automatically serialize ```Wireable``` classes when dispatching events.

**Passing data to the mount function:**
```php
use Mrjokermr\DynamicModalWrapper\Dtos\OpenModalDto;
use Mrjokermr\DynamicModalWrapper\Livewire\DynamicModalWrapper;

$this->dispatch(
    DynamicModalWrapper::OPEN_EVENT,
    (new OpenModalDto(
        livewireClass: CreateProductModal::class,
        params: ['id' => 12]
    ))->toArray()
);
```

You might want to set the ```OpenModalDto``` name since this name will be sent with the php```DynamicModalWrapper::CLOSING_EVENT``` event for implementing custom logic before the livewire component will be broken down.
**example**
```php
use Mrjokermr\DynamicModalWrapper\Dtos\OpenModalDto;
use Mrjokermr\DynamicModalWrapper\Livewire\DynamicModalWrapper;

function open()
{
    $this->dispatch(
        DynamicModalWrapper::OPEN_EVENT,
        (new OpenModalDto(
            livewireClass: CreateProductModal::class,
            params: ['id' => 12],
        ))->setName(name: 'create-product-modal')->toArray()
    );
}

class CreateProductModal extends Component
{
    public function mount(int $id)
    {
        //...
    }
}
```
You might also want to set the ```OpenModalDto``` content background color to another color then the set ```default_content_wrapper_background_color_hex``` config value.
**example**
```php
use Mrjokermr\DynamicModalWrapper\Dtos\OpenModalDto;
use Mrjokermr\DynamicModalWrapper\Livewire\DynamicModalWrapper;

$this->dispatch(
    DynamicModalWrapper::OPEN_EVENT,
    (new OpenModalDto(
        livewireClass: CreateProductModal::class,
        params: ['id' => 12]
    ))->setContentBackgroundColorHex(hexColor: '#292929')->toArray() //hexColor: '292929' is also valid
);
```

The package dispatches a closing event once the ```DynamicModalWrapper::CLOSE_EVENT``` has been triggered, before completion:
```php
$this->dispatch(self::CLOSING_EVENT, name: $modal->name ?? $modal->getId());
```

Available events:
```php
use Mrjokermr\DynamicModalWrapper\Livewire\DynamicModalWrapper;

DynamicModalWrapper::OPEN_EVENT,
DynamicModalWrapper::CLOSE_EVENT,
DynamicModalWrapper::CLOSING_EVENT, //will be dispatched before closing the modal, and dispatched the optionally set ->setName(name: 'modal-name') name.
```

### Config

```bash
php artisan vendor:publish --tag=dynamic-modal-wrapper-config
```

**default config:**
```php
return [
    'default_content_padding' => '1.25rem', //can be set to any CSS accepted value. Also nullable
    'escape_to_close' => true, //when the user presses the ESC button the shown modal will be closed
    'default_content_wrapper_background_color_hex' => '#ffffff', //color used for wrapping the Livewire modal content
    'wrapper_background_color_hex' => '#bdbdbd33', //color used in combination with the backdrop blur.
    
    'z-index' => 100, //used by the dynamic modal container
];
```
