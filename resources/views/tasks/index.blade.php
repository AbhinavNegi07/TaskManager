<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mr-5 inline">
            My Tasks
        </h2>
        <a href="{{ route('tasks.create') }}"
            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded shadow-md transition duration-300">
            Create New Task
        </a>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-20">
            <!-- pending and completed tabs -->
            <div class="mb-4 flex space-x-2">
                {{-- All Tasks --}}
                <a href="{{ route('tasks.index') }}"
                    class="px-4 py-2 rounded-lg 
       {{ is_null(request('completed')) ? 'font-bold bg-blue-600 text-white' : 'bg-white text-gray-500' }}">
                    All
                </a>

                {{-- Pending Tasks --}}
                <a href="{{ route('tasks.index', ['completed' => 0]) }}"
                    class="px-4 py-2 rounded-lg 
       {{ request('completed') === '0' ? 'font-bold bg-blue-600 text-white' : 'bg-white text-gray-500' }}">
                    Pending
                </a>

                {{-- Completed Tasks --}}
                <a href="{{ route('tasks.index', ['completed' => 1]) }}"
                    class="px-4 py-2 rounded-lg 
       {{ request('completed') === '1' ? 'font-bold bg-blue-600 text-white' : 'bg-white text-gray-500' }}">
                    Completed
                </a>
            </div>

            <!-- Filter Dropdown Button -->
            <div class="relative inline-block text-left mb-4 mr-10">
                <button type="button" id="filterToggle"
                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none"
                    onclick="document.getElementById('filterMenu').classList.toggle('hidden')">
                    Filter Tasks
                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="filterMenu"
                    class="origin-top-left absolute left-0 mt-2 w-72 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50">
                    <form method="GET" action="{{ route('tasks.index') }}" class="p-4 space-y-4">
                        <!-- Keep completed status if applied -->
                        @if(request()->has('completed'))
                        <input type="hidden" name="completed" value="{{ request('completed') }}">
                        @endif

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                            <select name="priority" id="priority"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                            <input type="text" name="due_date" id="due_date" placeholder="select a date"
                                value="{{ request('due_date') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>


                        <!-- Buttons -->
                        <div class="flex justify-between items-center pt-2">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                Apply
                            </button>
                            <a href="{{ route('tasks.index', request()->has('completed') ? ['completed' => request('completed')] : []) }}"
                                class="text-sm text-red-600 hover:underline">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Task Cards Container -->

        @if ($tasks->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($tasks as $task)
            <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
                <!-- Task Image -->
                @if ($task->image)
                <img src="{{ asset('storage/'.$task->image) }}" class="my-2 w-full h-50 object-cover rounded-lg shadow">
                @endif
                <h2 class="text-xl font-bold text-gray-800 ">{{ $task->title }}</h2>


                <p class="text-gray-600 mb-2">
                    {{ \Illuminate\Support\Str::words($task->description, 20, '...') }}
                </p>



                <!-- Priority & Status (Side by Side) -->
                <div class="flex items-center justify-between gap-4 mb-4">
                    <!-- Priority -->
                    <p class="text-sm font-semibold text-gray-700">
                        Priority:
                        <span class="px-2 py-1 rounded-full text-white
            {{ $task->priority == 'low' ? 'bg-green-500' : ($task->priority == 'medium' ? 'bg-yellow-500' : 'bg-red-500') }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </p>

                    <!-- Status -->
                    <p class="text-sm font-bold text-gray-700">
                        Status:
                        <span class="px-2 py-1 rounded-full text-white
            {{ $task->is_completed ? 'bg-green-500' : 'bg-gray-500' }}">
                            {{ $task->is_completed ? 'Completed' : 'Pending' }}
                        </span>
                    </p>
                </div>


                <!-- Priority -->
                <!-- <p class="text-sm font-semibold text-gray-700 mb-4">Priority:
                    <span class="px-2 py-1 rounded-full text-white
                {{ $task->priority == 'low' ? 'bg-green-500' : ($task->priority == 'medium' ? 'bg-yellow-500' : 'bg-red-500') }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                </p> -->

                <!-- Status -->
                <!-- <p class="text-sm text-gray-700 font-bold">Status:
                    <span class="px-2 py-1 rounded-full text-white
                {{ $task->is_completed ? 'bg-green-500' : 'bg-gray-500' }}">
                        {{ $task->is_completed ? 'Completed' : 'Pending' }}
                    </span>
                </p> -->
                <div class="flex items-center justify-between gap-4 mb-4">
                    <p class="text-sm text-gray-700 my-2 font-bold">Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</p>

                    <a href="{{ route('tasks.show', $task->id) }}" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Details</a>
                </div>



                <!-- Buttons -->
                @if (!$task->is_completed)
                <div class="mt-4 flex space-x-2">
                    <a href="{{ route('tasks.edit', $task->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                        Edit
                    </a>


                    <!-- Delete Tasks -->

                    <div x-data="{ showConfirm: false }">
                        <!-- Trigger Button -->
                        <button @click="showConfirm = true" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                            Delete
                        </button>

                        <!-- Confirmation Modal -->
                        <div x-show="showConfirm"
                            x-transition
                            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                            style="display: none;">
                            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                                <h2 class="text-lg font-bold mb-4">Confirm Deletion</h2>
                                <p class="text-gray-700 mb-6">Are you sure you want to delete this task? This action cannot be undone.</p>

                                <div class="flex justify-end gap-4">
                                    <button @click="showConfirm = false"
                                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">
                                        Cancel
                                    </button>

                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded">
                                            Yes, Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                            Delete
                        </button>
                    </form> -->

                    <form action="{{ route('tasks.complete', $task->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                            Mark as Completed
                        </button>
                    </form>
                </div>


                @else
                <div class="mt-4 flex space-x-2">
                    <!-- Completed Status -->
                    <span class="bg-green-500 text-white px-3 py-1 rounded inline-block">
                        ✅ Task Completed
                    </span>

                    <!-- Mark as In Progress Button -->
                    <form action="{{ route('tasks.toggle', $task->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                            Mark as In Progress
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="text-white text-5xl flex justify-center  items-center font-semibold">
            No tasks found for this filter.
        </div>
        @endif

    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#due_date", {
            altInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d", // matches MySQL format
            defaultDate: "{{ request('due_date') }}",
            enableTime: false // no time picker needed here
            // No minDate so past dates are allowed
        });
    </script>
    @endpush



</x-app-layout>