@foreach ($products as $product)
    <a href="{{ route('product.show', $product['id']) }}">
        <x-cart :producto="$product" />
    </a>
@endforeach

@if (count($products) === 0)
    <p class="col-span-full text-center text-gray-500 py-10">No se encontraron productos.</p>
@endif
