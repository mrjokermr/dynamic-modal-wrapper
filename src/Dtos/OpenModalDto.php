<?php

namespace Mrjokermr\DynamicModalWrapper\Dtos;

use Livewire\Wireable;

class OpenModalDto implements Wireable
{
    public ?string $name = null;
    public ?string $contentBackgroundColorHex = null;
    public function __construct(
        public string $livewireClass,
        public ?array $params = null,
    )
    {}

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setContentBackgroundColorHex(string $hexColor): self
    {
        if (!str_starts_with($hexColor, '#')) {
            $hexColor = '#' . $hexColor;
        }

        $this->contentBackgroundColorHex = $hexColor;

        return $this;
    }

    public function toLivewire(): array
    {
        return [
            'livewireClass' => $this->livewireClass,
            'params' => $this->params,
            'name' => $this->name,
            'contentBackgroundColorHex' => $this->contentBackgroundColorHex,
        ];
    }

    public static function fromLivewire($value): self
    {
        $openModalDto = new self(
            livewireClass: $value['livewireClass'],
            params: $value['params'],
        );

        if (isset($value['name'])) {
            $openModalDto->setName($value['name']);
        }

        if (isset($value['contentBackgroundColorHex'])) {
            $openModalDto->setContentBackgroundColorHex($value['contentBackgroundColorHex']);
        }

        return $openModalDto;
    }

    public function toArray(): array
    {
        return $this->toLivewire();
    }

    public static function fromArray($value): self
    {
        return self::fromLivewire($value);
    }

    public function __serialize(): array
    {
        return $this->toArray();
    }

    public function __unserialize(array $data): void
    {
        $instance = self::fromLivewire($data);

        $this->name = $instance->name;
        $this->contentBackgroundColorHex = $instance->contentBackgroundColorHex;
        $this->livewireClass = $instance->livewireClass;
        $this->params = $instance->params;
    }
}
