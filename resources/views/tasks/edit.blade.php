<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mx-5 inline">
            Edit Task
        </h2>
        <a href="{{ route('tasks.index') }}"
            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded shadow-md transition duration-300 mx-5">
            All Tasks
        </a>
        <a href="{{ route('tasks.create') }}"
            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded shadow-md transition duration-300">
            Create New Task
        </a>
    </x-slot>

    <div class="max-w-2xl mx-auto my-6 bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Edit Task</h2>

        <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Task Title</label>
                <input type="text" name="title" value="{{ $task->title }}" class="w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" class="w-full border rounded p-2" rows="4">{{ $task->description }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Priority</label>
                <select name="priority" class="w-full border rounded p-2">
                    <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <div>
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700">Due Date</label>
                    <input
                        type="text"
                        name="due_date"
                        id="due_date"
                        value="{{ old('due_date', isset($task) ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : now()->format('Y-m-d')) }}"
                        class="w-full border rounded p-2 pl-10 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <div class="absolute left-2 top-8 text-gray-400 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Upload Image</label>
                <input type="file" name="image" class="w-full border rounded p-2">
                @if ($task->image)
                <img src="{{ asset('storage/'.$task->image) }}" class="mt-2 w-32 rounded shadow">
                @endif
            </div>

            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Task</button>
                <a href="{{ route('tasks.index') }}" class="text-gray-500 ml-2">Cancel</a>
            </div>
        </form>
    </div>



    @push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#due_date", {
            altInput: true,
            altFormat: "d-m-Y", // visible format
            dateFormat: "Y-m-d", // format submitted to DB
            defaultDate: document.getElementById('due_date').value || new Date().toISOString().split('T')[0],
            minDate: "today",
            enableTime: false,
        });
    </script>
    @endpush

</x-app-layout>