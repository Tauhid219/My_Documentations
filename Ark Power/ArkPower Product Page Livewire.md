Question: 
Hello. In my laravel app, there is a page named Products. In that page, normally all products will be shown. In that page there are 3 button: All, Electronics, and Machines. When I will click All button, all products will be shown. When click Electronics button, only electronic products will be shown. And when click Machines button, machine products will be shown.  
There are two table in my mysql: products and categories. They are relational tables. In products table there is a column names category_id which is catogory tables id.  
Now, how can I do this using livewire? 

Answer: 

If you have more categories and want the filter buttons to update dynamically based on your `categories` table, you can fetch the categories from the database and loop through them in the Blade view. Here’s how to adjust your code to handle an unknown number of categories:

### Steps for Dynamic Categories

1. **Update the Livewire Component to Retrieve Categories:**

   In your `Products` Livewire component, fetch all categories and pass them to the Blade view. 

   ```php
   <?php

   namespace App\Livewire;

   use App\Models\Product;
   use App\Models\Category; // Import Category model
   use Livewire\Component;
   use Livewire\WithPagination;

   class Products extends Component
   {
       use WithPagination;

       public $selectedCategory = null; // Selected category ID for filtering

       protected $paginationTheme = 'bootstrap';

       public function filterByCategory($categoryId = null)
       {
           $this->selectedCategory = $categoryId;
           $this->resetPage(); // Reset pagination when category changes
       }

       public function render()
       {
           // Fetch all categories
           $categories = Category::all();

           // Filter products based on selected category
           $products = Product::when($this->selectedCategory, function ($query) {
               $query->where('category_id', $this->selectedCategory);
           })->paginate(8);

           return view('livewire.products', [
               'products' => $products,
               'categories' => $categories,
           ]);
       }
   }
   ```

2. **Update the Blade View to Display Dynamic Category Buttons:**

   In your `products.blade.php`, use a loop to create a button for each category dynamically. Additionally, add an "All" button to display all products.

   ```blade
   <div class="flex justify-center my-5">
                <!-- "All" Button -->
                <button wire:click="filterByCategory"
                    class="px-4 py-2 mx-2 {{ is_null($selectedCategory) ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                    All
                </button>

                <!-- Category Buttons -->
                @foreach ($categories as $category)
                    <button wire:click="filterByCategory({{ $category->id }})"
                        class="px-4 py-2 mx-2 {{ $selectedCategory === $category->id ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
            <div class="grid lg:grid-cols-4 md:grid-cols-3 gap-10">
                @if ($products->isNotEmpty())
                    @foreach ($products as $product)
                        <div
                            class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md overflow-hidden p-1 border hover:shadow-xl">
                            <div
                                class="relative bg-clip-border overflow-hidden bg-transparent text-gray-700 shadow-none m-0 rounded-none">
                                <div class="relative h-44 rounded-lg overflow-hidden">
                                    <a href="{{ route('productdetails', $product->id) }}">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                                    </a>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="py-3">
                                    <a class="md:text-xl text-black font-semibold text-sm"
                                        href="{{ route('productdetails', $product->id) }}">{{ $product->name }}</a>
                                </div>
                                <div>
                                    <a href="{{ route('productdetails', $product->id) }}">
                                        {{ Str::limit($product->description, 100) }}<span
                                            class="text-blue-700 hover:underline">Read more</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Display message when no products are available -->
                    <div class="col-span-full text-center text-gray-700 font-semibold">
                        <p>No products available at the moment.</p>
                    </div>
                @endif
            </div>
   ```

With this approach, any new categories added to the `categories` table will automatically generate a button in the UI. The `filterByCategory` method will fetch products for the selected category, providing dynamic filtering without reloading the page.