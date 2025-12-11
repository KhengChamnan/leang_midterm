# MIDTERM EXAM

**Duration:** 90 min

## INSTRUCTION

Build a Laravel application to manage products with images, using
session storage instead of a database.

## Features

### 1. Product Fields

-   **id** (unique)
-   **name** (string)
-   **price** (decimal)
-   **image**

### 2. Product List Page

-   Display products in a Bootstrap table with columns: Image, Name,
    Price, Actions\
-   Search products by name\
-   Sort by Name or Price (ascending/descending)\
-   Bootstrap styling and hover effects\
-   Edit/Delete buttons with icons\
-   Delete confirmation modal\
-   Success/Error alerts after actions

### 3. Product Form Page

-   Add/Edit product with image upload\
-   Preview existing image when editing\
-   Bootstrap form validation

### 4. Image Handling

-   Upload images to `public/images`\
-   Default placeholder if no image uploaded\
-   Validate file type and size (jpg, jpeg, png, gif; max 2MB)

### 5. Session Storage

-   Store products in PHP session\
-   CRUD operations update the session array

### 6. Expected Results

-   Home page (table with product list)\
-   Add new product page\
-   Updated home page after adding product\
-   Delete confirmation modal\
-   Update product page
