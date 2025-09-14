<?php

use App\Livewire\ImagesGallery;
use App\Livewire\ImageUploader;
use Illuminate\Support\Facades\Route;

Route::get('/', ImageUploader::class);
Route::get('/images', ImagesGallery::class);
