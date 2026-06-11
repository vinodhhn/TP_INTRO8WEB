<?php

namespace App\Http\Controllers;

use App\Services\ShopifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopifyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show Shopify dashboard (products + orders).
     */
    public function dashboard()
    {
        $shopify = $this->resolveService();

        if (!$shopify) {
            return redirect()->route('shopify.install');
        }

        $shop     = $shopify->getShop();
        $products = $shopify->getProducts(10);
        $orders   = $shopify->getOrders(10);

        return view('shopify.dashboard', compact('shop', 'products', 'orders'));
    }

    /**
     * Start OAuth flow — redirect to Shopify authorization page.
     */
    public function install(Request $request)
    {
        $shop = $request->input('shop', config('shopify.shop_domain'));

        if (!$shop) {
            return view('shopify.install');
        }

        $nonce = Str::random(32);
        session(['shopify_nonce' => $nonce, 'shopify_shop' => $shop]);

        $params = http_build_query([
            'client_id'    => config('shopify.api_key'),
            'scope'        => config('shopify.scopes'),
            'redirect_uri' => route('shopify.callback'),
            'state'        => $nonce,
        ]);

        return redirect("https://{$shop}/admin/oauth/authorize?{$params}");
    }

    /**
     * Handle OAuth callback — exchange code for access token.
     */
    public function callback(Request $request)
    {
        // Verify state nonce
        if ($request->input('state') !== session('shopify_nonce')) {
            abort(403, 'Invalid OAuth state.');
        }

        // Verify HMAC signature from Shopify
        if (!$this->verifyHmac($request)) {
            abort(403, 'Invalid HMAC signature.');
        }

        $shop = $request->input('shop', session('shopify_shop'));
        $code = $request->input('code');

        // Exchange code for access token
        $response = \Illuminate\Support\Facades\Http::post(
            "https://{$shop}/admin/oauth/access_token",
            [
                'client_id'     => config('shopify.api_key'),
                'client_secret' => config('shopify.api_secret'),
                'code'          => $code,
            ]
        );

        if (!$response->successful()) {
            abort(500, 'Failed to obtain access token from Shopify.');
        }

        $accessToken = $response->json('access_token');

        session([
            'shopify_access_token' => $accessToken,
            'shopify_shop'         => $shop,
        ]);

        return redirect()->route('shopify.dashboard')
            ->with('success', 'Boutique Shopify connectée avec succès !');
    }

    /**
     * Products list page.
     */
    public function products()
    {
        $shopify = $this->resolveService();

        if (!$shopify) {
            return redirect()->route('shopify.install');
        }

        $products = $shopify->getProducts(50);

        return view('shopify.products', compact('products'));
    }

    /**
     * Orders list page.
     */
    public function orders()
    {
        $shopify = $this->resolveService();

        if (!$shopify) {
            return redirect()->route('shopify.install');
        }

        $orders = $shopify->getOrders(50);

        return view('shopify.orders', compact('orders'));
    }

    /**
     * Resolve a ShopifyService from session or config.
     */
    private function resolveService(): ?ShopifyService
    {
        $token  = session('shopify_access_token') ?: config('shopify.access_token');
        $domain = session('shopify_shop') ?: config('shopify.shop_domain');

        if (!$token || !$domain) {
            return null;
        }

        return new ShopifyService($domain, $token);
    }

    /**
     * Verify HMAC signature sent by Shopify.
     */
    private function verifyHmac(Request $request): bool
    {
        $hmac   = $request->input('hmac');
        $params = $request->except('hmac');
        ksort($params);
        $message      = http_build_query($params);
        $expectedHmac = hash_hmac('sha256', $message, config('shopify.api_secret'));

        return hash_equals($expectedHmac, $hmac ?? '');
    }
}
