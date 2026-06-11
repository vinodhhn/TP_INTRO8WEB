@extends('layouts.main')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="card shadow-sm" style="max-width: 480px; width: 100%;">
        <div class="card-body p-4">
            <h4 class="card-title mb-1">Connecter Shopify</h4>
            <p class="text-muted mb-4">Entrez votre domaine Shopify pour autoriser l'accès.</p>

            <form action="{{ route('shopify.install') }}" method="GET">
                <div class="mb-3">
                    <label for="shop" class="form-label">Domaine de la boutique</label>
                    <input
                        type="text"
                        id="shop"
                        name="shop"
                        class="form-control"
                        placeholder="ma-boutique.myshopify.com"
                        value="{{ config('shopify.shop_domain') }}"
                        required
                    >
                </div>
                <button type="submit" class="btn btn-dark w-100">
                    Autoriser l'accès Shopify
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
