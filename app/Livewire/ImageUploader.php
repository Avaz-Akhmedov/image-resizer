<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Image as ImageModel;

class ImageUploader extends Component
{
    use WithFileUploads;

    public array $photos = [];
    public array $photoTypes = [];

    public array $newPhotos = [];

    public bool $showUploader = false;

    public array $availableTypes = [
        'main' => 'Главное изображение',
        'slider' => 'Слайдер',
        'layout' => 'Базовые планировки',
        'facade' => 'Базовые фасады',
        'section' => 'Разрез',
        'client_layout' => 'Клиентские планировки',
    ];


    public function toggleUploader(): void
    {
        $this->showUploader = !$this->showUploader;

        if (!$this->showUploader) {
            $this->reset(['photos', 'photoTypes', 'newPhotos']);
        }
    }

    public function updatedNewPhotos(): void
    {
        $this->validate([
            'newPhotos.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:10240'],
        ]);

        foreach ($this->newPhotos as $photo) {
            $this->photos[] = $photo;
            $this->photoTypes[] = '';
        }

        $this->reset('newPhotos');
    }

    public function save(): void
    {
        $this->validate([
            'photos' => ['required', 'array', 'min:1', 'max:10'],
            'photos.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:10240'],
            'photoTypes' => ['required', 'array'],
            'photoTypes.*' => ['required', 'in:main,slider,layout,facade,section,client_layout'],
        ]);

        foreach ($this->photos as $index => $photo) {
            $filename = $photo->getClientOriginalName();

            $originalPath = $photo->storeAs('uploads/original', $filename, 'public');

            $resizedImage = Image::read($photo->getRealPath())
                ->resize(300, 200);


            $resizedPath = "uploads/resized/{$filename}";
            Storage::disk('public')->put($resizedPath, (string)$resizedImage->encode());

            ImageModel::query()->create([
                'original_path' => $originalPath,
                'resized_path' => $resizedPath,
                'type' => $this->photoTypes[$index]
            ]);
        }

        $this->reset(['photos', 'photoTypes', 'newPhotos']);


        session()->flash('message', 'Изображения сохранены (оригинал + ресайз)!');
    }


    public function removePhoto($index): void
    {
        unset($this->photos[$index]);
        unset($this->photoTypes[$index]);

        $this->photos = array_values($this->photos);
        $this->photoTypes = array_values($this->photoTypes);

    }

    public function removeAll(): void
    {
        $this->reset(['photos', 'photoTypes']);
    }

    public function delete($id): void
    {

        $image = ImageModel::query()->findOrFail($id);

        Storage::disk('public')->delete($image->original_path);
        Storage::disk('public')->delete($image->resized_path);

        $image->delete();

        session()->flash('message', 'Изображение удалено!');

    }

    public function render()
    {
        $images = ImageModel::query()->latest()->paginate(16);

        return view('livewire.image-uploader',compact('images'));
    }

}
