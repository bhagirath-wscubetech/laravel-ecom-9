@extends('admin.layouts.master')

@section('admin-main')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create City</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Create City</li>
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
                            <form id="cityForm" action="{{ route('admin.city.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Enter city name" value="{{ old('name') }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Country</label>
                                                <select name="country" id="country" class="form-control">
                                                    <option value="0">Select a country</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->country_id }}">
                                                            {{ $country->country_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">State</label>
                                                <select name="state" id="state" class="form-control" disabled>
                                                    <option value="0">Select a country first</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
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
    <!-- jquery-validation -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script>
        $("#country").change(
            function() {
                const cId = $(this).val();
                if (cId == 0) {
                    $("#state").attr("disabled", true);
                } else {
                    $.ajax({
                        url: `{{ url('/admin/state/get-states/${cId}') }}`,
                        type: "get",
                        beforeSend: function() {
                            $("#state").attr("disabled", true);
                        },
                        success: function(response) {
                            console.log(response)
                            if (response.status == 1) {
                                const options = response.data.map(
                                    (state) => {
                                        return `<option value="${state.state_id}"> ${state.state_name} </option>`
                                        ''
                                    }
                                );
                                options.splice(0, 0, "<option value='0'>Select a state </option>");
                                $("#state")
                                    .html(options.join(" "))
                                    .attr("disabled", false);
                            } else {

                            }
                        }
                    })
                }
            }
        )
    </script>
    <script>
        $(function() {
            $('#stateForm').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 100,
                    }
                },
                messages: {
                    name: {
                        required: "Please enter the category name",
                        maxlength: "Only 100 characters are allowed"
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
