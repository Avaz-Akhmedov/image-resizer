<?php

namespace App\Livewire;

use App\Http\Requests\ResizeImageRequest;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Image as ImageModel;

class ImageUploader extends Component
{
    use WithFileUploads;

    public array $photos = [];
    protected array $rules = [
        'photos'   => ['required', 'array', 'min:1'],
        'photos.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120']
    ];

    public function render()
    {
        return view('livewire.image-uploader');
    }

    public function save(): void
    {
        $this->validate();

        foreach ($this->photos as $photo) {
            $filename = uniqid() . '.' . $photo->getClientOriginalExtension();

            $originalPath = $photo->storeAs('uploads/original', $filename, 'public');

            $resizedImage = Image::read($photo->getRealPath())
                ->resize(300, 200);


            $resizedPath = "uploads/resized/{$filename}";
            Storage::disk('public')->put($resizedPath, (string)$resizedImage->encode());

            ImageModel::query()->create([
                'original_path' => $originalPath,
                'resized_path' => $resizedPath,
            ]);
        }

        $this->reset('photos');


        session()->flash('message', 'Изображения сохранены (оригинал + ресайз)!');

    }

    public function removePhoto($index): void
    {
        unset($this->photos[$index]);

        $this->photos = array_values($this->photos);

    }

    public function removeAll(): void
    {
        $this->reset('photos');
    }


}
