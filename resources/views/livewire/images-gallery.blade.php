<div class="min-h-screen bg-gray-100 py-10 relative" >
    <div class="max-w-6xl mx-auto px-4">

        <h2 class="text-2xl font-bold text-gray-700 mb-6 text-center">
             Галерея изображений
        </h2>


        <a href="/" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 transition absolute top-4 right-4 ">
             Загрузить изображения
        </a>


        @if (session()->has('message'))
            <div class="mb-4 p-2 text-green-700 bg-green-100 rounded text-center">
                {{ session('message') }}
            </div>
        @endif

        @if($images->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($images as $image)
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
                                {{basename($image->original_path)}}
                            </a>
                        </div>
                        <button wire:click="delete({{$image->id}})"
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
