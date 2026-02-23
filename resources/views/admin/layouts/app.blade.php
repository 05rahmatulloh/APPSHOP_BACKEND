<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

   <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/admin/products') }}">Admin Panel</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                {{-- Products --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Products
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('products.index') }}">
                                List Products
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('products.create') }}">
                                Tambah Product
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Discounts --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/discounts/create') }}">
                        Buat Discount
                    </a>
                </li>

                {{-- Midtrans Test (optional) --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/midtrans-test/1') }}">
                        Midtrans Test
                    </a>
                </li>

            </ul>

            {{-- Logout --}}
            <form action="{{ route('logout') }}" method="POST" class="d-flex">
                @csrf
                <button class="btn btn-sm btn-danger">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

</body>

</html>
