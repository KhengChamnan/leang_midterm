<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header-bar {
            background: #007bff;
            padding: 20px;
            color: white;
            margin-bottom: 30px;
        }
        .table-hover tbody tr:hover {
            background-color: #f0f0f0;
        }
        .product-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
        }
        .table thead th {
            background-color: #2c3e50;
            color: white;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            padding: 15px;
        }
        .table tbody td {
            vertical-align: middle;
            text-align: center;
            padding: 15px;
        }
        .table tbody td:first-child {
            text-align: center;
        }
        .btn-action {
            margin: 0 3px;
        }
        .search-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Header Bar -->
    <div class="header-bar">
        <div class="container">
            <h1 class="mb-0"><i class="bi bi-box-seam"></i> Product Manager</h1>
        </div>
    </div>

    <div class="container">
        <!-- Success/Error Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Search Section -->
        <div class="search-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="text-primary mb-0">All Products</h3>
                <a href="{{ route('products.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Add New Product
                </a>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        @if(count($products) > 0)
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name 
                                <a href="{{ route('products.index', ['sort' => 'name', 'order' => 'asc', 'search' => request('search')]) }}" class="text-white">
                                    <i class="bi bi-arrow-down"></i>
                                </a>
                                <a href="{{ route('products.index', ['sort' => 'name', 'order' => 'desc', 'search' => request('search')]) }}" class="text-white">
                                    <i class="bi bi-arrow-up"></i>
                                </a>
                            </th>
                            <th>Price 
                                <a href="{{ route('products.index', ['sort' => 'price', 'order' => 'asc', 'search' => request('search')]) }}" class="text-white">
                                    <i class="bi bi-arrow-down"></i>
                                </a>
                                <a href="{{ route('products.index', ['sort' => 'price', 'order' => 'desc', 'search' => request('search')]) }}" class="text-white">
                                    <i class="bi bi-arrow-up"></i>
                                </a>
                            </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('images/' . $product['image']) }}" alt="{{ $product['name'] }}" class="product-img">
                                        </td>
                                        <td>{{ $product['name'] }}</td>
                                        <td>${{ number_format($product['price'], 2) }}</td>
                                        <td>
                                            <a href="{{ route('products.edit', $product['id']) }}" class="btn btn-sm btn-warning btn-action">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger btn-action" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product['id'] }}">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal{{ $product['id'] }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete <strong>{{ $product['name'] }}</strong>?</p>
                                                    <p class="text-muted">This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form method="POST" action="{{ route('products.destroy', $product['id']) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
            </div>
        @else
            <div class="table-container">
                <div class="alert alert-info text-center mb-0">
                    <i class="bi bi-info-circle"></i> No products found. <a href="{{ route('products.create') }}">Add your first product</a>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
