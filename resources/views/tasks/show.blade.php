<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Task Details
        </h2>
    </x-slot>


    <div class="max-w-3xl mx-auto my-6 bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-2">{{ $task->title }}</h2>

        <p class="mb-2 text-gray-700">{{ $task->description }}</p>

        <p><strong>Priority:</strong> {{ ucfirst($task->priority) }}</p>

        <p><strong>Status:</strong>
            <span class="{{ $task->is_completed ? 'text-green-600' : 'text-red-600' }}">
                {{ $task->is_completed ? 'Completed' : 'Pending' }}
            </span>
        </p>

        <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</p>

        @if ($task->image)
        <div class="mt-4">
            <img src="{{ asset('storage/' . $task->image) }}" alt="Task Image"
                class="rounded w-64 h-auto shadow-md">
        </div>
        @endif

        <div class="mt-6 space-x-2">
            <a href="{{ route('tasks.edit', $task->id) }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Edit
            </a>
            <a href="{{ route('tasks.index') }}"
                class="text-gray-600 px-4 py-2 hover:underline">
                Back
            </a>
        </div>
    </div>

</x-app-layout>