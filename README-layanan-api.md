 # Layanan (Service) API Documentation

This document provides details on the RESTful API endpoints for managing services (layanan). All endpoints require authentication with JWT token and admin privileges.

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

### List Services

Get a list of services, optionally filtered by service type.

**URL**: `GET /layanan/{jenisLayananId?}`

**Parameters**:
- `jenisLayananId` (optional): Filter services by this service type ID

**Response**:
```json
{
  "status": "success",
  "data": [
    {
      "id_layanan": 1,
      "id_jenis_layanan": 1,
      "judul_layanan": "Simpanan Pokok",
      "deskripsi_layanan": "Simpanan yang dibayarkan sekali selama menjadi anggota",
      "jenis_layanan": {
        "id_jenis_layanan": 1,
        "nama_layanan": "Unit Simpan Pinjam"
      }
    },
    // More services...
  ]
}
```

### Get Service Details

Get details of a specific service.

**URL**: `GET /layanan/detail/{id}`

**Response**:
```json
{
  "status": "success",
  "data": {
    "id_layanan": 1,
    "id_jenis_layanan": 1,
    "judul_layanan": "Simpanan Pokok",
    "deskripsi_layanan": "Simpanan yang dibayarkan sekali selama menjadi anggota",
    "jenis_layanan": {
      "id_jenis_layanan": 1,
      "nama_layanan": "Unit Simpan Pinjam"
    }
  }
}
```

### Create Service

Create a new service.

**URL**: `POST /layanan`

**Body Parameters**:
```json
{
  "id_jenis_layanan": 1,
  "judul_layanan": "Simpanan Pokok",
  "deskripsi_layanan": "Simpanan yang dibayarkan sekali selama menjadi anggota"
}
```

**Response**:
```json
{
  "status": "success",
  "message": "Layanan created successfully",
  "data": {
    "id_layanan": 1,
    "id_jenis_layanan": 1,
    "judul_layanan": "Simpanan Pokok",
    "deskripsi_layanan": "Simpanan yang dibayarkan sekali selama menjadi anggota",
    "jenis_layanan": {
      "id_jenis_layanan": 1,
      "nama_layanan": "Unit Simpan Pinjam"
    }
  }
}
```

### Update Service

Update an existing service.

**URL**: `POST /layanan/{id}`

**Body Parameters** (all are optional):
```json
{
  "id_jenis_layanan": 1,
  "judul_layanan": "Simpanan Pokok (Updated)",
  "deskripsi_layanan": "Simpanan yang dibayarkan sekali selama menjadi anggota koperasi"
}
```

**Response**:
```json
{
  "status": "success",
  "message": "Layanan updated successfully",
  "data": {
    "id_layanan": 1,
    "id_jenis_layanan": 1,
    "judul_layanan": "Simpanan Pokok (Updated)",
    "deskripsi_layanan": "Simpanan yang dibayarkan sekali selama menjadi anggota koperasi",
    "jenis_layanan": {
      "id_jenis_layanan": 1,
      "nama_layanan": "Unit Simpan Pinjam"
    }
  }
}
```

### Delete Service

Delete a service.

**URL**: `DELETE /layanan/{id}`

**Response**:
```json
{
  "status": "success",
  "message": "Layanan deleted successfully"
}
```

### Get All Service Types

Get a list of all service types.

**URL**: `GET /layanan/jenis`

**Response**:
```json
{
  "status": "success",
  "data": [
    {
      "id_jenis_layanan": 1,
      "nama_layanan": "Unit Simpan Pinjam"
    },
    {
      "id_jenis_layanan": 2,
      "nama_layanan": "Unit Jasa"
    },
    // More service types...
  ]
}
```

### Get Service Type with Services

Get a specific service type with its associated services.

**URL**: `GET /layanan/jenis/{id}`

**Response**:
```json
{
  "status": "success",
  "data": {
    "id_jenis_layanan": 1,
    "nama_layanan": "Unit Simpan Pinjam",
    "layanan": [
      {
        "id_layanan": 1,
        "id_jenis_layanan": 1,
        "judul_layanan": "Simpanan Pokok",
        "deskripsi_layanan": "Simpanan yang dibayarkan sekali selama menjadi anggota"
      },
      // More services of this type...
    ]
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