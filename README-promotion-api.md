# Promotion API Documentation

This document provides details on the RESTful API endpoints for managing promotions. All endpoints require authentication with JWT token and admin privileges.

## Authentication

All API requests require a valid JWT token to be included in the `Authorization` header:

```
Authorization: Bearer {your_jwt_token}
```

## Base URL

```
/api/admin/
```

## Error Handling

All endpoints return standard error responses with appropriate HTTP status codes:

```json
{
  "status": "error",
  "message": "Error message description",
  "errors": { /* Validation errors if applicable */ }
}
```

## Endpoints

### List Promotions

Get a paginated list of promotions with optional filtering and sorting.

**URL**: `GET /promotions`

**Query Parameters**:
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 10)
- `sort_by`: Field to sort by (options: id_promo, judul_promo, tgl_start, tgl_end, status, nilai_diskon)
- `sort_direction`: Sort direction (asc or desc, default: desc)
- `status`: Filter by status (aktif, nonaktif, berakhir)
- `search`: Search term to filter promotions by title
- `start_date`: Filter promotions that start on or after this date (YYYY-MM-DD)
- `end_date`: Filter promotions that end on or before this date (YYYY-MM-DD)

**Response**:
```json
{
  "status": "success",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id_promo": 1,
        "id_user": 1,
        "judul_promo": "Summer Sale",
        "tgl_start": "2023-06-01",
        "tgl_end": "2023-08-31",
        "tipe_diskon": "persen",
        "nilai_diskon": 20,
        "status": "aktif"
      },
      // More promotions...
    ],
    "from": 1,
    "last_page": 5,
    "per_page": 10,
    "to": 10,
    "total": 50
  }
}
```

### Get Promotion Details

Get details of a specific promotion including associated products.

**URL**: `GET /promotions/{id}`

**Response**:
```json
{
  "status": "success",
  "data": {
    "id_promo": 1,
    "id_user": 1,
    "judul_promo": "Summer Sale",
    "tgl_start": "2023-06-01",
    "tgl_end": "2023-08-31",
    "tipe_diskon": "persen",
    "nilai_diskon": 20,
    "status": "aktif",
    "products": [
      {
        "id_produk": 101,
        "nama_produk": "Product Name",
        "harga_produk": 150000,
        "gambar_produk": "products/image.jpg",
        "pivot": {
          "id_promo": 1,
          "id_produk": 101
        }
      },
      // More products...
    ]
  }
}
```

### Create Promotion

Create a new promotion.

**URL**: `POST /promotions`

**Body Parameters**:
```json
{
  "judul_promo": "Summer Sale",
  "tgl_start": "2023-06-01",
  "tgl_end": "2023-08-31",
  "tipe_diskon": "persen",
  "nilai_diskon": 20,
  "status": "aktif",
  "products": [101, 102, 103] // Optional array of product IDs
}
```

**Response**:
```json
{
  "status": "success",
  "message": "Promotion created successfully",
  "data": {
    "id_promo": 1,
    "id_user": 1,
    "judul_promo": "Summer Sale",
    "tgl_start": "2023-06-01",
    "tgl_end": "2023-08-31",
    "tipe_diskon": "persen",
    "nilai_diskon": 20,
    "status": "aktif",
    "products": [
      // Associated products...
    ]
  }
}
```

### Update Promotion

Update an existing promotion.

**URL**: `POST /promotions/{id}`

**Body Parameters** (all are optional):
```json
{
  "judul_promo": "Updated Summer Sale",
  "tgl_start": "2023-06-15",
  "tgl_end": "2023-09-15",
  "tipe_diskon": "persen",
  "nilai_diskon": 25,
  "status": "aktif",
  "products": [101, 102, 103, 104] // Optional array of product IDs
}
```

**Response**:
```json
{
  "status": "success",
  "message": "Promotion updated successfully",
  "data": {
    "id_promo": 1,
    "id_user": 1,
    "judul_promo": "Updated Summer Sale",
    "tgl_start": "2023-06-15",
    "tgl_end": "2023-09-15",
    "tipe_diskon": "persen",
    "nilai_diskon": 25,
    "status": "aktif",
    "products": [
      // Updated associated products...
    ]
  }
}
```

### Delete Promotion

Delete a promotion.

**URL**: `DELETE /promotions/{id}`

**Response**:
```json
{
  "status": "success",
  "message": "Promotion deleted successfully"
}
```

### Get Available Products

Get a list of all products that can be associated with promotions.

**URL**: `GET /available-products`

**Response**:
```json
{
  "status": "success",
  "data": [
    {
      "id_produk": 101,
      "nama_produk": "Product Name",
      "id_kategori": 1,
      "harga_produk": 150000,
      "gambar_produk": "products/image.jpg",
      "category": {
        "id_kategori": 1,
        "kategori": "Category Name"
      }
    },
    // More products...
  ]
}
```

### Get Promotion Products

Get a list of products associated with a specific promotion.

**URL**: `GET /promotions/{id}/products`

**Response**:
```json
{
  "status": "success",
  "data": [
    {
      "id_produk": 101,
      "nama_produk": "Product Name",
      "harga_produk": 150000,
      "gambar_produk": "products/image.jpg",
      "pivot": {
        "id_promo": 1,
        "id_produk": 101
      }
    },
    // More products...
  ]
}
```

### Add Products to Promotion

Add products to an existing promotion.

**URL**: `POST /promotions/{id}/products`

**Body Parameters**:
```json
{
  "products": [105, 106, 107] // Array of product IDs to add
}
```

**Response**:
```json
{
  "status": "success",
  "message": "Products added to promotion successfully",
  "data": {
    // Updated promotion with all products...
  }
}
```

### Remove Products from Promotion

Remove products from an existing promotion.

**URL**: `DELETE /promotions/{id}/products`

**Body Parameters**:
```json
{
  "products": [105, 106] // Array of product IDs to remove
}
```

**Response**:
```json
{
  "status": "success",
  "message": "Products removed from promotion successfully",
  "data": {
    // Updated promotion with remaining products...
  }
}
```

## Status Codes

The API uses the following HTTP status codes:

- `200 OK`: The request was successful
- `201 Created`: A new resource was created successfully
- `400 Bad Request`: The request could not be understood or was missing required parameters
- `401 Unauthorized`: Authentication failed or user doesn't have permissions
- `404 Not Found`: Resource was not found
- `422 Unprocessable Entity`: Validation errors
- `500 Internal Server Error`: Server error occurred 