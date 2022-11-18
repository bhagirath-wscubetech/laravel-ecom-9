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
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $product->name }} Variant</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Product Variant</li>
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
                <div class="col-12 text-right my-2">
                    <a href="{{ route('admin.product.variant.create', ['product_id' => $product->product_id]) }}">
                        <button class="btn btn-primary">
                            <i class="fa fa-plus"></i>
                        </button>
                    </a>
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
                                        <th>Size</th>
                                        <th>Price</th>
                                        <th>Weight (gm)</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sr = 1;
                                    @endphp
                                    @foreach ($product->product_variant as $variant)
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td>{{ $sr++ }}</td>
                                            <td>{{ $variant->size }} {{ $variant->type }}</td>
                                            <td> ₹ {{ $variant->price }}</td>
                                            <td>{{ $variant->weight }}</td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" onchange="toggleStatus(this)"
                                                        data-variant-id="{{ $variant->variant_id }}"
                                                        class="custom-control-input" id="variant{{ $variant->variant_id }}"
                                                        {{ $variant->stock == '1' ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="variant{{ $variant->variant_id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <a
                                                    href="{{ route('admin.product.variant.edit', ['productVariant' => $variant->variant_id]) }}">
                                                    <i class="fa fa-pencil-alt text-primary" data-toggle="tooltip"
                                                        data-placement="right" title="Edit Variant"></i>
                                                </a>
                                                &nbsp;
                                                <a
                                                    href="{{ route('admin.product.variant.delete', ['productVariant' => $variant->variant_id]) }}">
                                                    <i class="fa fa-trash-alt text-danger" data-toggle="tooltip"
                                                        data-placement="right" title="Move to trash"></i>
                                                </a>
                                                <br />
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
        function toggleStatus(elem) {
            const currentVal = $(elem).prop("checked");
            const variantId = $(elem).data('variant-id');
            $.ajax({
                url: `{{ url('/admin/product/variant/toggle-stock/${variantId}') }}`,
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
@endpush
