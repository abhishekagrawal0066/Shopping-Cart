    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Shop Homepage</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href={{ asset('assets/favicon.ico') }} />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href={{ asset('css/styles.css') }} rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="{{ route('home') }}">Shopping</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="{{ route('home') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#!">About</a></li>
                    </ul>
                    <form class="d-flex">
                        <a class="btn btn-outline-dark" href="{{ route('cart.index') }}">
                            <i class="bi-cart-fill me-1"></i>
                            Cart
                            <span class="badge bg-dark text-white ms-1 rounded-pill" id="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                        </a>
                    </form>
                    <div class="d-flex">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                            @guest
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                            @else
                                <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">Logout</a>
                                </li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                                <li class="nav-item"><a class="nav-link" href="#">Welcome, {{ Auth::user()->name }}</a></li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Header-->
        <header class="bg-dark py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Shopping</h1>
                    <p class="lead fw-normal text-white-50 mb-0">Welcome</p>
                </div>
            </div>
        </header>
        <!-- Section-->
        @yield('content')
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2024</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <!-- Update Cart Script -->
        <script>
            $(document).on('change', '.cart-quantity', function() {
                var id = $(this).data('id');
                var quantity = $(this).val();
                
                $.ajax({
                    url: '{{ route('cart.update') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#cart-count').text(Object.keys(response.cart).length);
                            alert(response.message);
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        
            $(document).on('click', '.remove-from-cart', function() {
                var id = $(this).data('id');
                
                $.ajax({
                    url: '{{ route('cart.remove') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#cart-count').text(Object.keys(response.cart).length);
                            alert(response.message);
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        </script>
    </body>
    </html>
