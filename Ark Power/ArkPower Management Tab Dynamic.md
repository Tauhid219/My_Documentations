Question: I want to do, only first 2 data fetch from database. When first tab clicked, first data shown, when second tab clicked, second data shown. 

Answer: 

To achieve the desired functionality where only the first two records from the database are fetched, and clicking the tabs displays the respective record, you can modify your `ManagementTabs` component as follows:

### Updated Component: `ManagementTabs.php`
```php
<?php

namespace App\Livewire;

use App\Models\Management;
use Livewire\Component;

class ManagementTabs extends Component
{
    public $activeTab = 'md'; // Default tab is Managing Director
    public $managementData = [];
    public $currentData;

    public function mount()
    {
        $this->managementData = Management::take(2)->get(); // Fetch only the first two records
        $this->setTab('md'); // Set the default tab
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;

        // Set the current data based on the active tab
        if ($tab === 'md') {
            $this->currentData = $this->managementData->first(); // First record for Managing Director
        } elseif ($tab === 'ceo' && $this->managementData->count() > 1) {
            $this->currentData = $this->managementData->get(1); // Second record for CEO
        } else {
            $this->currentData = null; // Handle cases where data is not available
        }
    }

    public function render()
    {
        return view('livewire.management-tabs');
    }
}
```

### Updated Blade View: `management-tabs.blade.php`
```blade
<div>
    <h1 class="text-2xl font-bold uppercase mb-7">Management</h1>
    <div class="overflow-hidden block">
        @if ($currentData)
            <nav>
                <ul role="tablist" class="flex relative bg-blue-gray-50 bg-opacity-60 rounded-lg p-1 flex-row">

                    <!-- Managing Director Tab -->
                    <li class="flex items-center justify-center text-center w-full h-full relative bg-transparent py-1 px-2 text-blue-gray-900 antialiased font-sans text-base font-normal leading-relaxed select-none cursor-pointer {{ $activeTab === 'md' ? 'bg-white shadow rounded-md' : '' }}"
                        wire:click="setTab('md')" role="tab" data-value="md">
                        <div class="z-20 text-inherit">Managing Director (MD)</div>
                    </li>

                    <!-- Chief Executive Officer Tab -->
                    <li class="flex items-center justify-center text-center w-full h-full relative bg-transparent py-1 px-2 text-blue-gray-900 antialiased font-sans text-base font-normal leading-relaxed select-none cursor-pointer {{ $activeTab === 'ceo' ? 'bg-white shadow rounded-md' : '' }}"
                        wire:click="setTab('ceo')" role="tab" data-value="ceo">
                        <div class="z-20 text-inherit">Chief Executive Officer (CEO)</div>
                    </li>
                </ul>
            </nav>

            <div class="block w-full relative bg-transparent overflow-hidden">
                <div role="tabpanel"
                    class="w-full h-max text-gray-700 p-4 antialiased font-sans text-base font-light leading-relaxed"
                    data-value="md" style="opacity: 1; position: relative; z-index: 2;">
                    <div class="md:grid grid-cols-5 md:gap-10 gap-5">
                        <div class="col-span-2">
                            <img src="{{ asset('storage/' . $currentData->image) }}" alt="">
                        </div>
                        <div class="col-span-3 mt-3">
                            <h1 class="text-xl font-bold">{{ $currentData->name }}</h1>
                            <p class="my-3">{{ $currentData->about }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <p class="text-center p-4">No management data available.</p>
        @endif
    </div>
</div>
```

### Explanation:
1. **Fetching Data:**
   - In the `mount()` method, only the first two records are fetched using `Management::take(2)->get()`.

2. **Dynamic Tab Switching:**
   - The `setTab` method determines which data to display based on the active tab.
   - For `'md'`, the first record is shown.
   - For `'ceo'`, the second record is shown if it exists.

3. **Error Handling:**
   - If there are less than two records, clicking the second tab (`ceo`) will not display any data.

