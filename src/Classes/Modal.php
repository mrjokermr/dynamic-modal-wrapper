<?php

namespace Mrjokermr\DynamicModalWrapper\Classes;

use Livewire\Component;
use Livewire\Wireable;
use Log;
use Mrjokermr\DynamicModalWrapper\Dtos\OpenModalDto;
use Str;

class Modal implements Wireable
{
    private string $id;
    public function __construct(
        public string $livewireClass,
        public ?array $params = null,
        public ?string $name = null,
        public ?string $contentBackgroundColorHex = null,
    )
    {
        $this->id = Str::uuid()->toString();

        if ($this->contentBackgroundColorHex === null) {
            $this->contentBackgroundColorHex = config('dynamic-modal-wrapper.default_content_wrapper_background_color_hex', '#ffffff');
        }
    }

    public static function createFromOpenModalDto(OpenModalDto $openModalDto): self
    {
        return new self(
            livewireClass: $openModalDto->livewireClass,
            params: $openModalDto->params,
            name: $openModalDto->name,
            contentBackgroundColorHex: $openModalDto->contentBackgroundColorHex,
        );
    }

    public function isLivewireClass(): bool
    {
        if (class_exists($this->livewireClass) && is_subclass_of($this->livewireClass, Component::class)) {
            return true;
        } else {
            Log::error($this->livewireClass.", is not a livewire class. Can't open as modal.");

            return false;
        }
    }

    private function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function toLivewire(): array
    {
        return [
            'id' => $this->id,
            'livewireClass' => $this->livewireClass,
            'params' => $this->params,
            'name' => $this->name,
            'contentBackgroundColorHex' => $this->contentBackgroundColorHex,
        ];
    }

    public static function fromLivewire($value): self
    {
        return (new self(
            livewireClass: $value['livewireClass'],
            params: $value['params'] ?? null,
            name: $value['name'],
            contentBackgroundColorHex: $value['contentBackgroundColorHex'] ?? null,
        ))->setId($value['id']);
    }

    public function __serialize(): array
    {
        return $this->toLivewire();
    }

    public function __unserialize(array $data): void
    {
        $instance = self::fromLivewire($data);

        $this->id = $instance->id;
        $this->livewireClass = $instance->livewireClass;
        $this->params = $instance->params;
        $this->name = $instance->name;
        $this->contentBackgroundColorHex = $instance->contentBackgroundColorHex;
    }
}
