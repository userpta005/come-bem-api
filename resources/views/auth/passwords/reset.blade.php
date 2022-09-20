<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recuperar senha | {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --input-padding-x: 1.5rem;
            --input-padding-y: 0.75rem;
        }

        body {
            font-family: "Poppins", sans-serif;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            text-align: left;
        }

        .login,
        .image {
            min-height: 100vh;
        }



        .login-heading {
            font-weight: 300;
        }

        .btn-login {
            font-size: 0.9rem;
            letter-spacing: 0.05rem;
            padding: 0.75rem 1rem;
            border-radius: 2rem;
            background: #003b19;
            border-color: #003b19;
        }

        .btn-login:hover {
            background: #04b61c;
            border-color: #04b61c;
        }

        .banner {
            background-image: url('{{ asset("images/wallpaper.jpg") }}');
            background-size: cover;
            background-position: center;
            padding-right: 0px;
        }

        .banner video {
            position: relative;
            top: 0;
            left: 0;
            object-fit: cover;
            width: 100%;
        }

        .banner .content {
            position: relative;
            z-index: 1;
            max-width: 1000px;
            margin: 0 auto;
        }
    </style>

</head>

<body>

    <form class="form" method="post" action="{{ route('password.update') }}">
        @csrf
        <div class="container-fluid">
            <div class="row no-gutter">
                <div class="col-md-8 col-lg-6">
                    <div class="login d-flex align-items-center py-5">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-9 col-lg-8 mx-auto">
                                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid mb-5"
                                        width="250px">
                                    <h5 class="login-heading mb-4">Resetar Senha
                                    </h5>
                                    @include('alerts.success')
                                    @include('alerts.error')
                                    <form class="form" method="post" action="{{ route('password.update') }}">
                                        @csrf
                                        @include('alerts.success')
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <div class="input-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                            </div>
                                            <input type="email" name="email"
                                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                placeholder="{{ __('Email') }}">
                                            @include('alerts.feedback', ['field' => 'email'])
                                        </div>
                                        <div
                                            class="mt-3 input-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-key"></i>
                                                </div>
                                            </div>
                                            <input type="password" name="password"
                                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                placeholder="Senha">
                                            @include('alerts.feedback', ['field' => 'password'])
                                        </div>
                                        <div class="mt-3 input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-key"></i>
                                                </div>
                                            </div>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                placeholder="Confirmar Senha">
                                        </div>
                                        <button
                                            class=" my-5 btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2"
                                            type="submit">Enviar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image banner">
                    <video autoplay muted loop>
                        <source src='{{asset("img/video.mp4")}}' type="video/mp4">
                    </video>
                </div>
            </div>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
</body>

</html>