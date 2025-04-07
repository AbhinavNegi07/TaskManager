<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 leading-tight flex items-center gap-2">
            📝 Task Details
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto my-10 bg-white shadow-xl rounded-xl p-8 space-y-6 border border-gray-100">
        <!-- Title -->
        <h2 class="text-3xl font-bold text-blue-700">{{ $task->title }}</h2>

        <!-- Description -->
        <p class="text-gray-700 text-lg leading-relaxed border-l-4 border-blue-200 pl-4">{{ $task->description }}</p>

        <!-- Task Info -->
        <div class="grid sm:grid-cols-2 gap-4 text-gray-800">
            <p><span class="font-semibold">📌 Priority:</span> {{ ucfirst($task->priority) }}</p>
            <p>
                <span class="font-semibold">📅 Due Date:</span>
                {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
            </p>
            <p>
                <span class="font-semibold">🚦 Status:</span>
                <span class="px-2 py-1 rounded-full text-sm font-medium 
                    {{ $task->is_completed ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ $task->is_completed ? 'Completed' : 'In Progress' }}
                </span>
            </p>
        </div>

        <!-- Image (if exists) -->
        @if ($task->image)
        <div class="mt-6">
            <img src="{{ asset('storage/' . $task->image) }}" alt="Task Image"
                class="rounded-lg w-full max-w-md h-auto shadow-md border border-gray-200 mx-auto">
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-wrap gap-4">
            <!-- Mark as Completed -->
            @if (!$task->is_completed)
            <form action="{{ route('tasks.complete', $task->id) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                    ✅ Mark as Completed
                </button>
            </form>
            @endif

            <!-- Mark In Progress -->
            @if ($task->is_completed)
            <form action="{{ route('tasks.toggle', $task->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                    🔄 Mark In Progress
                </button>
            </form>
            @endif

            <!-- Edit -->
            <a href="{{ route('tasks.edit', $task->id) }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                ✏️ Edit Task
            </a>

           <!-- Delete -->
            <button onclick="openModal()"
    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
    ✖️ Delete Task
</button>
<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full space-y-4">
        <h3 class="text-xl font-semibold text-gray-800">Are you sure?</h3>
        <p class="text-gray-600">This action will permanently delete the task. You cannot undo this.</p>

        <div class="flex justify-end gap-3">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                Cancel
            </button>

            <form id="deleteForm" action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                    Yes, Delete
                </button>
            </form>
        </div>
    </div>
</div>


            <!-- Back -->
            <a href="{{ route('tasks.index') }}"
                class="text-gray-700 border border-gray-300 px-4 py-2 rounded hover:bg-gray-100 transition">
                🔙 Back to Task List
            </a>
        </div>
    </div>


    @push('scripts')
<script>
    function openModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endpush
</x-app-layout>
