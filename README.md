# Best Alternative select2 for Livewire
🚀 The best and most practical alternative to Select2 for Laravel Livewire - Native searchable select component with database integration, no jQuery dependency!

# Complete Installation Tutorial

## Quick Start (2 Minutes Setup)

### Step 1: Install via Composer
```bash
composer require fyyyn1210/livewire-searchable-select
```

### Step 2: Use in Your Blade Template
```blade
<livewire:searchable-select-box 
    table-name="users" 
    field-name="user_id" 
    label="Select User" />
```

**That's it! 🎉 Your searchable select is ready!**

---

# 📋 Detailed Installation Guide

## Prerequisites

- Laravel 10.x or 11.x
- Livewire 3.x
- PHP 8.1+

## Step-by-Step Installation

### 1. Install Package

```bash
# Via Composer (Recommended)
composer require fyyyn1210/livewire-searchable-select

# Clear cache after installation
php artisan config:clear
php artisan view:clear
```

### 2. Verify Installation

```bash
# Check if package is loaded
composer show fyyyn1210/livewire-searchable-select
```

### 3. Publish Configuration (Optional)

```bash
# Publish config file for customization
php artisan vendor:publish --provider="Fyyyn1210\LivewireSearchableSelect\SearchableSelectServiceProvider" --tag="config"

# This creates: config/searchable-select.php
```

### 4. Publish Views (Optional)

```bash
# Publish views for custom styling
php artisan vendor:publish --provider="Fyyyn1210\LivewireSearchableSelect\SearchableSelectServiceProvider" --tag="views"

# This creates: resources/views/vendor/livewire-searchable-select/
```

### 5. Publish Assets (Optional)

```bash
# Publish CSS/JS assets
php artisan vendor:publish --provider="Fyyyn1210\LivewireSearchableSelect\SearchableSelectServiceProvider" --tag="assets"

# This creates: public/vendor/livewire-searchable-select/
```

---

# 🎯 Usage Examples

## Example 1: Basic Database Search

### Create Migration (if needed)
```php
// database/migrations/create_users_table.php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email');
    $table->timestamps();
});
```

### Seed Data (if needed)
```php
// database/seeders/UserSeeder.php
User::factory()->count(50)->create();
```

### Use in Livewire Component
```php
// app/Livewire/UserForm.php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class UserForm extends Component
{
    public $user_id;
    public $formData = [];

    #[On('selectionChanged')]
    public function handleUserSelection($data)
    {
        if ($data['field'] === 'user_id') {
            $this->user_id = $data['value'];
        }
    }

    public function save()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        // Save logic here
    }

    public function render()
    {
        return view('livewire.user-form');
    }
}
```

### Blade Template
```blade
<!-- resources/views/livewire/user-form.blade.php -->
<div>
    <form wire:submit="save">
        <livewire:searchable-select-box 
            table-name="users" 
            search-column="name"
            key-column="id"
            value-column="name"
            field-name="user_id" 
            label="Select User"
            placeholder="Type to search users..."
            :required="true" />

        @error('user_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror

        <button type="submit" class="btn btn-primary">Save</button>
    </form>

    @if($user_id)
        <div class="alert alert-success">
            Selected User ID: {{ $user_id }}
        </div>
    @endif
</div>
```

## Example 2: Static Array Data

```php
// app/Livewire/CategoryForm.php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class CategoryForm extends Component
{
    public $categories = [
        1 => 'Technology',
        2 => 'Business',
        3 => 'Science',
        4 => 'Health',
        5 => 'Education'
    ];

    public $selected_category;

    #[On('selectionChanged')]
    public function handleSelection($data)
    {
        if ($data['field'] === 'category') {
            $this->selected_category = $data['value'];
        }
    }

    public function render()
    {
        return view('livewire.category-form');
    }
}
```

```blade
<!-- resources/views/livewire/category-form.blade.php -->
<div>
    <livewire:searchable-select-box
        :items="$categories"
        field-name="category"
        label="Select Category"
        placeholder="Search categories..."
        icon="ki-category"
        :selected-value="$selected_category" />
</div>
```

## Example 3: Advanced Database with Conditions

```php
// app/Livewire/ProductForm.php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class ProductForm extends Component
{
    public $product_id;
    public $category_id = 1; // Pre-selected category

    #[On('selectionChanged')]
    public function handleProductSelection($data)
    {
        if ($data['field'] === 'product_id') {
            $this->product_id = $data['value'];
        }
    }

    public function render()
    {
        return view('livewire.product-form');
    }
}
```

```blade
<!-- resources/views/livewire/product-form.blade.php -->
<div>
    <livewire:searchable-select-box
        table-name="products"
        search-column="name"
        key-column="id"
        value-column="name"
        field-name="product_id"
        label="Select Active Product"
        :condition="['status' => 'active', 'category_id' => $category_id]"
        placeholder="Search products..."
        icon="ki-box"
        :limit="20"
        max-height="300px" />
</div>
```

---

# 🔧 Configuration Options

## Config File (config/searchable-select.php)

