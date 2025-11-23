@extends('layouts.app')

 
@section('title', 'Cytotec Venezuela - Información Confiable sobre Misoprostol')
@section('description', 'Información verificada sobre Cytotec (Misoprostol) en Venezuela. Dosis, uso seguro, precios y recomendaciones médicas actualizadas 2024.')
@section('keywords', 'cytotec venezuela, misoprostol, cytotec precio venezuela, comprar cytotec, información cytotec, dosis misoprostol')


@section('content')
    <div id="products-grid"
        class="overflow-hidden sm:rounded-lg p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @include('main.ajax.products', ['products' => $products])
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const productsGrid = document.getElementById('products-grid');
    const url = `{{ route('product.find-or-all') }}`;
    let debounceTimer;

    if (!searchInput || !productsGrid) return;

    // Debounce: espera 300ms después de escribir
    searchInput.addEventListener('keyup', e => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => search(e.target.value.trim()), 700);
    });

    async function search(buscar = '') {
        try {
            const uri = buscar ? `${url}?buscar=${encodeURIComponent(buscar)}` : url;
            productsGrid.innerHTML =
                '<p class="text-center col-span-full py-10 text-indigo-500">Cargando...</p>';

            const response = await fetch(uri, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            productsGrid.innerHTML = data.html || '<p class="text-center col-span-full text-gray-500">Sin resultados.</p>';
        } catch (err) {
            console.error(err);
            productsGrid.innerHTML =
                '<p class="text-center col-span-full text-red-500">Error al cargar los productos.</p>';
        }
    }
});
</script>
@endpush
