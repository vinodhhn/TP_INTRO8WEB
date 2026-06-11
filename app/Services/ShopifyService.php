<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ShopifyService
{
    private string $shopDomain;
    private string $accessToken;
    private string $apiVersion;

    public function __construct(string $shopDomain, string $accessToken)
    {
        $this->shopDomain   = rtrim($shopDomain, '/');
        $this->accessToken  = $accessToken;
        $this->apiVersion   = config('shopify.api_version', '2024-01');
    }

    private function baseUrl(): string
    {
        return "https://{$this->shopDomain}/admin/api/{$this->apiVersion}";
    }

    private function headers(): array
    {
        return ['X-Shopify-Access-Token' => $this->accessToken];
    }

    public function getShop(): array
    {
        return Http::withHeaders($this->headers())
            ->get("{$this->baseUrl()}/shop.json")
            ->json('shop', []);
    }

    public function getProducts(int $limit = 50): array
    {
        return Http::withHeaders($this->headers())
            ->get("{$this->baseUrl()}/products.json", ['limit' => $limit])
            ->json('products', []);
    }

    public function getProduct(int $id): array
    {
        return Http::withHeaders($this->headers())
            ->get("{$this->baseUrl()}/products/{$id}.json")
            ->json('product', []);
    }

    public function createProduct(array $data): array
    {
        return Http::withHeaders($this->headers())
            ->post("{$this->baseUrl()}/products.json", ['product' => $data])
            ->json('product', []);
    }

    public function updateProduct(int $id, array $data): array
    {
        return Http::withHeaders($this->headers())
            ->put("{$this->baseUrl()}/products/{$id}.json", ['product' => $data])
            ->json('product', []);
    }

    public function deleteProduct(int $id): bool
    {
        $response = Http::withHeaders($this->headers())
            ->delete("{$this->baseUrl()}/products/{$id}.json");

        return $response->successful();
    }

    public function getOrders(int $limit = 50): array
    {
        return Http::withHeaders($this->headers())
            ->get("{$this->baseUrl()}/orders.json", ['limit' => $limit, 'status' => 'any'])
            ->json('orders', []);
    }

    public function getOrder(int $id): array
    {
        return Http::withHeaders($this->headers())
            ->get("{$this->baseUrl()}/orders/{$id}.json")
            ->json('order', []);
    }
}
