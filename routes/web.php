<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;


Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/book/{id}',[HomeController::class,'detail'])->name('book.detail');
Route::post('/save-book-review',[HomeController::class,'saveReview'])->name('book.saveReview');

Route::group(['prefix'=>'account'], function(){
    Route::group(['middleware' => 'guest'], function(){
        Route::get('register',[AccountController::class,'register'])->name('account.register');
        Route::post('register',[AccountController::class,'processRegister'])->name('account.processRegister');
        Route::get('login',[AccountController::class,'login'])->name('account.login');
        Route::post('login',[AccountController::class,'authenticate'])->name('account.authenticate');
    }); 

    Route::group(['middleware' => 'auth'], function(){
        Route::get('profile',[AccountController::class,'profile'])->name('account.profile');
        Route::get('logout',[AccountController::class,'logout'])->name('account.logout');
        Route::post('update-profile',[AccountController::class,'updateProfile'])->name('account.updateProfile');
       
       
        Route::group(['middleware' => 'check-admin'], function(){
            Route::get('books',[BookController::class,'index'])->name('books.index');
            Route::get('books/create',[BookController::class,'create'])->name('books.create');
            Route::post('books/create',[BookController::class,'store'])->name('books.store');
            Route::get('books/edit/{id}',[BookController::class,'edit'])->name('books.edit');
            Route::post('books/update/{id}',[BookController::class,'update'])->name('books.update');
            Route::delete('books',[BookController::class,'destroy'])->name('books.destroy');    
      
            Route::get('reviews',[ReviewController::class,'index'])->name('books.reviews');
            Route::delete('delete-review',[ReviewController::class,'destroy'])->name('reviews.destroy');
            Route::get('reviews/edit/{id}',[ReviewController::class,'edit'])->name('reviews.edit');
            Route::post('reviews/update/{id}',[ReviewController::class,'update'])->name('reviews.update');    
      
        });
       
        Route::get('my-reviews',[AccountController::class,'myReviews'])->name('account.myReviews');
        Route::delete('delete-myreview',[AccountController::class,'deleteMyReview'])->name('account.deleteMyReview');
        Route::get('my-reviews/edit/{id}',[AccountController::class,'editMyReview'])->name('account.editMyReview');
        Route::post('my-reviews/update/{id}',[AccountController::class,'updateMyReview'])->name('account.updateMyReview');
        Route::get('change-password',[AccountController::class,'changePasswordForm'])->name('account.changePasswordForm');
        Route::post('change-password',[AccountController::class,'updatePassword'])->name('account.updatePassword');

    }); 

});