```php
<?php

return [
    'defaults' => [
        'placeholder' => 'Search...',
        'empty_message' => 'No data found',
        'max_height' => '250px',
        'limit' => 10,
        'icon' => 'ki-search',
        'icon_path' => 2,
        'required' => true,
    ],

    'css' => [
        'container' => 'mb-3',
        'label' => 'form-label fw-semibold',
        'input' => 'form-control',
        'dropdown' => 'border rounded shadow-sm',
    ]
];
```

## All Available Props

```blade
<livewire:searchable-select-box
    {{-- Data Source --}}
    :items="$array_data"              <!-- Static array data -->
    table-name="table_name"           <!-- Database table -->
    search-column="column_name"       <!-- Column to search -->
    key-column="id"                   <!-- Key column -->
    value-column="name"               <!-- Display column -->
    :condition="['status' => 'active']" <!-- WHERE conditions -->

    {{-- Behavior --}}
    field-name="field_name"           <!-- Field identifier -->
    :selected-value="$value"          <!-- Pre-selected value -->
    :limit="20"                       <!-- Max results -->
    :show-init="true"                 <!-- Show initial data -->

    {{-- Appearance --}}
    label="Select Item"               <!-- Label text -->
    placeholder="Search..."           <!-- Placeholder text -->
    icon="ki-search"                  <!-- Icon class -->
    :icon-path="2"                    <!-- Icon paths count -->
    max-height="300px"                <!-- Max dropdown height -->
    empty-message="No data found"     <!-- Empty state message -->

    {{-- States --}}
    :required="true"                  <!-- Required field -->
    :disabled="false"                 <!-- Disabled state -->
/>
```

---

# 🚀 Migration from Select2

## Before (Select2 Code)

```html
<!-- Old Select2 HTML -->
<div class="form-group">
    <label>Select User</label>
    <select id="user-select" class="form-control select2" data-placeholder="Choose user...">
        <option value=""></option>
    </select>
</div>

<script>
$(document).ready(function() {
    $('#user-select').select2({
        ajax: {
            url: '{{ route("users.search") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        placeholder: 'Search for users',
        minimumInputLength: 1,
        templateResult: formatUser,
        templateSelection: formatUserSelection
    });

    $('#user-select').on('select2:select', function (e) {
        var data = e.params.data;
        @this.set('user_id', data.id);
    });
});

function formatUser (user) {
    if (user.loading) {
        return user.text;
    }
    return $('<span>' + user.name + ' (' + user.email + ')</span>');
}

function formatUserSelection (user) {
    return user.name || user.text;
}
</script>
```

```php
// Old API Route
Route::get('/users/search', function (Request $request) {
    $users = User::where('name', 'like', '%' . $request->q . '%')
                 ->orWhere('email', 'like', '%' . $request->q . '%')
                 ->paginate(30);
    
    return response()->json([
        'items' => $users->items(),
        'total_count' => $users->total()
    ]);
});
```

## After (Livewire Searchable Select)

```blade
<!-- New Livewire Component - ONE LINE! -->
<livewire:searchable-select-box 
    table-name="users" 
    search-column="name"
    field-name="user_id" 
    label="Select User"
    placeholder="Search for users..." />
```

```php
// Handle selection in your Livewire component
#[On('selectionChanged')]
public function handleUserSelection($data)
{
    if ($data['field'] === 'user_id') {
        $this->user_id = $data['value'];
    }
}
```

**Lines of code reduced: 60+ → 2 lines!**

---

# 🛠️ Troubleshooting

## Common Issues

### Issue 1: Component Not Found
```bash
# Error: Component [searchable-select-box] not found
# Solution: Clear cache
php artisan config:clear
php artisan view:clear
composer dump-autoload
```

### Issue 2: Styles Not Applied
```bash
# Make sure Bootstrap/your CSS framework is loaded
# Or publish and customize the views
php artisan vendor:publish --provider="Fyyyn1210\LivewireSearchableSelect\SearchableSelectServiceProvider" --tag="views"
```

### Issue 3: Database Connection Issues
```php
// Make sure your database table exists and has the specified columns
// Check your .env database configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Issue 4: Events Not Working
```php
// Make sure you're using the correct event listener
use Livewire\Attributes\On;

#[On('selectionChanged')]
public function handleSelection($data)
{
    // Your logic here
}
```

## Debug Mode

```blade
<!-- Add debug info to see what's happening -->
<livewire:searchable-select-box 
    table-name="users" 
    field-name="user_id" 
    label="Select User" />

<!-- Debug output -->
@if(config('app.debug'))
    <div class="mt-2 small text-muted">
        Selected: {{ $user_id ?? 'None' }}
    </div>
@endif
```

---

# 🎉 You're Ready!

Your Livewire Searchable Select is now installed and ready to use. Start with a simple example and gradually explore more advanced features.

**Need help?** Check our [GitHub Issues](https://github.com/fyyyn1210/livewire-searchable-select/issues) or [Discussions](https://github.com/fyyyn1210/livewire-searchable-select/discussions).
