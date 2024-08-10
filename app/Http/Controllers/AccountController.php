<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AccountController extends Controller
{
    // This method will show register page
    public function register(){
        return view('account.register');
    }

    // This method will register a user
    public function processRegister(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }

        // user will be register using this code
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('account.login')->with('success','You have registred successfully.');
    }

    // Login page
    public function login(){
        return view('account.login');
    }

    //authenticate user here 
    public function authenticate(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);

        if($validator->fails()){
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }
        // Check user is valid 
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password ])){
            return redirect()->route('account.profile');
        }else{
            return redirect()->route('account.login')->with('error','Either email or password is invalid.');
        }

    }

    //This method will show user profile data
    public function profile(){
        $user = User::find(Auth::user()->id);
        return view('account.profile',[
            'user' => $user,
        ]);
    }

    //This method will update user profile 
    public function updateProfile(Request $request){

        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id.',id',
        ];

        if(!empty($request->image)){
            $rules['image'] = 'image';
        }   

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }
        
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Image upload code
        if (!empty($request->image)) {

            // Delete old image here 
            File::delete(public_path('uploads/profile/'.$user->image));
            File::delete(public_path('uploads/profile/thumb/'.$user->image));

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            $image->move(public_path('uploads/profile'), $imageName);
        
            $user->image = $imageName;
            $user->save();

            $manager = new ImageManager(Driver::class);
            $img = $manager->read(public_path('uploads/profile/'.$imageName));
            $img->cover(150,150);
            $img->save(public_path('uploads/profile/thumb/'.$imageName));
        }
        
        return redirect()->route('account.profile')->with('success','Profile updated successfully.');

    }

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }

    
    public function myReviews(Request $request){
        $reviews = Review::where('user_id',Auth::user()->id);
        $reviews = $reviews->orderBy('created_at','DESC');

        if(!empty($request->keyword))
        {
            $reviews = $reviews->where('review','like','%'.$request->keyword.'%');
        }

        $reviews = $reviews->paginate(10);
        return view('account.my-reviews',[
            'reviews' => $reviews,
        ]);
    }

    // delete my reviews using this function
    public function deleteMyReview($id){
        $reviews = Review::find($id);
        if($reviews == null){
            return response()->json([
                'status' => false,
                'message' => 'Review not found',
            ]);
        }else{
            $reviews->delete();
            Session()->flash('success','Review deleted successfully.');
        }
    }

    public function editMyReview($id){
        $review = Review::findOrFail($id);
        return view('account.edit-myReview',[
            'review' => $review,
        ]);
    }

    public function updateMyReview(Request $request, $id){
        $review = Review::findOrFail($id);
        $rules = [
            'review' => 'required',
            'rating' => 'required',
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return redirect()->route('reviews.editMyReview', $review->id)->withInput()->withErrors($validator);
        }

        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->save();

        session()->flash('success','Review updated successfully.');
        return redirect()->route('reviews.myReviews');
    }

}
