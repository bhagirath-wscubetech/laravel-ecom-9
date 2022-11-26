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
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <form id="categoryForm" action="{{ route('admin.category.update', ['category' => $category->id]) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Enter category name" value="{{ $category->name ?? old('name') }}">
                                    </div>
                                    <input type="hidden" id="category_id" value="{{$category->id}}">
                                    <div class="form-group">
                                        <label for="slug">Slug</label>
                                        <input type="text" name="slug" class="form-control" id="slug"
                                            placeholder="Category Slug" value="{{ $category->slug ?? old('slug') }}"
                                            readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" placeholder="Enter category description" class="form-control"
                                            cols="10" rows="5">{{ $category->description ?? old('description') }}</textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="icon">Icon</label>
                                                <div class="input-group">
                                                    <input type="file" name="icon" class="dropify" id="icon" data-show-remove="false" data-default-file="{{ asset('storage/images/category/icons') }}/{{ $category->icon }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="banner">Banner</label>
                                                <div class="input-group">
                                                    <input type="file" name="banner" class="dropify" id="banner" data-show-remove="false" data-default-file="{{ asset('storage/images/category/banners') }}/{{ $category->banner }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_title">Meta Title</label>
                                        <input type="text" name="meta_title" class="form-control" id="meta_title"
                                            placeholder="Enter Meta Title" value="{{ $category->meta_title ?? old('meta_title') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_description">Meta Description</label>
                                        <textarea name="meta_description" id="meta_description" placeholder="Enter meta description" class="form-control"
                                            cols="10" rows="5">{{ $category->meta_description ?? old('meta_description') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="keywords">Keywords</label>
                                        <textarea name="keywords" id="keywords" placeholder="Enter meta keywords" class="form-control" cols="10"
                                            rows="5">{{ $category->meta_keywords ?? old('meta_keywords') }}</textarea>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
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
    <script src="{{ asset('dist/js/dropify.js') }}"></script>
    <script>
        $('.dropify').dropify();
        $("#name").blur(
            function() {
                $("#name").next("span").remove();
                const cateogryName = $(this).val();
                const categoryId = $("#category_id").val();
                if (cateogryName.length !== 0) {
                    $.ajax({
                        // url: "{{ url('/check-name/"+cateogryName+"}') }}"
                        url: `{{ url('/admin/category/check-name/${cateogryName}/${categoryId}') }}`,
                        type: "get",
                        success: function(response) {
                            if (response.status === 1) {
                                const strToArr = cateogryName.split(" ");
                                const slug = strToArr.join("-").toLowerCase();
                                $("#slug").val(slug);
                                $("#submit").attr("disabled",false);
                            } else {
                                $("#name").after("<span class='text-danger d-block pl-2'> Category name already exists </span>");
                                $("#submit").attr("disabled",true);
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
