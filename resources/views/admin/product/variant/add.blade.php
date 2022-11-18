@extends('admin.layouts.master')

@section('admin-main')

    @push('style')
        <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    @endpush

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add {{ $product->name ?? ""}} variant</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Add Variant</li>
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
                            <form id="productForm"
                                action="{{ route('admin.product.variant.store', ['product_id' => $product->product_id]) }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3">
                                            <label for="">Type</label>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <div>
                                                        <input class="form-check-input" name="type" type="radio"
                                                            id="ml" value="ml">
                                                        <label class="form-check-label" for="ml">ml</label>
                                                    </div>
                                                    <div>
                                                        <input class="form-check-input" name="type" type="radio"
                                                            id="gm" value="gm">
                                                        <label class="form-check-label" for="gm">gm</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="">Size (eg. 400 gm)</label>
                                                <input type="number" name="size" step="0.01" class="form-control"
                                                    placeholder="400.00">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="">Price</label>
                                                <input type="number" name="price" step="0.01" class="form-control"
                                                    placeholder="400.00">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="">Weight (in gm)</label>
                                                <input type="number" name="weight" step="0.01" class="form-control"
                                                    placeholder="400.00">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                                    <button type="reset" id="submit" class="btn btn-primary">Reset</button>
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
    <script src="{{ asset('dist/js/dropify.js') }}"></script>
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        $(function() {
            $('.summerNote').summernote();
            $('.dropify').dropify();
            $('#productForm').validate({
                rules: {
                    type: {
                        required: true,

                    },
                    size: {
                        required: true
                    },
                    price: {
                        required: true
                    },
                    weight: {
                        required: true
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
