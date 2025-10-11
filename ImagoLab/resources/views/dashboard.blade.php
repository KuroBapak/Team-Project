<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Image Processor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('error'))
                        <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                            <span class="font-medium">Error!</span> {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('imago.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="image" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                        <hr class="my-4">

                        <strong class="text-lg">Processing Mode:</strong><br>
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center">
                                <input type="radio" id="removebg" name="mode" value="removebg" checked class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="removebg" class="ml-3 block text-sm font-medium text-gray-700">Remove Background</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="grayscale" name="mode" value="grayscale" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="grayscale" class="ml-3 block text-sm font-medium text-gray-700">Convert to Grayscale</label>
                            </div>
                        </div>
                        <hr class="my-4">

                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Process Image</button>
                    </form>

                </div>
            </div>

            @if(isset($originalUrl) && isset($processedUrl))
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-semibold text-lg">Original</h3>
                        <img src="{{ $originalUrl }}" alt="Original Image" style="height: 200px; width: 200px;" class="mt-4 rounded-lg w-full">
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-semibold text-lg">Processed</h3>
                        <img src="{{ $processedUrl }}" alt="Processed Image" style="height: 200px; width: 200px;" class="mt-4 rounded-lg w-full">

                        <a href="{{ $processedUrl }}" download="processed_image.png" class="inline-block mt-4 px-4 py-2 bg-green-600 text-dark font-semibold text-xs uppercase rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Download Result
                        </a>
                    </div>
                </div>
            @else
                <p class="text-center mt-6 text-gray-500">Your processed image will appear here.</p>
            @endif
        </div>
    </div>
</x-app-layout>
