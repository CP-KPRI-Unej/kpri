# Download Item API Documentation

This document provides information about the RESTful API endpoints for managing download items in the KPRI system.

## Authentication

All API endpoints require JWT authentication with a valid token. The user must have the "kpri admin" role to access these endpoints.

## Base URL

```
/api/admin/downloads
```

## Endpoints

### List All Download Items

- **URL**: `/api/admin/downloads`
- **Method**: `GET`
- **Description**: Retrieves a list of all download items ordered by their sequence number.
- **Response**: 
  ```json
  {
    "status": "success",
    "data": [
      {
        "id_download_item": 1,
        "id_user": 1,
        "nama_item": "Example Document",
        "path_file": "downloads/example.pdf",
        "status": "Active",
        "tgl_upload": "2023-06-15",
        "urutan": 1
      },
      // ...more items
    ]
  }
  ```

### Get a Specific Download Item

- **URL**: `/api/admin/downloads/{id}`
- **Method**: `GET`
- **Description**: Retrieves a specific download item by its ID.
- **Parameters**:
  - `id` (path) - The ID of the download item
- **Response**:
  ```json
  {
    "status": "success",
    "data": {
      "id_download_item": 1,
      "id_user": 1,
      "nama_item": "Example Document",
      "path_file": "downloads/example.pdf",
      "status": "Active",
      "tgl_upload": "2023-06-15",
      "urutan": 1
    }
  }
  ```

### Create a New Download Item

- **URL**: `/api/admin/downloads`
- **Method**: `POST`
- **Description**: Creates a new download item.
- **Content-Type**: `multipart/form-data`
- **Parameters**:
  - `nama_item` (required) - Name of the download item (max 120 characters)
  - `file` (required) - The file to upload (max 10MB)
  - `status` (required) - Status of the item ('Active' or 'Inactive')
  - `urutan` (optional) - Order/sequence number
- **Response**:
  ```json
  {
    "status": "success",
    "message": "Download item created successfully",
    "data": {
      "id_download_item": 2,
      "id_user": 1,
      "nama_item": "New Document",
      "path_file": "downloads/new_document.pdf",
      "status": "Active",
      "tgl_upload": "2023-06-16",
      "urutan": 2
    }
  }
  ```

### Update a Download Item

- **URL**: `/api/admin/downloads/{id}`
- **Method**: `POST`
- **Description**: Updates an existing download item.
- **Content-Type**: `multipart/form-data`
- **Parameters**:
  - `id` (path) - The ID of the download item to update
  - `nama_item` (optional) - Name of the download item
  - `file` (optional) - New file to upload
  - `status` (optional) - Status of the item ('Active' or 'Inactive')
  - `urutan` (optional) - Order/sequence number
- **Response**:
  ```json
  {
    "status": "success",
    "message": "Download item updated successfully",
    "data": {
      "id_download_item": 2,
      "id_user": 1,
      "nama_item": "Updated Document",
      "path_file": "downloads/updated_document.pdf",
      "status": "Active",
      "tgl_upload": "2023-06-16",
      "urutan": 3
    }
  }
  ```

### Delete a Download Item

- **URL**: `/api/admin/downloads/{id}`
- **Method**: `DELETE`
- **Description**: Deletes a download item and its associated file.
- **Parameters**:
  - `id` (path) - The ID of the download item to delete
- **Response**:
  ```json
  {
    "status": "success",
    "message": "Download item deleted successfully"
  }
  ```

### Update Download Items Order

- **URL**: `/api/admin/downloads/update-order`
- **Method**: `POST`
- **Description**: Updates the order/sequence of multiple download items.
- **Content-Type**: `application/json`
- **Parameters**:
  - `items` (required) - Array of items with their new order
    ```json
    {
      "items": [
        {"id": 1, "urutan": 3},
        {"id": 2, "urutan": 1},
        {"id": 3, "urutan": 2}
      ]
    }
    ```
- **Response**:
  ```json
  {
    "status": "success",
    "message": "Download items order updated successfully"
  }
  ```

## Error Responses

All endpoints may return the following error responses:

- **401 Unauthorized**: When the user is not authenticated
- **403 Forbidden**: When the user does not have the required role
- **404 Not Found**: When the requested resource is not found
- **422 Unprocessable Entity**: When validation fails for the input data 