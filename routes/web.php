<?php

use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('contactform.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); 

    //add contact
    Route::get('/contactform', [ContactFormController::class, 'getContactForm'])->name('contactform'); 
    Route::post('/savecontact', [ContactFormController::class, 'saveContact']); 
    Route::post('/getcontactdet', [ContactFormController::class, 'getContactDetails']); 
    Route::post('/showcustomfields', [ContactFormController::class, 'showCustomFields']); 

    //list contact
    Route::get('/listcontact', [ContactFormController::class, 'listContact'])->name('listcontact');  
    Route::get('/getcontact', [ContactFormController::class, 'getContact']);      
    Route::post('/getmergedetails', [ContactFormController::class, 'getMergeDetails']);  
    Route::post('/mergecontact', [ContactFormController::class, 'mergeContact']);  
    Route::post('/deletecontact', [ContactFormController::class, 'deleteContact']);  
    Route::post('/getcontactotherdetails', [ContactFormController::class, 'getContactOtherDetails']);  

    //custom fields
    Route::get('/customfields', [ContactFormController::class, 'getCustomFields'])->name('customfields');
    Route::post('/savecustomfields', [ContactFormController::class, 'saveCustomFields']);  
    Route::get('/getcustomfield', [ContactFormController::class, 'getCustomField']);  
    Route::post('/deletecustomfield', [ContactFormController::class, 'deleteCustomField']);  
    Route::post('/getcustomfielddet', [ContactFormController::class, 'getCustomFieldDetails']);  
});

require __DIR__.'/auth.php';