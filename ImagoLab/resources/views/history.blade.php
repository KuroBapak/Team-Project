<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Image History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 flex space-x-2">
                <button id="filter-basic" class="px-4 py-2 rounded-md font-semibold text-xs uppercase bg-gray-800 text-white">Basic Tools</button>
                <button id="filter-advanced" class="px-4 py-2 rounded-md font-semibold text-xs uppercase bg-gray-200 text-gray-800">Advanced AI</button>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($images->isEmpty())
                        <p>You have no processed images yet. <a href="{{ route('selection') }}" class="text-indigo-600 hover:underline">Process one now!</a></p>
                    @else
                        <div id="history-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($images as $image)
                                <div class="history-card border rounded-lg p-4 flex flex-col" data-tool-type="{{ $image->tool_type }}">
                                    <div class="flex-grow">
                                        <img src="{{ Storage::url($image->processed_path) }}" alt="Processed Image" style="height: 200px; width: 200px;" class="rounded-md object-cover mx-auto">
                                        <p class="text-sm text-gray-500 mt-2 text-center">Processed on: {{ $image->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="mt-4 flex justify-between items-center">
                                        <a href="{{ route('history.download', $image) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">Download</a>
                                        <form action="{{ route('history.destroy', $image) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image history?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBasicBtn = document.getElementById('filter-basic');
            const filterAdvancedBtn = document.getElementById('filter-advanced');
            const allCards = document.querySelectorAll('.history-card');

            function filterHistory(toolType) {
                allCards.forEach(card => {
                    card.style.display = card.dataset.toolType === toolType ? 'flex' : 'none';
                });

                // FIXED FILTER BUTTON COLORS
                if (toolType === 'basic') {
                    // Style for active Basic button
                    filterBasicBtn.className = 'px-4 py-2 rounded-md font-semibold text-xs uppercase bg-gray-800 text-white';
                    // Style for inactive Advanced button
                    filterAdvancedBtn.className = 'px-4 py-2 rounded-md font-semibold text-xs uppercase bg-gray-200 text-gray-800';
                } else {
                    // Style for inactive Basic button
                    filterBasicBtn.className = 'px-4 py-2 rounded-md font-semibold text-xs uppercase bg-gray-200 text-gray-800';
                    // Style for active Advanced button
                    filterAdvancedBtn.className = 'px-4 py-2 rounded-md font-semibold text-xs uppercase bg-gray-800 text-white';
                }
            }

            filterBasicBtn.addEventListener('click', () => filterHistory('basic'));
            filterAdvancedBtn.addEventListener('click', () => filterHistory('advanced'));

            // Apply default filter on page load
            filterHistory('basic');
        });
    </script>
    @endpush
</x-app-layout>
