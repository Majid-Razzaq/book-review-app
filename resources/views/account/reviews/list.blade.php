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
                        Reviews
                    </div>
                    <div class="card-body pb-0">   
                    <div class="d-flex justify-content-end">
                    <form action="" method="get">
                                <div class="d-flex">
                                    <input type="text" value="{{ Request::get('keyword') }}" class="form-control" placeholder="keyword" name="keyword">
                                    <button type="submit" class="btn btn-primary ms-2">Search</button>
                                    <a href="{{ route('books.reviews') }}" class="btn btn-secondary ms-2">Clear</a>
                                </div>
                            </form> 
                    </div>        
                        <table class="table  table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Review</th>
                                    <th>Book</th>
                                    <th>Rating</th>
                                    <th>Created at</th>
                                    <th>Status</th>                                  
                                    <th width="100">Action</th>
                                </tr>
                                <tbody>
                                    @if($reviews->isNotEmpty())
                                    @foreach($reviews as $review)     
                                        <tr>
                                            <td>{{ $review->review }}<br/><strong>{{ $review->user->name }}</strong></td>                                        
                                            <td>{{ $review->book->title }}</td>
                                            <td><i class="fa-regular fa-star"></i> {{ $review->rating }}</td>
                                            <td>{{ \Carbon\Carbon::parse($review->created_at)->format('d M, Y')}}</td>
                                            <td>
                                                @if($review->status == 1)
                                                    <span class="text-success">Active</span>
                                                @else
                                                    <span class="text-danger">Block</span>
                                                @endif    
                                            </td>
                                            <td>
                                                <a href="{{ route('reviews.edit',$review->id) }}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="#" onclick="deleteReviews('{{$review->id}}')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="6">Review not found.</td>
                                    </tr>
                                    @endif                                  
                                </tbody>
                            </thead>
                        </table>   
                        {{ $reviews->links() }}               
                    </div>
                    
                </div>              
            </div>
        </div>       
    </div>

@endsection

@section('script')
    <script>
        function deleteReviews(id){
            if(confirm("Are you sure you want to delete this review?")){
                $.ajax({
                    url:'{{route("reviews.destroy")}}',
                    type:'delete',
                    data:{id:id},
                    headers:{
                        'X-CSRF-TOKEN':'{{ csrf_token() }}',
                    },
                    success: function(response){
                        window.location.href = '{{ route("books.reviews") }}';
                    }
                });
            }
        }

    </script>
@endsection