4. **Blade View:**
   - The `$currentData` variable is used to display the selected management data dynamically. 

This ensures efficient data fetching and accurate display for the tabs. 









If you want the designation to be dynamically displayed for each tab (i.e., the first designation for the `md` tab and the second for the `ceo` tab), you should use the `managementData` collection directly in your Blade template. This ensures that the correct designation is displayed for each tab.

Here's how you can update the `<nav>` section to show the designation dynamically:

### Updated `<nav>` Section in `management-tabs.blade.php`
```blade
<nav>
    <ul role="tablist" class="flex relative bg-blue-gray-50 bg-opacity-60 rounded-lg p-1 flex-row">

        <!-- Managing Director Tab -->
        <li class="flex items-center justify-center text-center w-full h-full relative bg-transparent py-1 px-2 text-blue-gray-900 antialiased font-sans text-base font-normal leading-relaxed select-none cursor-pointer {{ $activeTab === 'md' ? 'bg-white shadow rounded-md' : '' }}"
            wire:click="setTab('md')" role="tab" data-value="md">
            <div class="z-20 text-inherit">
                {{ $managementData->first()?->designation ?? 'Managing Director (MD)' }}
            </div>
        </li>

        <!-- Chief Executive Officer Tab -->
        <li class="flex items-center justify-center text-center w-full h-full relative bg-transparent py-1 px-2 text-blue-gray-900 antialiased font-sans text-base font-normal leading-relaxed select-none cursor-pointer {{ $activeTab === 'ceo' ? 'bg-white shadow rounded-md' : '' }}"
            wire:click="setTab('ceo')" role="tab" data-value="ceo">
            <div class="z-20 text-inherit">
                {{ $managementData->get(1)?->designation ?? 'Chief Executive Officer (CEO)' }}
            </div>
        </li>
    </ul>
</nav>
```

### Key Changes:
1. **Dynamic Designation:**
   - For the `md` tab, the designation is fetched from the first record using `$managementData->first()?->designation`.
   - For the `ceo` tab, the designation is fetched from the second record using `$managementData->get(1)?->designation`.

2. **Fallback Text:**
   - If a record is not available (e.g., only one record exists in the database), fallback text like "Managing Director (MD)" or "Chief Executive Officer (CEO)" is displayed.

3. **Null-safe Operator (`?->`):**
   - Ensures that the template doesn't break if there are fewer records than expected.

### Result:
- The tabs will display the `designation` field of the first and second management records dynamically.
- If the database contains fewer than two records, the fallback text ensures a graceful UI experience. 









To ensure that if there is only one record in the database, the second tab is not clickable and is visually disabled, you can modify the Blade view to check the number of records and adjust the behavior accordingly.

Here's the updated `<nav>` section:

### Updated `<nav>` Section in `management-tabs.blade.php`
```blade
<nav>
    <ul role="tablist" class="flex relative bg-blue-gray-50 bg-opacity-60 rounded-lg p-1 flex-row">

        <!-- Managing Director Tab -->
        <li class="flex items-center justify-center text-center w-full h-full relative bg-transparent py-1 px-2 text-blue-gray-900 antialiased font-sans text-base font-normal leading-relaxed select-none cursor-pointer {{ $activeTab === 'md' ? 'bg-white shadow rounded-md' : '' }}"
            wire:click="setTab('md')" role="tab" data-value="md">
            <div class="z-20 text-inherit">
                {{ $managementData->first()?->designation ?? 'Managing Director (MD)' }}
            </div>
        </li>

        <!-- Chief Executive Officer Tab (Disabled if only one record exists) -->
        <li class="flex items-center justify-center text-center w-full h-full relative bg-transparent py-1 px-2 text-blue-gray-400 antialiased font-sans text-base font-normal leading-relaxed select-none {{ $managementData->count() > 1 ? 'cursor-pointer text-blue-gray-900' : 'opacity-50 cursor-not-allowed' }} {{ $activeTab === 'ceo' && $managementData->count() > 1 ? 'bg-white shadow rounded-md' : '' }}"
            @if($managementData->count() > 1) wire:click="setTab('ceo')" @endif role="tab" data-value="ceo">
            <div class="z-20 text-inherit">
                {{ $managementData->get(1)?->designation ?? 'Chief Executive Officer (CEO)' }}
            </div>
        </li>
    </ul>
</nav>
```

