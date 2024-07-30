@extends('admin.master')

@section('admin.content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Product /</span>Products Form</h4>
    <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <div class="pull-left">
            <h2>{{ isset($product) ? 'Edit' : 'Add' }} Product</h2>
        </div>

        <div class="row">
            <div class="p-2">
                <a class="btn btn-secondary float-end" href="{{ route('products.index') }}" role="button">Back</a>
            </div>
            <div class="col-xl-12">
                <!-- HTML5 Inputs -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select id="category_id" name="category_id" class="form-select">
                                <option value="">Category Select</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', isset($product) ? $product->category_id : '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input class="form-control" type="text" id="name" name="name" value="{{ old('name', isset($product) ? $product->name : '') }}" />
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input class="form-control" id="price" type="number" step="0.01" name="price" value="{{ old('price', isset($product) ? $product->price : '') }}" />
                            @error('price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input class="form-control" type="number" id="quantity" name="quantity" value="{{ old('quantity', isset($product) ? $product->quantity : '') }}" />
                            @error('quantity')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            
                            <label for="images" class="form-label">Product Images</label>
                            <input class="form-control" type="file" id="images" name="images[]" multiple />
                        
                            @isset($images)
                                <div class="mt-2">
                                    @foreach($images as $image)
                                        <img src="{{ Storage::url($image->image_path) }}" width="100" height="100" style="margin-right: 5px;" />
                                    @endforeach
                                </div>
                            @endisset
                            @error('images')
                            <span class="text-danger">{{ $message }}</span>
                            @endif
                            @error('images.*')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="name" class="description">Product Description</label>
                            <textarea class="form-control"  name="description" >
                              {{old('description') }}
                            </textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- <div>
                            <label for="description">Description</label>
                            <textarea id="description" name="description">{{ old('description') }}</textarea>
                        </div> --}}
                        <button type="submit" class="btn btn-primary">{{ isset($product) ? 'Update' : 'Create' }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
