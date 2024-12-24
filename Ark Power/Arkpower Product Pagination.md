Question: 
Hello. I have products page in my laravel app, in that page data come from Product model. Now I want to impliment pagination in that page using livewire. It shown 8 products per page, if products more than 8, the pagination visible, otherwise pagination hide. 

Answer: 

To add pagination to your products page using Livewire, follow these steps:

1. **Create a Livewire Component:**
   Generate a Livewire component named `ProductList` (or similar):
   ```bash
   php artisan make:livewire ProductList
   ```

2. **Set Up Pagination in the Component:**
   In the generated `ProductList.php` file, modify the `render()` method to fetch products with pagination.

   ```php
   <?php

   namespace App\Http\Livewire;

   use Livewire\Component;
   use Livewire\WithPagination;
   use App\Models\Product;

   class ProductList extends Component
   {
       use WithPagination;

       protected $paginationTheme = 'bootstrap'; // Optional, for Bootstrap-style pagination

       public function render()
       {
           return view('livewire.product-list', [
               'products' => Product::paginate(8), // 8 products per page
           ]);
       }
   }
   ```

3. **Update the Blade Template:**
   Replace the `products.blade.php` code with Livewire syntax for displaying products and pagination.

   In `resources/views/livewire/product-list.blade.php`:
   ```blade
   <div class="min-h-[calc(100vh-335px)]">
       <div class="min-h-screen w-full max-w-screen-xl mx-auto flex items-center justify-center p-5">
           <div class="md:mt-24 mt-14">
               <h1 class="text-primary text-center md:text-5xl text-3xl uppercase">Our Products</h1>
               <p class="text-primary text-center md:mt-2 text-xl md:uppercase mb-10">We truly care about our customer</p>
               <div class="grid lg:grid-cols-4 md:grid-cols-3 gap-10">
                   @foreach ($products as $product)
                       <div class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md overflow-hidden p-1 border hover:shadow-xl">
                           <div class="relative bg-clip-border overflow-hidden bg-transparent text-gray-700 shadow-none m-0 rounded-none">
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
                                       {{ Str::limit($product->description, 100) }}<span class="text-blue-700 hover:underline">Read more</span>
                                   </a>
                               </div>
                           </div>
                       </div>
                   @endforeach
               </div>
               <!-- Pagination links -->
               @if ($products->hasPages())
                   <div class="flex justify-center mt-8">
                       {{ $products->links() }}
                   </div>
               @endif
           </div>
       </div>
   </div>
   ```

4. **Integrate the Livewire Component in Your Blade File:**
   In your `products.blade.php` file, include the Livewire component:

   ```blade
   @extends('layouts.layout')
   @section('main-content')
       <livewire:product-list />
   @endsection
   ```

5. **Update Routes if Necessary:**
   Ensure your `products.blade.php` route is set to load the Livewire component.

With this setup, your page will display pagination when there are more than 8 products and hide it otherwise. 

Question: 
Pagination should in this design: 
<div class="flex justify-center mt-8"><button disabled=""
                        class="px-4 py-2 mx-1 border rounded bg-gray-300">Previous</button><button
                        class="px-4 py-2 mx-1 border rounded bg-blue-500 text-white">1</button><button
                        class="px-4 py-2 mx-1 border rounded bg-white text-blue-500 hover:bg-blue-300">2</button><button
                        class="px-4 py-2 mx-1 border rounded bg-white text-blue-500 hover:bg-blue-300">Next</button></div>
            </div> 

Answer: 

To style the pagination in Livewire with your custom design, you can customize the pagination links in `resources/views/livewire/product-list.blade.php`. Here’s how to achieve your design:

1. **Customize Pagination Links in the Livewire View:**
   Replace the `{{ $products->links() }}` section with a custom pagination layout. Livewire provides pagination properties like `previousPageUrl`, `nextPageUrl`, and `currentPage`.

2. **Custom Pagination Code:**
   Here’s an example that replicates your design using Livewire pagination:

   ```blade
   @if ($products->hasPages())
       <div class="flex justify-center mt-8">
           {{-- Previous Page Link --}}
           @if ($products->onFirstPage())
               <button disabled class="px-4 py-2 mx-1 border rounded bg-gray-300">Previous</button>
           @else
               <button wire:click="previousPage" class="px-4 py-2 mx-1 border rounded bg-blue-500 text-white">Previous</button>
           @endif

           {{-- Pagination Elements --}}
           @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
               <button wire:click="gotoPage({{ $page }})"
                       class="px-4 py-2 mx-1 border rounded {{ $page === $products->currentPage() ? 'bg-blue-500 text-white' : 'bg-white text-blue-500 hover:bg-blue-300' }}">
                   {{ $page }}
               </button>
           @endforeach

           {{-- Next Page Link --}}
           @if ($products->hasMorePages())
               <button wire:click="nextPage" class="px-4 py-2 mx-1 border rounded bg-blue-500 text-white">Next</button>
           @else
               <button disabled class="px-4 py-2 mx-1 border rounded bg-gray-300">Next</button>
           @endif
       </div>
   @endif
   ```

This code will display pagination as per your design:

- **Previous/Next Buttons**: They change appearance based on whether they’re clickable.
- **Page Numbers**: Highlight the current page with `bg-blue-500 text-white` and style other pages with hover effects.
