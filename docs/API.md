# API

Sanctum, prefix `/api/v1`. Send `Authorization: Bearer {token}` from a personal access token.

| Method | Path | Returns |
|---|---|---|
| GET | `/api/v1/orders` | Buyer orders visible to the token user |
| GET | `/api/v1/orders/{id}` | One buyer order |
| GET | `/api/v1/issues` | Open issues |
| GET | `/api/v1/production/{id}` | One production order with TNA and material requirements |

Create a token from tinker:

```bash
php artisan tinker
>>> $user = App\Models\User::where('email', 'admin@knitwear.test')->first();
>>> $user->createToken('mill-desk')->plainTextToken;
```

Document print (session auth, not the API): `GET /documents/{table}/{id}` — for example `/documents/buyer_orders/1`.
