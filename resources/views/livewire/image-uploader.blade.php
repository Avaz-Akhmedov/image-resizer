<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white shadow-lg rounded-2xl p-6 w-full max-w-lg">

        <h2 class="text-xl font-semibold text-gray-700 mb-4 text-center">
            Загрузите изображения
        </h2>

        @if (session()->has('message'))
            <div class="mb-4 p-2 text-green-700 bg-green-100 rounded text-center">
                {{ session('message') }}
            </div>
        @endif

        <input type="file" wire:model="photos" multiple
               class="block w-full text-sm text-gray-600
                      file:mr-4 file:py-2 file:px-4
                      file:rounded-lg file:border-0
                      file:text-sm file:font-semibold
                      file:bg-blue-50 file:text-blue-600
                      hover:file:bg-blue-100
                      mb-3"/>

        @error('photos')
        <div class="text-red-500 text-sm mb-2">{{ $message }}</div>
        @enderror
        @error('photos.*')
        <div class="text-red-500 text-sm mb-2">{{ $message }}</div>
        @enderror

        <div class="mt-3 flex gap-3 flex-wrap justify-center">
            @foreach ($photos as  $index => $photo)
                <div class="relative group">
                    <img src="{{ $photo->temporaryUrl() }}" class="w-28 h-28 object-cover rounded-lg shadow">

                    <button wire:click="removePhoto({{ $index }})"
                            type="button"
                            class="absolute top-1 right-1 bg-red-600 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
                        ✕
                    </button>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <button wire:click="save"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700text-white rounded-xl shadow transition">
                Сохранить
            </button>

            <button wire:click="removeAll" type="button"
                    class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-xl shadow transition">
                Удалить все
            </button>
        </div>
    </div>
</div>
