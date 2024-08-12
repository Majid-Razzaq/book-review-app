<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BookController extends Controller
{

    // This method will show books listing page
    public function index(Request $request){
        
        $books = Book::orderBy('created_at','DESC');
        if(!empty($request->keyword)){
            $books->where('title','like','%'.$request->keyword.'%');
        }
        $books = $books->withCount('reviews')->withSum('reviews','rating')->paginate(10);
        
        return view('books.list',[
            'books' => $books,
        ]);
    }

    // This method will create book page
    public function create(){
        return view('books.create');
    }


    // This method will store a book in DB
    public function store(Request $request){
        
        $rules = [
            'title' => 'required|min:5',
            'author' => 'required|min:5',
            'status' => 'required',
        ];

        if(!empty($request->image)){
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return redirect()->route('books.create')->withInput()->withErrors($validator);
        }

        // Save book in DB
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;
        $book->save();

        // upload image here
        if(!empty($request->image)){
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext;
            $image->move(public_path('uploads/books'),$imageName);

            $book->image = $imageName;
            $book->save();

            $manager = new ImageManager(Driver::class);
            $img = $manager->read(public_path('uploads/books/'.$imageName));
            $img->resize(990);
            $img->save(public_path('uploads/books/thumb/'.$imageName));
        }

        return redirect()->route('books.index')->with('success','Book added successfully.');

    }

    // This method will show edit book page
    public function edit(Request $request, $id){
        $book = Book::findOrFail($id);
        return view('books.edit',[
            'book' => $book,
        ]);
    }

    // This method will update a book
    public function update(Request $request, $id){

        $book = Book::findOrFail($id);

        $rules = [
            'title' => 'required|min:5',
            'author' => 'required|min:5',
            'status' => 'required',
        ];

        if(!empty($request->image)){
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return redirect()->route('books.edit',$book->id)->withInput()->withErrors($validator);
        }

        // update book in DB
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;
        $book->save();

        // update book image here
        if(!empty($request->image)){

            // delete old image 
            File::delete(public_path('uploads/books/'.$book->image));
            File::delete(public_path('uploads/books/thumb/'.$book->image));

            $image = $request->image;
            $ext = $image ->getClientOriginalExtension();
            $imageName = time().'.'.$ext;
            $image->move(public_path('uploads/books'),$imageName);

            $book->image = $imageName;
            $book->save();

            // Generate image thumbnail here
            $manager = new ImageManager(Driver::class);
            $img = $manager->read(public_path('uploads/books/'.$imageName));
            $img->resize(990);
            $img->save(public_path('uploads/books/thumb/'.$imageName));
        }
        return redirect()->route('books.index')->with('success','Book updated successfully.');
    }

    // This method will delete a book from DB
    public function destroy(Request $request){
        $book = Book::find($request->id);
        if($book == null){
            session()->flash('error','Book not found');
            return response()->json([
                'status' => false,
                'message' => 'Book not found',
            ]);
        }else{

            File::delete(public_path('uploads/books/'.$book->image));
            File::delete(public_path('uploads/books/thumb/'.$book->image));
            $book->delete();

            session()->flash('success','Book deleted successfully.');
            return response()->json([
                'status' => true,
                'success' => 'Book deleted successfully.'
            ]);
        }
        return view();
    }

}
