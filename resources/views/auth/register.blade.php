@extends('layouts.auth')

@section('content')
<div class="bg-body-dark bg-pattern" style="background-image: url('assets/media/various/bg-pattern-inverse.png');">
    <div class="row mx-0 justify-content-center">
        <div class="hero-static col-lg-6 col-xl-4">
            <div class="content content-full overflow-hidden">
                <div class="py-30 text-center">
                    <a class="link-effect font-w700" href="index.php">
                        <i class="si si-fire"></i>
                        <span class="font-size-xl text-primary-dark">SiS</span><span class="font-size-xl">Cloud</span>
                    </a>
                    <h1 class="h4 font-w700 mt-30 mb-10">Create New Account</h1>
                    <h2 class="h5 font-w400 text-muted mb-0">We’re excited to have you on board!</h2>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="js-validation-signup" action="{{ route('register') }}" method="post" novalidate="novalidate" autocomplete="off">
                    @csrf
                    <div class="block block-themed block-rounded block-shadow">
                        <div class="block-header bg-gd-emerald">
                            <h3 class="block-title">Please add your details</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option">
                                    <i class="si si-wrench"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="form-group row is-invalid">
                                <div class="col-12">
                                    <label for="signup-username">Username</label>
                                    <input type="text" class="form-control" id="signup-username" name="username" placeholder="eg: john_smith" aria-describedby="signup-username-error" aria-invalid="true">
                                <div id="signup-username-error" class="invalid-feedback animated fadeInDown">Please enter a username</div></div>
                            </div>
                             {{-- <div class="form-group row is-invalid">
                                <div class="col-12">
                                    <label for="signup-first_name">First name</label>
                                    <input type="text" class="form-control" id="signup-first_name" name="first_name" placeholder="eg: john" aria-describedby="signup-first_name-error" aria-invalid="true">
                                <div id="signup-first_name-error" class="invalid-feedback animated fadeInDown">Please enter a first name</div></div>
                            </div>
                             <div class="form-group row is-invalid">
                                <div class="col-12">
                                    <label for="signup-last_name">Last name</label>
                                    <input type="text" class="form-control" id="signup-last_name" name="last_name" placeholder="eg: smith" aria-describedby="signup-last_name-error" aria-invalid="true">
                                <div id="signup-last_name-error" class="invalid-feedback animated fadeInDown">Please enter a last name</div></div>
                            </div> --}}
                            <div class="form-group row is-invalid">
                                <div class="col-12">
                                    <label for="signup-email">Email</label>
                                    <input type="email" class="form-control" id="signup-email" name="email" placeholder="eg: john@example.com" aria-describedby="signup-email-error">
                                <div id="signup-email-error" class="invalid-feedback animated fadeInDown">Please enter a valid email address</div></div>
                            </div>
                            <div class="form-group row is-invalid">
                                <div class="col-12">
                                    <label for="signup-password">Password</label>
                                    <input type="password" class="form-control" id="signup-password" name="password" placeholder="********" aria-describedby="signup-password-error">
                                <div id="signup-password-error" class="invalid-feedback animated fadeInDown">Please provide a password</div></div>
                            </div>
                            <div class="form-group row is-invalid">
                                <div class="col-12">
                                    <label for="signup-password-confirm">Password Confirmation</label>
                                    <input type="password" class="form-control" id="signup-password-confirm" name="password_confirmation" placeholder="********" aria-describedby="signup-password-confirm-error">
                                <div id="signup-password-confirm-error" class="invalid-feedback animated fadeInDown">Please provide a password</div></div>
                            </div>
                            <div class="form-group row mb-0 is-invalid">
                                {{-- <div class="col-sm-6 push">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="signup-terms" name="signup-terms" aria-describedby="signup-terms-error">
                                        <label class="custom-control-label" for="signup-terms">I agree to Terms &amp; Conditions</label>
                                    </div>
                                    <div id="signup-terms-error" class="invalid-feedback animated fadeInDown">You must agree to the service terms!</div>
                                </div> --}}
                                <div class="col-12 push text-center">
                                    <button type="submit" class="btn btn-alt-success">
                                        <i class="fa fa-plus mr-10"></i> Create Account
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="block-content bg-body-light">
                            <div class="form-group text-center">
                                {{-- <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="#" data-toggle="modal" data-target="#modal-terms">
                                    <i class="fa fa-book text-muted mr-5"></i> Read Terms
                                </a> --}}
                                <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="/login">
                                    <i class="fa fa-user text-muted mr-5"></i> Sign In
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
