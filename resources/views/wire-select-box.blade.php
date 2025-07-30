<div class="mb-3">
    @if($label)
        <label class="form-label fw-semibold {{ $required ? 'required' : '' }}">{{ $label }}</label>
    @endif

    <div class="position-relative">
        <div class="input-group mb-2">
            <input type="text" wire:model.live.debounce.300ms="searchTerm" wire:focus="showItemList"
                wire:keydown.escape="hideItems" @click.stop class="form-control" placeholder="{{ $placeholder }}"
                @disabled($disabled) />
        </div>

        <div class="{{ (count($this->filteredItems) > 0 && $showItems) ? 'border rounded shadow-sm' : '' }} @error($fieldName) border-danger @enderror"
            style="max-height: {{ $maxHeight }}; overflow-y: auto;">

            <!-- Loading State -->
            <div wire:loading wire:target="searchTerm" class="text-center p-4">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="spinner-border spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="text-muted">Searching...</span>
                </div>
            </div>

            <div wire:loading.remove wire:target="searchTerm" wire:click.away="hideItems" @click.stop>
                @foreach ($this->filteredItems ?? [] as $id => $name)
                    @if($selectedValue == $id)
                        <div wire:click="selectItem({{ $id }})"
                            class="d-flex align-items-center p-3 cursor-pointer bg-primary bg-opacity-10 border-start border-primary border-3">
                            <div class="me-3">
                                <i class="fas fa-check-circle text-primary fs-5"></i>
                            </div>
                            <div class="d-flex flex-column flex-grow-1">
                                <span class="fw-bold text-primary">
                                    {{ $loadingItem ? 'Processing...' : $name }}
                                    @if($showItems && !$loadingItem)
                                        <span class="badge bg-primary ms-2">Selected</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    @elseif($showItems)
                        <div wire:click="selectItem({{ $id }})"
                            class="d-flex align-items-center p-3 cursor-pointer hover-bg-light border-bottom">
                            <div class="me-3">
                                <i class="fas {{ $icon ?? 'fa-circle' }} text-muted fs-5"></i>
                            </div>
                            <div class="d-flex flex-column flex-grow-1">
                                <span class="fw-semibold">{{ $name }}</span>
                            </div>
                        </div>
                    @endif
                @endforeach

                @if(!empty($searchTerm) && (empty($this->filteredItems) || (count($this->filteredItems) == 1 && !!$selectedValue)) && !$loadingItem)
                    <div class="text-center text-muted p-4">
                        <i class="fas fa-info-circle text-warning fs-2 mb-2"></i>
                        <p class="mb-0">{{ $emptyMessage }}</p>
                    </div>
                @endif
            </div>
        </div>

        @error($fieldName)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<style>
/* Custom styles for better UX */
.cursor-pointer {
    cursor: pointer;
}

.hover-bg-light:hover {
    background-color: rgba(var(--bs-light-rgb), 0.5) !important;
}

.required::after {
    content: " *";
    color: var(--bs-danger);
}

/* Smooth transitions */
.hover-bg-light {
    transition: background-color 0.15s ease-in-out;
}

.border-start {
    transition: all 0.15s ease-in-out;
}

/* Better focus states */
.form-control:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
}

/* Responsive font sizes */
@media (max-width: 576px) {
    .fs-5 {
        font-size: 1rem !important;
    }

    .p-3 {
        padding: 0.75rem !important;
    }
}
</style>