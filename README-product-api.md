# Product Management API Documentation

This document provides information about the RESTful API endpoints for managing products in the KPRI system.

## Authentication

All API endpoints require JWT authentication with a valid token. The user must have the "kpri admin" role to access these endpoints.

## Base URL

```
/api/admin/products
```

## Endpoints

### List All Products

- **URL**: `/api/admin/products`
- **Method**: `GET`
- **Description**: Retrieves a paginated list of all products with their categories.
- **Query Parameters**:
  - `category` (optional) - Filter products by category ID
  - `search` (optional) - Search products by name
  - `sort_by` (optional) - Field to sort by (nama_produk, harga_produk, stok_produk)
  - `sort_direction` (optional) - Sort direction (asc, desc)
  - `per_page` (optional) - Number of items per page (default: 15)
- **Response**: 
  ```json
  {
    "status": "success",
    "data": {
      "current_page": 1,
      "data": [
        {
          "id_produk": 1,
          "gambar_produk": "products/example.jpg",
          "id_kategori": 1,
          "nama_produk": "Example Product",
          "harga_produk": 50000,
          "stok_produk": 10,
          "deskripsi_produk": "This is an example product",
          "category": {
            "id_kategori": 1,
            "kategori": "Example Category"
          }
        },
        // ...more products
      ],
      "from": 1,
      "last_page": 1,
      "per_page": 15,
      "to": 1,
      "total": 1
    }
  }
  ```

### Get a Specific Product

- **URL**: `/api/admin/products/{id}`
- **Method**: `GET`
- **Description**: Retrieves a specific product by its ID with its category.
- **Parameters**:
  - `id` (path) - The ID of the product
- **Response**:
  ```json
  {
    "status": "success",
    "data": {
      "id_produk": 1,
      "gambar_produk": "products/example.jpg",
      "id_kategori": 1,
      "nama_produk": "Example Product",
      "harga_produk": 50000,
      "stok_produk": 10,
      "deskripsi_produk": "This is an example product",
      "category": {
        "id_kategori": 1,
        "kategori": "Example Category"
      }
    }
  }
  ```

### Create a New Product

- **URL**: `/api/admin/products`
- **Method**: `POST`
- **Description**: Creates a new product.
- **Content-Type**: `multipart/form-data`
- **Parameters**:
  - `nama_produk` (required) - Name of the product (max 120 characters)
  - `id_kategori` (required) - Category ID
  - `harga_produk` (required) - Price of the product
  - `stok_produk` (required) - Stock quantity of the product
  - `deskripsi_produk` (optional) - Description of the product
  - `gambar_produk` (optional) - Product image file (max 2MB)
- **Response**:
  ```json
  {
    "status": "success",
    "message": "Product created successfully",
    "data": {
      "id_produk": 2,
      "gambar_produk": "products/new_product.jpg",
      "id_kategori": 1,
      "nama_produk": "New Product",
      "harga_produk": 75000,
      "stok_produk": 5,
      "deskripsi_produk": "This is a new product"
    }
  }
  ```

### Update a Product

- **URL**: `/api/admin/products/{id}`
- **Method**: `POST`
- **Description**: Updates an existing product.
- **Content-Type**: `multipart/form-data`
- **Parameters**:
  - `id` (path) - The ID of the product to update
  - `nama_produk` (optional) - Name of the product
  - `id_kategori` (optional) - Category ID
  - `harga_produk` (optional) - Price of the product
  - `stok_produk` (optional) - Stock quantity of the product
  - `deskripsi_produk` (optional) - Description of the product
  - `gambar_produk` (optional) - New product image file
- **Response**:
  ```json
  {
    "status": "success",
    "message": "Product updated successfully",
    "data": {
      "id_produk": 2,
      "gambar_produk": "products/updated_product.jpg",
      "id_kategori": 2,
      "nama_produk": "Updated Product",
      "harga_produk": 80000,
      "stok_produk": 8,
      "deskripsi_produk": "This is an updated product"
    }
  }
  ```

### Delete a Product

- **URL**: `/api/admin/products/{id}`
- **Method**: `DELETE`
- **Description**: Deletes a product and its associated image.
- **Parameters**:
  - `id` (path) - The ID of the product to delete
- **Response**:
  ```json
  {
    "status": "success",
    "message": "Product deleted successfully"
  }
  ```

### Get All Product Categories

- **URL**: `/api/admin/product-categories`
- **Method**: `GET`
- **Description**: Retrieves all product categories.
- **Response**:
  ```json
  {
    "status": "success",
    "data": [
      {
        "id_kategori": 1,
        "kategori": "Category 1"
      },
      {
        "id_kategori": 2,
        "kategori": "Category 2"
      }
    ]
  }
  ```

### Add Product to Promotions

- **URL**: `/api/admin/products/{id}/promotions`
- **Method**: `POST`
- **Description**: Adds or updates the promotions for a product.
- **Content-Type**: `application/json`
- **Parameters**:
  - `id` (path) - The ID of the product
  - `promotions` (required) - Array of promotion IDs
    ```json
    {
      "promotions": [1, 2, 3]
    }
    ```
- **Response**:
  ```json
  {
    "status": "success",
    "message": "Product promotions updated successfully",
    "data": {
      "id_produk": 1,
      "nama_produk": "Example Product",
      "promotions": [
        {
          "id_promo": 1,
          "judul_promo": "Summer Sale",
          "tgl_start": "2023-06-01",
          "tgl_end": "2023-08-31",
          "tipe_diskon": "persen",
          "nilai_diskon": 20,
          "status": "aktif"
        },
        // ...more promotions
      ]
    }
  }
  ```

### Get Product Promotions

- **URL**: `/api/admin/products/{id}/promotions`
- **Method**: `GET`
- **Description**: Retrieves all promotions for a specific product.
- **Parameters**:
  - `id` (path) - The ID of the product
- **Response**:
  ```json
  {
    "status": "success",
    "data": [
      {
        "id_promo": 1,
        "judul_promo": "Summer Sale",
        "tgl_start": "2023-06-01",
        "tgl_end": "2023-08-31",
        "tipe_diskon": "persen",
        "nilai_diskon": 20,
        "status": "aktif"
      },
      // ...more promotions
    ]
  }
  ```

## Error Responses

All endpoints may return the following error responses:

- **401 Unauthorized**: When the user is not authenticated
- **403 Forbidden**: When the user does not have the required role
- **404 Not Found**: When the requested resource is not found
- **422 Unprocessable Entity**: When validation fails for the input data 