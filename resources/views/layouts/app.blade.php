<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="{{ asset('uploads/books/logo.png') }}"/>
        <title>Book Review App</title>
        <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    </head>
    <body class="bg-light">
        <div class="container-fluid shadow-lg header">
            <div class="container">
                <div class="d-flex justify-content-between">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('uploads/books/logo.png') }}" alt="" width="130px" height="100px">
                </a>
                    <div class="d-flex align-items-center navigation">
                        @if(Auth::check())
                            <a href="{{ route('account.profile')}}" class="text-white">My account</a>
                        @else
                            <a href="{{ route('account.login')}}" class="text-white">Login</a>
                            <a href="{{ route('account.register')}}" class="text-white ps-2">Register</a>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        @yield('content')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        @yield('script')
    </body>
</html>