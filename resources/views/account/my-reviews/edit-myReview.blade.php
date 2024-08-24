@extends('layouts.app')

@section('content')

<div class="container">
        <div class="row my-5">
            <div class="col-md-3">
                 @include('layouts/sidebar')
            </div>
        <div class="col-md-9">
                @include('layouts.message')
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        Edit Reviews
                    </div>
                    <div class="card-body">
                        <form action="{{ route('account.updateMyReview',$review->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="User" class="form-label">Book</label> 
                            <input value="{{ $review->book->title }}" disabled class="form-control">
                            
                        </div>

                            <div class="mb-3">
                                <label for="User" class="form-label">Reviews</label>
                                <textarea placeholder="Review" class="form-control @error('review') is-invalid @enderror" name="review" id="review">{{old('review',$review->review)}}</textarea>
                                @error('review')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for=""  class="form-label">Rating</label>
                                <select name="rating" id="rating" class="form-control">
                                    <option value="1" {{ $review->rating == 1 ? 'selected'  : ''}}>1</option>
                                    <option value="2" {{ $review->rating == 2 ? 'selected'  : ''}}>2</option>
                                    <option value="3" {{ $review->rating == 3 ? 'selected'  : ''}}>3</option>
                                    <option value="4" {{ $review->rating == 4 ? 'selected'  : ''}}>4</option>
                                    <option value="5" {{ $review->rating == 5 ? 'selected'  : ''}}>5</option>
                                </select>
                          </div>
                            <button class="btn btn-primary mt-2">Update</button>                     
                    </form>
                    </div>
                </div>                
            </div>
          </div>                
        </div>


@endsection