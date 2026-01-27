<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">

                <div class="card shadow">
                    <div class="card-header text-center bg-dark text-white">
                        <strong>LOGIN ADMIN</strong>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('login.process') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    autofocus>

                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror">

                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button class="btn btn-dark w-100">
                                Login
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>

</body>

</html>
