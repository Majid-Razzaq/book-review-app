@extends('layouts.app')

@section('content')

<div class="container">
        <div class="row my-5">
            <div class="col-md-3">
                <div class="card border-0 shadow-lg">
                    <div class="card-header  text-white">
                        Welcome, {{ Auth::user()->name }}               
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @if(Auth::user()->image != "")
                                <img src="{{ asset('uploads/profile/thumb/'.Auth::user()->image) }}" class="img-fluid rounded-circle" alt="Luna John">                            
                            @endif
                        </div>
                        <div class="h5 text-center">
                            <strong>{{ Auth::user()->name }}</strong>
                            <p class="h6 mt-2 text-muted">5 Reviews</p>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-lg mt-3">
                    <div class="card-header  text-white">
                        Navigation
                    </div>
                    <div class="card-body sidebar">
                        @include('layouts/sidebar')
                    </div>
                </div>
            </div>
        <div class="col-md-9">
                @include('layouts.message')
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        Edit Reviews
                    </div>
                    <div class="card-body">
                        <form action="{{ route('reviews.update',$review->id) }}" method="POST">
                        @csrf
                            <div class="mb-3">
                                <label for="User" class="form-label">Reviews</label>
                                <textarea placeholder="Review" class="form-control @error('review') is-invalid @enderror" name="review" id="review">{{old('review',$review->review)}}</textarea>
                                @error('review')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="author" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="1" {{ ($review->status == 1) ? 'selected' : '' }} >Active</option>
                                    <option value="0" {{($review->status == 0) ? 'selected' : '' }} >Block</option>
                                </select>
                                @error('status')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <button class="btn btn-primary mt-2">Update</button>                     
                    </form>
                    </div>
                </div>                
            </div>
          </div>                
        </div>


@endsection