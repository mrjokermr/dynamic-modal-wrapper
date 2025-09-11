<?php

namespace Mrjokermr\DynamicModalWrapper\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Mrjokermr\DynamicModalWrapper\Classes\Modal;
use Mrjokermr\DynamicModalWrapper\Dtos\OpenModalDto;

class DynamicModalWrapper extends Component
{
    const OPEN_EVENT = 'dynamic_modal_wrapper_open_event';
    const CLOSE_EVENT = 'dynamic_modal_wrapper_close_event';
    const CLOSING_EVENT = 'dynamic_modal_wrapper_closing_event';

    public array $modals = [];
    public bool $escapeToClose = true;
    public ?string $defaultContentPadding = null;
    public string $backgroundColorHex;

    public function mount()
    {
        $this->escapeToClose = (bool) config('dynamic_modal_wrapper.escape_to_close', true);
        $this->defaultContentPadding = (string) config('dynamic-modal-wrapper.default_content_padding', '1.25rem');
        $this->backgroundColorHex = (string) config('dynamic-modal-wrapper.wrapper_background_color_hex', '#bdbdbd33');
    }

    #[On(self::OPEN_EVENT)]
    public function openModal(array $openModalDto)
    {
        $modal = Modal::createFromOpenModalDto(
            openModalDto: OpenModalDto::fromArray(value: $openModalDto),
        );

        if ($modal->isLivewireClass()) {
            $modals = $this->modals;
            array_unshift($modals, $modal);

            $this->modals = $modals;
        }
    }

    #[On(self::CLOSE_EVENT)]
    public function closeModal()
    {
        $modals = $this->modals;
        /** @var Modal|null $modal */
        $modal = $modals[0] ?? null;
        if ($modal !== null) {
            $this->dispatch(self::CLOSING_EVENT, name: $modal->name ?? $modal->getId());

            array_shift($modals);
            $this->modals = $modals;
        }
    }

    public function render()
    {
        return view('dynamic-modal-wrapper::Livewire.dynamic-modal-wrapper');
    }
}
