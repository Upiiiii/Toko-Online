@extends('layouts.parent')

@section('content')
<div class="container">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i>
        {!! session('success') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @elseif(session('error'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i>
        {!! session('error') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Create Product</h5>
            <form class="row g-3" action="{{ route('dashboard.product.store') }}" method="post" enctype="multipart/form-data" >
                @csrf
                @method('POST')
                
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="inputName4" class="form-label">Name Product</label>
                        <input type="text" class="form-control" id="inputName4" name="name" value="">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="inputName4" class="form-label">Price Product</label>
                        <input type="number" class="form-control" id="inputName4" name="price" min="0" value="{{ old('price') }}">
                    </div>
                </div>

                <div class="col-12">
                    <label class="col-sm-2 col-form-label">Select Category product</label>
                    <select class="form-select" aria-label="Default select example" name="category_id">
                        <option selected>Pilih Category</option>
                        @foreach ($category as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <textarea name="description" id="description" cols="30" rows="10"></textarea>
                </div>

                <div class="col-md-12">
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description');
</script>
@endsection