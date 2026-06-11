# CLAUDE.md — TP_INTRO8WEB

## Procédure : Connexion Shopify

Quand l'utilisateur dit **"connecte-toi à Shopify"** ou **"connexion Shopify"**, effectuer les étapes suivantes.

Les credentials sont stockés dans le fichier `.env` (gitignored, jamais commité).

### Variables attendues dans `.env`
```
SHOPIFY_API_KEY=<voir .env local>
SHOPIFY_API_SECRET=<voir .env local>
SHOPIFY_SHOP_DOMAIN=up9xbc-20.myshopify.com
SHOPIFY_SCOPES=read_products,write_products,read_orders,write_orders
SHOPIFY_ACCESS_TOKEN=<obtenu via OAuth ou Custom App token>
```

### Checklist de vérification

1. **`.env`** — variables `SHOPIFY_*` présentes et renseignées
2. **`config/shopify.php`** — lit les variables d'env
3. **`app/Services/ShopifyService.php`** — client HTTP Admin API (produits, commandes, shop)
4. **`app/Http/Controllers/ShopifyController.php`** — OAuth install/callback + pages
5. **`routes/web.php`** — routes `/shopify/*` protégées par `auth`
6. **`resources/views/shopify/`** — vues dashboard, products, orders, install

### Routes
```
GET /shopify           → dashboard boutique
GET /shopify/install   → lance le flux OAuth
GET /shopify/callback  → reçoit le code Shopify et échange contre un access_token
GET /shopify/products  → catalogue produits
GET /shopify/orders    → liste commandes
```

### Flux OAuth
1. Visiter `/shopify/install` → redirige vers `https://up9xbc-20.myshopify.com/admin/oauth/authorize`
2. Shopify rappelle `/shopify/callback?code=xxx&state=yyy`
3. Le controller vérifie l'HMAC, échange le code, stocke le token en session
4. Optionnel : copier le token dans `.env` sous `SHOPIFY_ACCESS_TOKEN` pour le persistance

### Notes
- `.env` est gitignored — les credentials ne sont jamais commités
- Branche de développement : `claude/shopify-api-auth-y9tq10`
- API version : `2024-01`
- L'HMAC des callbacks est vérifié (`hash_hmac` SHA256)
