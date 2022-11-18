@extends('admin.layouts.master')

@section('admin-main')
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    @push('style')
        <style>
            .custom-switch .custom-control-label {
                user-select: none;
            }
        </style>
    @endpush
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="modal fade" id="product_modal" aria-modal="true" role="dialog"
            style="padding-right: 17px;background:rgba(0, 0, 0, 0.7)">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Product details</h4>
                        <button type="button" onclick="closeModel()" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" onclick="closeModel()"
                            data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Edit</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Products Listing</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">View Product</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        {{-- Table here --}}
        <div class="container-fluid px-3">
            <div class="row">
                <div class="col-12">
                    @if ($errors->any())
                        <div class="alert alert-danger my-2">
                            <ul class="list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 350px;">
                                    <input type="text" name="table_search" class="form-control float-right"
                                        placeholder="Search">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox"></th>
                                        <th>Sr.</th>
                                        <th>Name</th>
                                        <th>SKU</th>
                                        <th>Main Image</th>
                                        <th>Toggle</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sr = 1;
                                        $mainImgPath = config('constants.readPath') . config('constants.product.main_image');
                                        $otherImgPath = config('constants.readPath') . config('constants.product.other_image');
                                    @endphp
                                    @foreach ($products as $product)
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td>{{ $sr++ }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->sku }}</td>
                                            <td>
                                                <img width="100px"
                                                    src="{{ asset($mainImgPath . '/' . $product->main_image) }}"
                                                    alt="" />
                                            </td>
                                            <td>

                                                <div class="form-group">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" onchange="toggle(this,1)"
                                                            data-product-id="{{ $product->product_id }}"
                                                            class="custom-control-input"
                                                            id="status{{ $product->product_id }}"
                                                            {{ $product->status == '1' ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="status{{ $product->product_id }}">Status</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" onchange="toggle(this,2)"
                                                            data-product-id="{{ $product->product_id }}"
                                                            class="custom-control-input"
                                                            id="featured{{ $product->product_id }}"
                                                            {{ $product->featured == '1' ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="featured{{ $product->product_id }}">Featured</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" onchange="toggle(this,3)"
                                                            data-product-id="{{ $product->product_id }}"
                                                            class="custom-control-input"
                                                            id="seasonal{{ $product->product_id }}"
                                                            {{ $product->seasonal == '1' ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="seasonal{{ $product->product_id }}">Seasonal</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" onchange="toggle(this,4)"
                                                            data-product-id="{{ $product->product_id }}"
                                                            class="custom-control-input"
                                                            id="most_selling{{ $product->product_id }}"
                                                            {{ $product->most_selling == '1' ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="most_selling{{ $product->product_id }}">Best
                                                            Selling</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="fa fa-eye text-info" data-toggle="tooltip"
                                                    data-placement="right" title="View Product"
                                                    onclick="viewProduct({{ $product->product_id }})"></i>
                                                <br />
                                                <a
                                                    href="{{ route('admin.product.edit', ['product' => $product->product_id]) }}">
                                                    <i class="fa fa-pencil-alt text-primary" data-toggle="tooltip"
                                                        data-placement="right" title="Edit Product"></i>
                                                </a>
                                                <br />
                                                <i class="fa fa-trash-alt text-danger" data-toggle="tooltip"
                                                    data-placement="right" title="Move to trash"></i>
                                                <br />
                                                <a
                                                    href="{{ route('admin.product.variant.index', ['product_id' => $product->product_id]) }}">
                                                    <i class="fa fa-plus text-secondary" data-toggle="tooltip"
                                                        data-placement="right" title=" Variants"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script>
        setTimeout(() => {
            $(".alert").fadeOut(500);
        }, 2000);

        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <script>
        function toggle(elem, type) {
            // 1: Status 2: Featured 3: Seasonal 4: Best selling
            const currentVal = $(elem).prop("checked");
            const productId = $(elem).data('product-id');
            $.ajax({
                url: `{{ url('/admin/product/toggle/${type}/${productId}') }}`,
                type: "get",
                beforeSend: function() {
                    $(elem).attr("disabled", true);
                },
                success: function(response) {
                    if (response.status == 1) {
                        toastr.success(response.msg);
                    } else {
                        $(elem).prop("checked", !currentVal); //revert
                        toastr.error(response.msg);
                    }
                    $(elem).attr("disabled", false);
                }
            })
        }
    </script>
    <script>
        function viewProduct(pId) {
            var mainImgPath = "{{ asset($mainImgPath) }}";
            var otherImgPath = "{{ asset($otherImgPath) }}";
            $.ajax({
                url: `{{ url('admin/product/${pId}') }}`,
                type: 'get',
                beforeSend: function() {
                    product_modal.classList.add('show')
                    product_modal.style.display = "block";
                    $('.modal-body').html("Loading data...");
                },
                success: function(response) {
                    console.log(response);
                    const modalBody = `
            <div class="table-responsive">
                    <table class="table table-bordered">                               
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Slug</th>
                            <th scope="col">SKU</th>
                            <th scope="col">GST</th>
                        </tr>
                        <tr class="">
                            <td scope="row">${response.data.name}</td>
                            <td>${response.data.slug}</td>
                            <td>${response.data.sku}</td>
                            <td>${response.data.gst}</td>
                        </tr>
                        <tr>
                            <th scope="col" colspan="2">Ingredients</th>
                            <th scope="col">Uses</th>
                            <th scope="col">Doses</th>
                        </tr>
                        <tr class="">
                            <td scope="row" colspan="2">${response.data.ingredients}</td>
                            <td>${response.data.uses}</td>
                            <td>${response.data.doses}</td>
                        </tr>
                        <tr>
                            <th scope="col">Short Description</th>
                            <th scope="col" colspan="3">Long Description</th>
                        </tr>
                        <tr class="">
                            <td>${response.data.short_description}</td>
                            <td colspan="3">${response.data.long_description}</td>
                        </tr>
                    </table>
                </div>
            `
                    $('.modal-body').html(modalBody);
                }

            })
        }

        function closeModel() {
            product_modal.classList.remove('show')
            product_modal.style.display = "";
        }
    </script>
@endpush
