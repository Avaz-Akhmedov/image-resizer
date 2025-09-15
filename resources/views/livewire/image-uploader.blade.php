<div class="min-h-screen bg-gray-100 py-10 relative">
    <div class="max-w-6xl mx-auto px-4">

        <h2 class="text-2xl font-bold text-gray-700 mb-6 text-center">
             Галерея изображений
        </h2>

        <button wire:click="toggleUploader"
                class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 transition absolute top-4 right-4">
            {{ $showUploader ? '✕ Закрыть загрузчик' : 'Загрузить изображения' }}
        </button>

        @if (session()->has('message'))
            <div class="mb-4 p-2 text-green-700 bg-green-100 rounded text-center">
                {{ session('message') }}
            </div>
        @endif



        @if ($showUploader)
            <div class="bg-white shadow-lg rounded-2xl p-6 w-full max-w-lg mx-auto mb-6">

                <input type="file" wire:model.live="newPhotos" multiple
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
                    @foreach ($photos as $index => $photo)
                        <div class="relative group flex flex-col items-center">
                            <img src="{{ $photo->temporaryUrl() }}"
                                 class="w-28 h-28 object-cover rounded-lg shadow mb-2">

                            <button wire:click="removePhoto({{ $index }})"
                                    type="button"
                                    class="absolute top-1 right-1 bg-red-600 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
                                ✕
                            </button>

                            <select wire:model="photoTypes.{{ $index }}"
                                    class="w-full text-sm border rounded px-2 py-1">
                                <option value="" disabled selected>Выберите тип</option>
                                @foreach ($availableTypes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>

                            @error('photoTypes.' . $index)
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-5">
                    <button type="button" wire:click="save"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow transition">
                        Сохранить
                    </button>

                    <button wire:click="removeAll" type="button"
                            class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-xl shadow transition">
                        Удалить все
                    </button>
                </div>
            </div>
        @endif

        {{-- Image gallery --}}
        @if ($images->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($images as $image)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden relative">
                        <img src="{{ asset('storage/' . $image->resized_path) }}"
                             alt="image"
                             class="w-full h-48 object-cover">

                        <div class="p-3">
                            <p class="text-sm text-gray-600">
                                Тип: <span class="font-semibold">{{ $image->type }}</span>
                            </p>
                            <a href="{{ asset('storage/' . $image->original_path) }}"
                               target="_blank"
                               class="text-blue-600 text-sm hover:underline">
                                {{ basename($image->original_path) }}
                            </a>
                        </div>

                        <button wire:click="delete({{ $image->id }})"
                                class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded shadow hover:bg-red-700 transition">
                            ✕
                        </button>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $images->links() }}
            </div>
        @else
            <p class="text-center text-gray-500">Нет изображений для отображения</p>
        @endif
    </div>

</div>
