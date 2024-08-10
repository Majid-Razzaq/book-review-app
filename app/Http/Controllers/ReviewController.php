<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    // this method will show review in backend
    public function index(Request $request)
    {
        $reviews = Review::with('book')->orderBy('created_at','DESC');
        if(!empty($request->keyword)){
            $reviews = $reviews->where('review','like','%'.$request->keyword.'%');
        }
        $reviews= $reviews->paginate(10);
        return view('account.reviews.list',[
            'reviews' => $reviews,
        ]);
    }

    public function destroy(Request $request){
        $review = Review::find($request->id);
        if($review == null){
            Session()->flash('error','Review not found.');
            return response()->json([
                'status' => false,
                'message' => 'review not found',
            ]);
        }else{
            $review->delete();
            session()->flash('success','Review deleted successfully.');
        }
    }

    public function edit($id){
        $review = Review::findOrFail($id);
        return view('account.reviews.edit',[
            'review' => $review,
        ]);

    }

    public function update(Request $request,$id){
        $review = Review::findOrFail($id);
        $rules = [
            'review' => 'required',
            'status' => 'required',
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return redirect()->route('reviews.edit',$review->id)->withInput()->withErrors($validator);
        }

        $review->review = $request->review;
        $review->status = $request->status;
        $review->save();

        session()->flash('success','Review updated successfully.');
        return redirect()->route('books.reviews');
    }
    
}
 