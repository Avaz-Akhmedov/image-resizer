<?php

namespace App\Livewire;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class ImagesGallery extends Component
{
    use WithPagination;


    public function delete($id): void
    {

        $image = Image::query()->findOrFail($id);

        Storage::disk('public')->delete($image->original_path);
        Storage::disk('public')->delete($image->resized_path);

        $image->delete();

        session()->flash('message', 'Изображение удалено!');
        $this->resetPage();

    }

    public function render()
    {
        $images = Image::query()->latest()->paginate(16);
        return view('livewire.images-gallery', compact('images'));
    }
}
