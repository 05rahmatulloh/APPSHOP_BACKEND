# 🛒 App Shop Backend API

Dokumentasi resmi API untuk **App Shop Backend**. Ditujukan untuk kebutuhan frontend (Flutter / Web) dan integrasi payment Midtrans.

---

## 🌐 Base URL

```http
http://localhost/api
```

### 📌 General Rules

* Semua response menggunakan format **JSON**
* Endpoint **protected** wajib menggunakan header:

```http
Authorization: Bearer {token}
Content-Type: application/json
```

---

## 🔐 Authentication

### Register

```http
POST /auth/register
```

**Request Body**

```json
{
  "name": "Rahmat",
  "email": "rahmat@mail.com",
  "password": "password",
  "password_confirmation": "password"
}
```

**Response — 201**

```json
{
  "message": "Register berhasil",
  "token": "string"
}
```

---

### Login

```http
POST /auth/login
```

**Request Body**

```json
{
  "email": "rahmat@mail.com",
  "password": "password"
}
```

**Response — 200**

```json
{
  "message": "Login berhasil",
  "token": "string"
}
```

---

### Logout (Protected)

```http
POST /auth/logout
```

**Response — 200**

```json
{
  "message": "Logout berhasil"
}
```

---

## 👤 User

### Get Authenticated User

```http
GET /user
```

**Response — 200**

```json
{
  "id": 1,
  "name": "Rahmat",
  "email": "rahmat@mail.com"
}
```

---

## 📦 Products

### Get All Products

```http
GET /products
```

**Response — 200**

```json
[
  {
    "id": 1,
    "name": "Produk A",
    "price": 10000,
    "stock": 10
  }
]
```

---

### Get Product Detail

```http
GET /products/{id}
```

**Response — 200**

```json
{
  "id": 1,
  "name": "Produk A",
  "price": 10000,
  "stock": 10
}
```

---

## 🎟️ Discounts

### Create Discount

```http
POST /discounts
```

**Request Body**

```json
{
  "code": "PROMO10",
  "scope": "product",
  "type": "percentage",
  "value": 10,
  "stock": 100,
  "is_active": true,
  "start_date": "2026-01-01",
  "end_date": "2026-02-01",
  "product_ids": [1, 2]
}
```

#### Business Rules

* `scope = order` → `type` **WAJIB** `free_shipping`
* `scope = product` → `product_ids` **WAJIB**

**Response — 201**

```json
{
  "success": true,
  "message": "Diskon berhasil dibuat",
  "data": {
    "id": 1,
    "code": "PROMO10"
  }
}
```

---

### Apply Discount to Product

```http
POST /products/{product_id}/apply-discount
```

**Request Body**

```json
{
  "code": "PROMO10"
}
```

**Response — 200**

```json
{
  "success": true,
  "data": {
    "original_price": 10000,
    "final_price": 9000,
    "discount_type": "percentage",
    "discount_value": 10,
    "free_shipping": false,
    "is_discounted": true
  }
}
```

---

## 🛒 Cart

### Get Cart

```http
GET /cart
```

**Response — 200**

```json
{
  "items": [
    {
      "product_id": 1,
      "name": "Produk A",
      "price": 10000,
      "quantity": 2
    }
  ]
}
```

---

### Add Item to Cart

```http
POST /cart/items
```

**Request Body**

```json
{
  "product_id": 1,
  "quantity": 2
}
```

**Response — 201**

```json
{
  "message": "Item ditambahkan ke cart"
}
```

---

### Update Cart Item

```http
PUT /cart/items/{itemId}
```

**Request Body**

```json
{
  "quantity": 3
}
```

---

### Clear Cart

```http
DELETE /cart
```

**Response — 200**

```json
{
  "message": "Cart dikosongkan"
}
```

---

## 🚚 Checkout

### Preview Checkout

```http
POST /preview-checkout
```

**Request Body**

```json
{
  "shipping_address": "kampus1",
  "discount_code": "PROMO10"
}
```

**Response — 200**

```json
{
  "message": "Preview checkout",
  "data": {
    "items": [
      {
        "product_id": 1,
        "name": "Produk A",
        "price": 10000,
        "final_price": 9000,
        "quantity": 2,
        "subtotal": 18000
      }
    ],
    "subtotal": 20000,
    "discount_total": 2000,
    "shipping_cost": 7000,
    "free_shipping": false,
    "total": 25000
  }
}
```

---

### Checkout Final

```http
POST /checkout
```

**Request Body**

```json
{
  "shipping_address": "kampus1",
  "payment_method": "midtrans",
  "discount_code": "PROMO10"
}
```

**Response — 201**

```json
{
  "message": "Checkout berhasil",
  "data": {
    "id": 10,
    "order_code": "ORD-1700000000",
    "total": 25000,
    "status": "pending"
  }
}
```

---

## 💳 Payment (Midtrans)

### Get Snap Token

```http
GET /orders/{order_id}/snap-token
```

**Response — 200**

```json
{
  "snap_token": "string"
}
```

---

### Midtrans Callback (Public)

```http
POST /midtrans/callback
```

**Request Body**

```json
{
  "order_id": "ORD-1700000000",
  "transaction_status": "settlement"
}
```

---

## ❌ Error Response Format

```json
{
  "success": false,
  "message": "Pesan error"
}
```

---

## 📌 HTTP Status Codes

| Code | Description                |
| ---- | -------------------------- |
| 200  | Success                    |
| 201  | Created                    |
| 400  | Bad Request                |
| 401  | Unauthorized               |
| 403  | Forbidden                  |
| 422  | Validation / Business Rule |
| 500  | Internal Server Error      |
