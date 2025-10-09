<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Image History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($images->isEmpty())
                        <p>You have no processed images yet. <a href="{{ route('imago.index') }}" class="text-indigo-600 hover:underline">Process one now!</a></p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($images as $image)
                                <div class="border rounded-lg p-4 flex flex-col">
                                    <div class="flex-grow">
                                        <img src="{{ Storage::url($image->processed_path) }}" alt="Processed Image" style="height: 200px; width: 200px; class="rounded-md w-full object-cover">
                                        <p class="text-sm text-gray-500 mt-2">Processed on: {{ $image->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="mt-4 flex justify-between items-center">
                                        <a href="{{ route('history.download', $image) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-dark bg-indigo-600 hover:bg-indigo-700">Download</a>

                                        <form action="{{ route('history.destroy', $image) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image history?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $images->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
