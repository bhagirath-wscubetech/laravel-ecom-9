@extends('admin.layouts.master')

@section('admin-main')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Category</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Create Category</li>
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
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <form id="categoryForm" action="{{ route('admin.category.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Enter category name" value="{{old('name')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="slug">Slug</label>
                                        <input type="text" name="slug" class="form-control" id="slug"
                                            placeholder="Category Slug" value="{{old('slug')}}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" placeholder="Enter category description" class="form-control"
                                            cols="10" rows="5"></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="icon">Icon</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" name="icon" class="custom-file-input"
                                                            id="icon">
                                                        <label class="custom-file-label">Choose
                                                            file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="banner">Banner</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" name="banner" class="custom-file-input"
                                                            id="banner">
                                                        <label class="custom-file-label">Choose
                                                            file</label>
                                                    </div>
                                                </div>
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
                                        <textarea name="" id="description" placeholder="Enter meta keywords" class="form-control" cols="10"
                                            rows="5"></textarea>
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
    <script>
        $("#name").blur(
            function() {
                $("#name").next("span").remove();
                const cateogryName = $(this).val();
                if (cateogryName.length !== 0) {
                    $.ajax({
                        // url: "{{ url('/check-name/"+cateogryName+"}') }}"
                        url: `{{ url('/admin/category/check-name/${cateogryName}') }}`,
                        type: "get",
                        success: function(response) {
                            if (response.status === 1) {
                                const strToArr = cateogryName.split(" ");
                                const slug = strToArr.join("-").toLowerCase();
                                $("#slug").val(slug);
                            } else {
                                $("#name").after("<span> Category name already exists </span>")
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
    <script>
        $(function() {
            $('#categoryForm').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 100,
                    },
                    slug: {
                        required: true,
                        maxlength: 100
                    }
                },
                messages: {
                    name: {
                        required: "Please enter the category name",
                        maxlength: "Only 100 characters are allowed"
                    },
                    slug: {
                        required: "Please enter the category slug",
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
