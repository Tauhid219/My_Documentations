Question: 

<div>
                        <h1 class="text-2xl font-bold uppercase mb-7">Management</h1>
                        <div class="overflow-hidden block">
                            <nav>
                                <ul role="tablist"
                                    class="flex relative bg-blue-gray-50 bg-opacity-60 rounded-lg p-1 flex-row">
                                    <li role="tab"
                                        class="flex items-center justify-center text-center w-full h-full relative bg-transparent py-1 px-2 text-blue-gray-900 antialiased font-sans text-base font-normal leading-relaxed select-none cursor-pointer"
                                        data-value="md">
                                        <div class="z-20 text-inherit">Managing Director (MD)</div>
                                        <div class="absolute inset-0 z-10 h-full bg-white rounded-md shadow"
                                            style="transform: none; transform-origin: 50% 50% 0px; opacity: 1;">
                                        </div>
                                    </li>
                                    <li role="tab"
                                        class="flex items-center justify-center text-center w-full h-full relative bg-transparent py-1 px-2 text-blue-gray-900 antialiased font-sans text-base font-normal leading-relaxed select-none cursor-pointer"
                                        data-value="ceo">
                                        <div class="z-20 text-inherit">Chief Executive Officer (CEO)
                                        </div>
                                    </li>
                                </ul>
                            </nav>
                            <div class="block w-full relative bg-transparent overflow-hidden">
                                <div role="tabpanel"
                                    class="w-full h-max text-gray-700 p-4 antialiased font-sans text-base font-light leading-relaxed"
                                    data-value="md" style="opacity: 1; position: relative; z-index: 2;">
                                    <div class="md:grid grid-cols-5 md:gap-10 gap-5">
                                        <div class="col-span-2"><img
                                                src="https://wac-cdn.atlassian.com/dam/jcr:ba03a215-2f45-40f5-8540-b2015223c918/Max-R_Headshot%20(1).jpg?cdnVersion=1769"
                                                alt=""></div>
                                        <div class="col-span-3 mt-3">
                                            <h1 class="text-xl font-bold">Managing Director</h1>
                                            <p class="my-3">It really matters and then like it really
                                                doesn't matter.
                                                What matters is the people who are sparked by it. And
                                                the people
                                                who are like offended by it, it doesn't matter.</p>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel"
                                    class="w-full h-max text-gray-700 p-4 antialiased font-sans text-base font-light leading-relaxed"
                                    data-value="ceo"
                                    style="opacity: 0; position: absolute; top: 0px; left: 0px; z-index: 1;">
                                    <div class="md:grid grid-cols-5 md:gap-10 gap-5">
                                        <div class="col-span-2"><img
                                                src="https://beyondceocoaching.com/wp-content/uploads/elementor/thumbs/african-man-in-blue-elegant-costume-89AHPTZ-pjcsg7ortaugl52xjnhb9ximql1l21bne39c1reu3k.jpg"
                                                alt=""></div>
                                        <div class="col-span-3 mt-3">
                                            <h1 class="text-xl font-bold">Chief Executive Officer</h1>
                                            <p class="my-3">Because it's about motivating the doers.
                                                Because I'm here
                                                to follow my dreams and inspire other people to follow
                                                their dreams, too.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
In this div I want to do, if Managing Director button clicks, Managing Directors dynamic data shown, and if Chief Executive Officer button clicks, Chief Executive Officers dynamic data shown. Data came from Management model. I want to do it using livewire.

Answer: 

To achieve this with Livewire, you can create a component that dynamically updates the displayed data based on the selected tab. Here’s a general outline for setting it up:

1. **Create the Livewire Component:**

   Run the command:
   ```bash
   php artisan make:livewire ManagementTabs
   ```

2. **Define the Component Logic:** 

   In `ManagementTabs.php`, retrieve data from the `Management` model based on the selected tab.

   ```php
   <?php

   namespace App\Http\Livewire;

   use Livewire\Component;
   use App\Models\Management;

   class ManagementTabs extends Component
   {
       public $activeTab = 'md';
       public $managementData;

       public function mount()
       {
           $this->loadManagementData();
       }

       public function setTab($tab)
       {
           $this->activeTab = $tab;
           $this->loadManagementData();
       }

       public function loadManagementData()
       {
           $this->managementData = Management::where('designation', $this->activeTab === 'md' ? 'Managing Director' : 'Chief Executive Officer')->first();
       }

       public function render()
       {
           return view('livewire.management-tabs');
       }
   }
   ```

3. **Update the Livewire View (management-tabs.blade.php):**

   Structure the view with clickable tabs and dynamic content display.

   ```blade
   <div>
        <h1 class="text-2xl font-bold uppercase mb-7">Management</h1>
        <div class="overflow-hidden block">
            <nav>
                <ul role="tablist" class="flex relative bg-blue-gray-50 bg-opacity-60 rounded-lg p-1 flex-row">

                    <!-- Managing Director Tab -->
                    <li class="flex items-center justify-center text-center w-full h-full relative bg-transparent py-1 px-2 text-blue-gray-900 antialiased font-sans text-base font-normal leading-relaxed select-none cursor-pointer {{ $activeTab === 'md' ? 'bg-white shadow rounded-md' : '' }}"
                        wire:click="setTab('md')" role="tab" data-value="md">
                        <div class="z-20 text-inherit">Managing Director (MD)</div>
                    </li>

                    <!-- Chief Executive Officer Tab -->
                    <li class="flex items-center justify-center text-center w-full h-full relative bg-transparent py-1 px-2 text-blue-gray-900 antialiased font-sans text-base font-normal leading-relaxed select-none cursor-pointer {{ $activeTab === 'ceo' ? 'bg-white shadow rounded-md' : '' }}"
                        wire:click="setTab('ceo')" role="tab" data-value="md">
                        <div class="z-20 text-inherit">Chief Executive Officer (CEO)</div>
                    </li>
                </ul>
            </nav>

            @if ($managementData)
                <div class="block w-full relative bg-transparent overflow-hidden">
                    <div role="tabpanel"
                        class="w-full h-max text-gray-700 p-4 antialiased font-sans text-base font-light leading-relaxed"
                        data-value="md" style="opacity: 1; position: relative; z-index: 2;">
                        <div class="md:grid grid-cols-5 md:gap-10 gap-5">
                            <div class="col-span-2"><img src="{{ asset('storage/' . $managementData->image) }}"
                                    alt=""></div>
                            <div class="col-span-3 mt-3">
                                <h1 class="text-xl font-bold">{{ $managementData->name }}</h1>
                                <p class="my-3">{{ $managementData->about }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
   ```

4. **Update Routes and Include Component in Blade:**

   In a Blade file where you want to display the component:

   ```blade
   @livewire('management-tabs')
   ```

This setup will display the data based on the selected tab. When a tab is clicked, Livewire re-renders only the necessary parts, updating the content for the corresponding `designation`.