@extends('admin.layouts.master')

@section('admin-main')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Register Admin</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Register Admin</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        {{-- Table here --}}
        <div class="container-fluid px-3">
            <div class="row">
                <div class="col-12">
                    <!-- /.card-header -->
                    @if ($errors->any())
                        <div class="alert alert-danger my-2">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <form id="adminRegisterForm" action="{{ route('admin.register') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Enter category name" value="{{ old('name') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" class="form-control" id="email"
                                            placeholder="Admin email" value="{{ old('email') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" class="form-control pass-box" id="password"
                                            placeholder="Admin password" value="{{ old('password') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm Password</label>
                                        <input type="password" name="confirm_password" class="form-control pass-box"
                                            id="confirm_password" placeholder="Admin confirm password"
                                            value="{{ old('confirm_password') }}">
                                    </div>
                                </div>
                                </ <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                                    <button type="button" id="show-pass" data-toggle="0" class="btn btn-primary">Show
                                        Password</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $("#email").blur(
            function() {
                const email = $(this).val();
                if (email.length !== 0) {
                    $.ajax({
                        url: `{{ url('admin/check-email') }}/${email}`,
                        type: "get",
                        beforeSend: function() {
                            $("#submit").attr('disabled', true);
                            $("#email").next("span").remove();
                        },
                        success: function(response) {
                            if (response.status) {
                                $("#submit").attr('disabled', false);
                            } else {
                                $("#email").after(
                                    "<span class='text-danger d-block pl-2'> Email already exists </span>"
                                );
                            }
                        }
                    })
                }
            }
        )
        // 0: Password is hidden
        // 1: Password is visible
        $("#show-pass").click(
            function() {
                const toggle = $(this).data('toggle'); //get
                if (toggle == 1) {
                    $(this)
                        .removeClass("btn-warning")
                        .addClass("btn-primary")
                        .text("Show passoword")
                        .data('toggle', 0); //set
                    $(".pass-box").attr("type", "password");
                } else {
                    $(this)
                        .removeClass("btn-primary")
                        .addClass("btn-warning")
                        .text("Hide passoword")
                        .data('toggle', 1); //set
                    $(".pass-box").attr("type", "text");
                }
            }
        )
    </script>
    <!-- jquery-validation -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script>
        $(function() {
            $('#adminRegisterForm').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 100,
                    },
                    email: {
                        required: true,
                        maxlength: 100,
                        email: true
                    },
                    password: {
                        required: true
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#password"
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endpush
