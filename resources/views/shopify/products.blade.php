@extends('layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Produits</h2>
    <a href="{{ route('shopify.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
</div>

@forelse($products as $product)
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h5 class="card-title mb-1">{{ $product['title'] }}</h5>
                <small class="text-muted">{{ $product['vendor'] ?? '' }} · {{ $product['product_type'] ?? '' }}</small>
            </div>
            <span class="badge bg-{{ $product['status'] === 'active' ? 'success' : 'secondary' }}">
                {{ $product['status'] }}
            </span>
        </div>

        @if(!empty($product['variants']))
        <div class="mt-2">
            @foreach($product['variants'] as $variant)
            <span class="badge bg-light text-dark border me-1">
                {{ $variant['title'] !== 'Default Title' ? $variant['title'] . ' — ' : '' }}{{ $variant['price'] }} $
            </span>
            @endforeach
        </div>
        @endif
    </div>
</div>
@empty
<p class="text-muted">Aucun produit trouvé.</p>
@endforelse

@endsection
