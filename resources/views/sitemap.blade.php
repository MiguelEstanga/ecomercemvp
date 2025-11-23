<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Página principal --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    
    {{-- Productos --}}
    @foreach($products ?? [] as $product)
    <url>
        <loc>{{ url('/products/' . $product->slug) }}</loc>
        <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    
    {{-- Páginas importantes --}}
    <url>
        <loc>{{ url('/about') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <url>
        <loc>{{ url('/contact') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
</urlset>