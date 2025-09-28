<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ config('app.name', 'Laravel') }} - Register</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">{{ __('Create an Account!') }}</h1>
                            </div>

                            <form method="POST" action="{{ route('register') }}" class="user">
                                @csrf

                                <!-- Name -->
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control form-control-user"
                                        id="exampleFirstName" placeholder="{{ __('Name') }}" 
                                        value="{{ old('name') }}" required>
                                    @if ($errors->has('name'))
                                        <div class="text-danger mt-2">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Email Address -->
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control form-control-user"
                                        id="exampleInputEmail" placeholder="{{ __('Email Address') }}" 
                                        value="{{ old('email') }}" required>
                                    @if ($errors->has('email'))
                                        <div class="text-danger mt-2">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Password -->
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" name="password" class="form-control form-control-user"
                                            id="exampleInputPassword" placeholder="{{ __('Password') }}" required>
                                        @if ($errors->has('password'))
                                            <div class="text-danger mt-2">
                                                {{ $errors->first('password') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" name="password_confirmation" class="form-control form-control-user"
                                            id="exampleRepeatPassword" placeholder="{{ __('Repeat Password') }}" required>
                                        @if ($errors->has('password_confirmation'))
                                            <div class="text-danger mt-2">
                                                {{ $errors->first('password_confirmation') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Hidden role field - always client -->
                                <input type="hidden" name="role" value="client">

                                <!-- Client Information -->
                                <div id="client-info">
                                    <!-- Client name is taken from the user's name field -->
                                    <input type="hidden" name="client_name" value="{{ old('name') }}">

                                    <div class="form-group">
                                        <input type="text" name="nik" class="form-control form-control-user"
                                            id="clientNik" placeholder="{{ __('NIK') }}" 
                                            value="{{ old('nik') }}">
                                        @if ($errors->has('nik'))
                                            <div class="text-danger mt-2">
                                                {{ $errors->first('nik') }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <input type="text" name="address" class="form-control form-control-user"
                                            id="clientAddress" placeholder="{{ __('Address') }}" 
                                            value="{{ old('address') }}">
                                        @if ($errors->has('address'))
                                            <div class="text-danger mt-2">
                                                {{ $errors->first('address') }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <input type="text" name="phone" class="form-control form-control-user"
                                            id="clientPhone" placeholder="{{ __('Phone') }}" 
                                            value="{{ old('phone') }}">
                                        @if ($errors->has('phone'))
                                            <div class="text-danger mt-2">
                                                {{ $errors->first('phone') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    {{ __('Register Account') }}
                                </button>
                            </form>

                            <script>
                                document.getElementById('role').addEventListener('change', function() {
                                    const clientInfo = document.getElementById('client-info');
                                    if (this.value === 'client') {
                                        clientInfo.style.display = 'block';
                                    } else {
                                        clientInfo.style.display = 'none';
                                    }
                                });

                                // Show client info on page load if role was previously selected as client
                                window.addEventListener('DOMContentLoaded', function() {
                                    const roleSelect = document.getElementById('role');
                                    if (roleSelect.value === 'client') {
                                        document.getElementById('client-info').style.display = 'block';
                                    }
                                });
                            </script>

                            <hr>
                            <div class="text-center">
                                @if (Route::has('password.request'))
                                    <a class="small" href="{{ route('password.request') }}">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="text-center">
                                <a class="small" href="{{ route('login') }}">
                                    {{ __('Already have an account? Login!') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

</body>

</html>