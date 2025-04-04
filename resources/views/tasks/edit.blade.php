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
                <label class="block text-sm font-medium text-gray-700">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date', isset($task) ? $task->due_date : '') }}" class="w-full border rounded p-2">
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
</x-app-layout>