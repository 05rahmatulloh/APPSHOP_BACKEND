# 📑 API CONTRACT – CHECKOUT & DISCOUNT

Base URL:

```
http://localhost/api
```

Semua response menggunakan format **JSON**
Semua endpoint di bawah **WAJIB login** dan menggunakan header:

```
Authorization: Bearer {token}
Content-Type: application/json
```

---

## 🛒 CHECKOUT

### 🔹 Preview Checkout

**POST** `/preview-checkout`

**Request Body**

```json
{
  "shipping_address": "string",
  "discount_code": "string | null"
}
```

**Success Response (200)**

```json
{
  "message": "Preview checkout",
  "data": {
    "success": true,
    "items": [
      {
        "product_id": 1,
        "name": "Produk A",
        "price": 100000,
        "final_price": 80000,
        "quantity": 2,
        "subtotal": 160000,
        "Data Discount": {
          "original_price": 100000,
          "final_price": 80000,
          "discount_type": "percentage",
          "discount_value": 20,
          "free_shipping": false
        }
      }
    ],
    "subtotal": 200000,
    "discount_total": 40000,
    "shipping_cost": 10000,
    "free_shipping": false,
    "total": 170000
  }
}
```

**Error Response (400)**

```json
{
  "message": "Cart kosong / Stok tidak cukup / Kode diskon tidak valid"
}
```

---

### 🔹 Checkout Final

**POST** `/checkout`

**Request Body**

```json
{
  "shipping_address": "string",
  "payment_method": "cod | transfer | midtrans",
  "discount_code": "string | null"
}
```

**Success Response (201)**

```json
{
  "message": "Checkout berhasil",
  "data": {
    "id": 1,
    "user_id": 1,
    "order_code": "ORD-1700000000",
    "subtotal": 200000,
    "discount_total": 40000,
    "shipping_cost": 10000,
    "total": 170000,
    "status": "pending",
    "payment_method": "cod",
    "shipping_address": "Kampus A",
    "created_at": "2026-01-01T00:00:00Z"
  }
}
```

**Error Response (400)**

```json
{
  "message": "Cart kosong / Stok tidak cukup / Kode diskon tidak valid"
}
```

---

## 🎟️ DISCOUNT

### 🔹 Apply Discount ke Produk

**POST** `/products/{product}/apply-discount`

**Request Body**

```json
{
  "code": "DISKON20"
}
```

**Success Response (200)**

```json
{
  "success": true,
  "data": {
    "original_price": 100000,
    "final_price": 80000,
    "discount_type": "percentage",
    "discount_value": 20,
    "free_shipping": false,
    "is_discounted": true
  }
}
```

**Success (Produk Tidak Termasuk Diskon)**

```json
{
  "success": true,
  "data": {
    "original_price": 100000,
    "final_price": 100000,
    "discount_type": null,
    "discount_value": 0,
    "free_shipping": false,
    "is_discounted": false
  }
}
```

**Error Response (400)**

```json
{
  "success": false,
  "message": "Kode diskon tidak valid"
}
```

---

### 🔹 Create Discount (Admin)

**POST** `/discounts`

**Request Body**

```json
{
  "code": "DISKON20",
  "scope": "product | order",
  "type": "percentage | nominal | free_shipping",
  "value": 20,
  "stock": 10,
  "is_active": true,
  "start_date": "2026-01-01",
  "end_date": "2026-01-31",
  "product_ids": [1,2]
}
```

**Success Response (201)**

```json
{
  "success": true,
  "message": "Diskon berhasil dibuat",
  "data": {
    "id": 1,
    "code": "DISKON20",
    "scope": "product",
    "type": "percentage",
    "value": 20,
    "stock": 10,
    "is_active": true
  }
}
```

**Error Response (422)**

```json
{
  "success": false,
  "message": "Diskon produk wajib memilih produk / Diskon order hanya boleh free shipping"
}
```
