<?php

namespace Fyyyn1210\WireSelect\Components;

use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class WireSelectBox extends Component
{
    public $items = [];
    public $selectedValue = null;
    public $searchTerm = '';
    public $placeholder = 'Cari...';
    public $label = '';
    public $required = true;
    public $disabled = false;
    public $emptyMessage = 'Tidak ada data ditemukan';
    public $maxHeight = '250px';
    public $fieldName = '';
    public $icon = 'ki-geolocation';
    public $iconpath = 2;
    public $showItems = false;
    public $showinit = true;
    public $loading = false;
    public $loadingItem = false;
    public $limit = 10;
    public $searchColumn = 'name';
    public $tableName = '';
    public $condition;
    public $keyColumn = 'id';
    public $valueColumn = 'name';

    #[On('finishLoading')]
    public function finishLoading()
    {
        $this->loadingItem = false;
    }

    public function updatedSelectedValue($value)
    {
        $this->dispatch('selectionChanged', [
            'field' => $this->fieldName,
            'value' => $value
        ]);
        $this->loadingItem = false;
    }

    public function selectItem($id)
    {
        if($this->selectedValue === $id) $id = null;
        $this->selectedValue = $id;
        $this->loadingItem = true;
        $this->dispatch('selectionChanged', [
            'field' => $this->fieldName,
            'value' => $id
        ]);
    }

    public function updatedSearchTerm()
    {
        $this->loading = true;
        $this->showItems = true;

        if (!empty($this->tableName) && !empty($this->searchColumn)) {
            $this->searchFromDatabase();
        }

        // sleep(0.5); // Uncomment if you want to simulate loading delay
        $this->loading = false;
    }

    private function searchFromDatabase()
    {
        if (empty($this->searchTerm)) {
            // Load initial data without search term
            $results = null;
            if($this->showinit){
                $results = DB::table($this->tableName)
                ->limit($this->limit)
                ->when($this->condition,function($query){
                    $query->where($this->condition);
                })
                ->get();
            }
        } else {
            // Search with term
            $results = DB::table($this->tableName)
                ->where($this->searchColumn, 'ILIKE', '%' . $this->searchTerm . '%')
                ->when($this->condition,function($query){
                    $query->where($this->condition);
                })
                ->limit($this->limit)
                ->get();
        }

        // Convert results to array format expected by the component
        $this->items = is_null($results) ? [] : $results->pluck($this->valueColumn, $this->keyColumn)->toArray();
    }

    public function getFilteredItemsProperty()
    {
        // If using database search, return items as is (already filtered)
        if (!empty($this->tableName) && !empty($this->searchColumn)) {
            return $this->sortItemsWithSelectedFirst($this->items);
        }

        // Original logic for array-based filtering
        if (empty($this->searchTerm)) {
            return $this->getSortedItems();
        }

        $filtered = collect($this->items)->filter(function ($name, $id) {
            return stripos($name, $this->searchTerm) !== false;
        });

        $items = array_slice($filtered->toArray(), 0, $this->limit, true);
        return $this->sortItemsWithSelectedFirst($items);
    }

    private function getSortedItems()
    {
        $items = array_slice($this->items, 0, $this->limit, true);

        if ($this->selectedValue && !isset($items[$this->selectedValue]) && isset($this->items[$this->selectedValue])) {
            $items = [$this->selectedValue => $this->items[$this->selectedValue]] + $items;
            $items = array_slice($items, 0, $this->limit, true); // maintain limit after adding
        }

        return $this->sortItemsWithSelectedFirst($items);
    }

    private function sortItemsWithSelectedFirst($items)
    {
        if (!$this->selectedValue) {
            return $items;
        }

        // If using database search, we need to ensure selected item is loaded
        if (!empty($this->tableName) && !isset($items[$this->selectedValue])) {
            $selectedItem = DB::table($this->tableName)
                ->when($this->condition,function($query){
                    $query->where($this->condition);
                })
                ->where($this->keyColumn, $this->selectedValue)
                ->first();

            if ($selectedItem) {
                $items = [$selectedItem->{$this->keyColumn} => $selectedItem->{$this->valueColumn}] + $items;
            }
        }

        if (!isset($this->items[$this->selectedValue]) && empty($this->tableName)) {
            return $items;
        }

        if (isset($items[$this->selectedValue])) {
            $selectedItem = [$this->selectedValue => $items[$this->selectedValue]];
            $otherItems = collect($items)->except($this->selectedValue)->toArray();
            return $selectedItem + $otherItems;
        }

        if (empty($this->tableName)) {
            $selectedItem = [$this->selectedValue => $this->items[$this->selectedValue]];
            return $selectedItem + $items;
        }

        return $items;
    }

    public function mount()
    {
        if (!empty($this->tableName) && !empty($this->searchColumn)) {
            $this->searchFromDatabase();
        }
    }

    public function hideItems(){
        $this->showItems = false;
        $this->reset(['searchTerm']);
    }

    public function showItemList()
    {
        $this->showItems = true;
    }

    public function render()
    {
        return view('wire-select::wire-select-box');
    }
}