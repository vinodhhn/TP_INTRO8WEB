@extends('layouts.main')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Boutique Shopify</h2>
        @if(!empty($shop))
            <small class="text-muted">{{ $shop['name'] ?? '' }} — {{ $shop['domain'] ?? '' }}</small>
        @endif
    </div>
    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">← Retour</a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h3 class="mb-0">{{ count($products) }}</h3>
                <small class="text-muted">Produits</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h3 class="mb-0">{{ count($orders) }}</h3>
                <small class="text-muted">Commandes récentes</small>
            </div>
        </div>
    </div>
</div>

{{-- Quick access --}}
<div class="row g-3 mb-5">
    <div class="col-12 col-md-6">
        <a href="{{ route('shopify.products') }}" class="card shadow-sm text-decoration-none text-dark h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <span style="font-size: 2rem;">📦</span>
                <div>
                    <h5 class="mb-0">Produits</h5>
                    <small class="text-muted">Voir et gérer le catalogue</small>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-md-6">
        <a href="{{ route('shopify.orders') }}" class="card shadow-sm text-decoration-none text-dark h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <span style="font-size: 2rem;">🛒</span>
                <div>
                    <h5 class="mb-0">Commandes</h5>
                    <small class="text-muted">Suivre les commandes</small>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Recent products --}}
@if(!empty($products))
<h5 class="mb-3">Produits récents</h5>
<div class="row g-2 mb-4">
    @foreach(array_slice($products, 0, 4) as $product)
    <div class="col-12 col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <strong>{{ $product['title'] }}</strong>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <small class="text-muted">{{ $product['vendor'] ?? '' }}</small>
                    <span class="badge bg-{{ $product['status'] === 'active' ? 'success' : 'secondary' }}">
                        {{ $product['status'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Recent orders --}}
@if(!empty($orders))
<h5 class="mb-3">Commandes récentes</h5>
<div class="table-responsive">
    <table class="table table-sm table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Client</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach(array_slice($orders, 0, 5) as $order)
            <tr>
                <td>{{ $order['order_number'] }}</td>
                <td>{{ $order['contact_email'] ?? 'N/A' }}</td>
                <td>{{ $order['total_price'] }} {{ $order['currency'] }}</td>
                <td>
                    <span class="badge bg-{{ $order['financial_status'] === 'paid' ? 'success' : 'warning text-dark' }}">
                        {{ $order['financial_status'] }}
                    </span>
                </td>
                <td>{{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection
