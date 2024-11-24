@if ($careers)
@foreach ($careers as $career)
@if ($career->category->name === 'Job')
    <div class="py-4 border-b border-gray-200 hover:bg-gray-50 transition duration-300">
        <a class="text-lg text-gray-800 hover:text-blue-500 uppercase"
            href="{{ route('careerdetails', $career->id) }}">
            {{ $career->title }}
        </a>
    </div>
@endif
@endforeach 
@else
<p class="text-gray-600">No job opportunities are currently available.</p>
@endif