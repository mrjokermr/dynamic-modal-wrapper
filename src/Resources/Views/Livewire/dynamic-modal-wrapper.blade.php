<div
    id="dynamic_modal_wrapper_container"
    wire:key="dynamic_modal_wrapper_container"
    @if ($escapeToClose)
    x-on:keydown.escape.window="$wire.dispatch('{{ \Mrjokermr\DynamicModalWrapper\Livewire\DynamicModalWrapper::CLOSE_EVENT}}')"
    @endif
    style="{{ count($modals) > 0
        ? 'position:fixed;width:100%;height:100%;z-index:'.((int) config('dynamic-modal-wrapper.z-index')).';display:flex;align-items:center;justify-content:center;backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);'
        : '' }}"
>
    @php /** @var \Mrjokermr\DynamicModalWrapper\Classes\Modal $modal */ @endphp
    @foreach ($modals as $i => $modal)
        @php
            $isTop = $i === 0;
            $z = ((int) config('dynamic-modal-wrapper.z-index')) + (count($modals) - $i);
        @endphp

        <div
            wire:key="wrapper_livewire_comp_{{ $modal->getId() }}"
            style="background-color: {{ $backgroundColorHex }}; @if ($isTop) backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px); @endif position:fixed;top:0;right:0;bottom:0;left:0;display:flex;align-items:center;justify-content:center;transition:all 150ms ease-in-out;z-index: {{ $z }};{{ $isTop ? 'opacity:1;pointer-events:auto;' : 'opacity:0;pointer-events:none;' }}"
        >
            <div
                style="position:relative;display:inline-block;width:auto;max-width:90vw;max-height:90vh;margin-left:1rem;margin-right:1rem;border-radius:12px;background-color:#fff;box-shadow:0 20px 25px -5px rgba(0,0,0,.1), 0 10px 10px -5px rgba(0,0,0,.04);overflow:visible;"
            >
                <button
                    type="button"
                    wire:click="closeModal"
                    style="position:absolute;top:-1rem;right:-1rem;font-size:1.5rem;line-height:2rem;border-radius:9999px;background-color:#ffffff;border:1px solid #6b7280;padding:0.125rem;cursor:pointer;"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        style="display:block;width:1.25rem;height:1.25rem;"
                    >
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </button>

                <div style="
                        @if ($defaultContentPadding !== null) padding: {{ $defaultContentPadding }}; @endif
                        background-color: {{ $modal->contentBackgroundColorHex }};
                        border-radius: 6px;
                    "
                >
                    @if (!empty($modal->params))
                        @livewire($modal->livewireClass, $modal->params, key($modal->getId()))
                    @else
                        @livewire($modal->livewireClass, key($modal->getId()))
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
