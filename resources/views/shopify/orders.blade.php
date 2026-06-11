@extends('layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Commandes</h2>
    <a href="{{ route('shopify.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
</div>

@forelse($orders as $order)
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="mb-1">Commande #{{ $order['order_number'] }}</h6>
                <small class="text-muted">{{ $order['contact_email'] ?? 'Pas d\'email' }}</small>
            </div>
            <div class="text-end">
                <strong>{{ $order['total_price'] }} {{ $order['currency'] }}</strong><br>
                <span class="badge bg-{{ $order['financial_status'] === 'paid' ? 'success' : 'warning text-dark' }}">
                    {{ $order['financial_status'] }}
                </span>
            </div>
        </div>

        @if(!empty($order['line_items']))
        <ul class="list-unstyled mt-2 mb-0">
            @foreach($order['line_items'] as $item)
            <li><small>{{ $item['quantity'] }}× {{ $item['title'] }}</small></li>
            @endforeach
        </ul>
        @endif

        <small class="text-muted d-block mt-1">
            {{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}
        </small>
    </div>
</div>
@empty
<p class="text-muted">Aucune commande trouvée.</p>
@endforelse

@endsection