### Key Changes:
1. **Disabling the CEO Tab:**
   - The `li` for the CEO tab is conditionally styled.
   - If there is only one record (`$managementData->count() <= 1`), the tab gets additional classes: `opacity-50` (fades the tab) and `cursor-not-allowed` (disables pointer interactions).

2. **Conditional `wire:click`:**
   - The `wire:click` attribute for the CEO tab is only added if there are more than one records.

3. **Dynamic Styling:**
   - Tabs are styled differently depending on whether they are active (`bg-white shadow rounded-md`) or inactive and disabled.

### Behavior:
- If there is **only one record**:
  - The CEO tab appears grayed out and is not clickable.
  - Only the MD tab is functional and clickable.
- If there are **two or more records**:
  - Both tabs are clickable and switch content as intended. 

This implementation ensures an intuitive UI and avoids confusion when only one record is available. 









To ensure that the second tab does not display any designation if only one record exists, you can conditionally check the number of records in the `managementData` collection. Here's the updated `<nav>` section:

### Updated `<nav>` Section in `management-tabs.blade.php`
```blade
<nav>
    <ul role="tablist" class="flex relative bg-blue-gray-50 bg-opacity-60 rounded-lg p-1 flex-row">

        <!-- Managing Director Tab -->
        <li class="flex items-center justify-center text-center w-full h-full relative bg-transparent py-1 px-2 text-blue-gray-900 antialiased font-sans text-base font-normal leading-relaxed select-none cursor-pointer {{ $activeTab === 'md' ? 'bg-white shadow rounded-md' : '' }}"
            wire:click="setTab('md')" role="tab" data-value="md">
            <div class="z-20 text-inherit">
                {{ $managementData->first()?->designation ?? 'Managing Director (MD)' }}
            </div>
        </li>

        <!-- Chief Executive Officer Tab (Hidden Designation if Only One Record) -->
        <li class="flex items-center justify-center text-center w-full h-full relative bg-transparent py-1 px-2 text-blue-gray-400 antialiased font-sans text-base font-normal leading-relaxed select-none {{ $managementData->count() > 1 ? 'cursor-pointer text-blue-gray-900' : 'opacity-50 cursor-not-allowed' }} {{ $activeTab === 'ceo' && $managementData->count() > 1 ? 'bg-white shadow rounded-md' : '' }}"
            @if($managementData->count() > 1) wire:click="setTab('ceo')" @endif role="tab" data-value="ceo">
            <div class="z-20 text-inherit">
                @if ($managementData->count() > 1)
                    {{ $managementData->get(1)?->designation }}
                @else
                    <!-- No designation shown -->
                @endif
            </div>
        </li>
    </ul>
</nav>
```

### Key Changes:
1. **Conditionally Display Designation for CEO Tab:**
   - For the second tab, the designation (`$managementData->get(1)?->designation`) is only shown if `managementData` contains more than one record (`$managementData->count() > 1`).
   - Otherwise, the `div` is left empty, showing no text in the tab.

2. **Graceful Fallback:**
   - If there is only one record, the CEO tab will remain disabled (gray with `opacity-50` and `cursor-not-allowed`) and show no designation.

### Behavior:
- **If only one record exists:**
  - The first tab (`md`) will display the designation.
  - The second tab (`ceo`) will appear grayed out and show no designation.
- **If two or more records exist:**
  - Both tabs will display their respective designations and be fully functional.

This ensures a clean and intuitive interface that avoids showing irrelevant information.