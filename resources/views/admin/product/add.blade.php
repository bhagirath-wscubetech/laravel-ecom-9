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
                        <h1>Create Product</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Add Product</li>
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
                            <form id="productForm" action="{{ route('admin.product.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" class="form-control" id="name"
                                                    placeholder="Enter product name" value="{{ old('name') }}">
                                            </div>

                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="slug">Slug</label>
                                                <input type="text" name="slug" class="form-control" id="slug"
                                                    placeholder="Product Slug" value="{{ old('slug') }}" readonly>
                                            </div>

                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="gst">GST %</label>
                                                <input type="number" min="0" max="100" name="gst"
                                                    class="form-control" step="0.01" id="gst"
                                                    placeholder="Enter product gst" value="{{ old('gst') }}">
                                            </div>

                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="sku">SKU</label>
                                                <input type="text" name="sku" class="form-control" id="sku"
                                                    placeholder="Enter product sku" value="{{ old('sku') }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="slug">Category</label>
                                                <select name="category" id="" class="form-control">
                                                    <option value="0">Select a category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="ingredient">Ingredients</label>
                                                <textarea name="ingredient" id="ingredient" placeholder="Enter ingredients" class="summerNote form-control"
                                                    cols="10" rows="5">{{ old('ingredient') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="uses">Uses</label>
                                                <textarea name="uses" id="uses" placeholder="Enter long description" class="summerNote form-control"
                                                    cols="10" rows="5">{{ old('uses') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="dose">Dose</label>
                                                <textarea name="dose" id="dose" placeholder="Enter long description" class="summerNote form-control"
                                                    cols="10" rows="5">{{ old('dose') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="short_description">Short Description</label>
                                                <textarea name="short_description" id="short_description" placeholder="Enter short description"
                                                    class="summerNote form-control" cols="10" rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="long_description">Long Description</label>
                                                <textarea name="long_description" id="long_description" placeholder="Enter long description"
                                                    class="summerNote form-control" cols="10" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="main_image">Main Image</label>
                                                <input type="file" name="main_image" class="dropify" id="main_image">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="other_images">Other Images</label>
                                                <input type="file" multiple name="other_images[]" class="dropify"
                                                    id="other_images">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_title">Meta Title</label>
                                        <input type="text" name="meta_title" class="form-control" id="meta_title"
                                            placeholder="Enter Meta Title">
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_description">Meta Description</label>
                                        <textarea name="meta_description" id="meta_description" placeholder="Enter meta description" class="form-control"
                                            cols="10" rows="5"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Keywords</label>
                                        <textarea name="meta_keywords" id="description" placeholder="Enter meta keywords" class="form-control" cols="10"
                                            rows="5"></textarea>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
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
        $("#name").blur(
            function() {
                $("#name").next("span").remove();
                const productName = $(this).val();
                if (productName.length !== 0) {
                    $.ajax({
                        url: `{{ url('/admin/product/check-name/${productName}') }}`,
                        type: "get",
                        success: function(response) {
                            if (response.status === 1) {
                                const strToArr = productName.split(" ");
                                const slug = strToArr.join("-").toLowerCase();
                                $("#slug").val(slug);
                                $("#submit").attr("disabled", false);
                            } else {
                                $("#name").after(
                                    "<span class='text-danger d-block pl-2'> Product name already exists </span>"
                                    );
                                $("#submit").attr("disabled", true);
                            }
                        }
                    })

                }

            }
        )
        $("#sku").blur(
            function() {
                $("#sku").next("span").remove();
                const productsku = $(this).val();
                if (productsku.length !== 0) {
                    $.ajax({
                        url: `{{ url('/admin/product/check-sku/${productsku}') }}`,
                        type: "get",
                        success: function(response) {
                            if (response.status === 1) {
                                $("#submit").attr("disabled", false);
                            } else {
                                $("#sku").after(
                                    "<span class='text-danger d-block pl-2'> Product SKU already exists </span>"
                                    );
                                $("#submit").attr("disabled", true);
                            }
                        }
                    })

                }

            }
        )
    </script>
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
                    name: {
                        required: true,
                        maxlength: 100,
                    },
                    slug: {
                        required: true,
                        maxlength: 100
                    },
                    sku: {
                        required: true,
                        maxlength: 100
                    }
                },
                messages: {
                    name: {
                        required: "Please enter the product name",
                        maxlength: "Only 100 characters are allowed"
                    },
                    slug: {
                        required: "Please enter the product slug",
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
