@extends('admin.layouts.master')

@section('admin-main')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Categories Listing</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">View country</li>
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
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $sr = 1 @endphp
                                    @foreach ($countries as $country)
                                        <tr>
                                            <td>
                                                <input type="checkbox">
                                            </td>
                                            <td>{{ $sr++ }}</td>
                                            <td>{{ $country->country_name }}</td>
                                            <td>
                                                <a
                                                    href="{{ route('admin.country.toggleStatus', ['country' => $country->country_id]) }}">
                                                    @if ($country->country_status == 1)
                                                        <button class="btn btn-primary">Active</button>
                                                    @else
                                                        <button class="btn btn-warning">Inactive</button>
                                                    @endif
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.country.edit', ['country' => $country->country_id]) }}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                &nbsp;&nbsp;&nbsp;
                                                <a href="{{ route('admin.country.delete', ['country' => $country->country_id]) }}">
                                                    <i class="text-danger fas fa-trash-alt"></i>
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
    <script>
        setTimeout(() => {
            $(".alert").fadeOut(500);
        }, 2000);
    </script>
@endpush
