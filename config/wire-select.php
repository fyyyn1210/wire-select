<?php

return [
      /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    |
    | This array contains the default configuration for the searchable select
    | component. You can override these values by passing props to the component.
    |
    */

    'defaults' => [
        'placeholder'   => 'Cari...',
        'empty_message' => 'Tidak ada data ditemukan',
        'max_height'    => '250px',
        'limit'         => 10,
        'icon'          => 'ki-geolocation',
        'icon_path'     => 2,
        'search_column' => 'name',
        'key_column'    => 'id',
        'value_column'  => 'name',
        'show_init'     => true,
        'required'      => true,
        'disabled'      => false,
    ],

      /*
    |--------------------------------------------------------------------------
    | CSS Classes
    |--------------------------------------------------------------------------
    |
    | You can customize the CSS classes used by the component here.
    |
    */

    'css' => [
        'container'      => 'mb-2',
        'label'          => 'fw-semibold fs-6 mb-2',
        'label_required' => 'required',
        'input_group'    => 'input-group mb-2',
        'input'          => 'form-control',
        'dropdown'       => 'border rounded',
        'dropdown_error' => 'border-danger',
        'loading'        => 'text-center p-4',
        'item'           => 'd-flex align-items-center p-3 cursor-pointer hover-bg-light',
        'item_selected'  => 'd-flex align-items-center p-3 cursor-pointer bg-light-primary',
        'empty_message'  => 'text-center text-muted p-3',
        'error'          => 'invalid-feedback d-block',
    ],

      /*
    |--------------------------------------------------------------------------
    | View Path
    |--------------------------------------------------------------------------
    |
    | The view path for the component. You can change this if you want to
    | use a custom view.
    |
    */

    'view' => 'wire-select::wire-select-box',
];