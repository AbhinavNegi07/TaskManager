<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            📊 Task Analytics Dashboard
        </h2>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto px-4 space-y-12">

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($stats as $key => $value)
                <a href="{{ route('tasks.index', ['completed' => 1]) }}"
                   class="bg-white border border-gray-200 p-6 rounded-xl shadow hover:shadow-lg transition duration-200">
                    <h3 class="text-gray-600 text-sm font-medium uppercase tracking-wide mb-2">
                        {{ ucfirst($key) }} Completed
                    </h3>
                    <p class="text-4xl font-bold text-blue-600">{{ $value }}</p>
                </a>
            @endforeach
        </div>


       
        <!-- Charts Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <!-- Priority Chart -->
            <div class="bg-white p-6 rounded-xl shadow-md flex justify-center flex-col items-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📌 Completed Tasks by Priority</h3>
                <div class="relative h-72 " style="height: 300px; width:300px">
                    <canvas id="priorityChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Monthly Chart -->
            <div class="bg-white p-6 rounded-xl shadow-md flex justify-center flex-col items-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📆 Tasks Completed Per Month</h3>
                <div class="relative h-72">
                    <canvas id="monthlyChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>
        </div>

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const priorityData = {
            labels: ['Low', 'Medium', 'High'],
            datasets: [{
                label: 'Completed Tasks',
                data: [{{ $priorityBreakdown['low'] ?? 0 }}, {{ $priorityBreakdown['medium'] ?? 0 }}, {{ $priorityBreakdown['high'] ?? 0 }}],
                backgroundColor: ['#34d399', '#fbbf24', '#ef4444'],
                borderColor: ['#10b981', '#f59e0b', '#dc2626'],
                borderWidth: 1
            }]
        };

        new Chart(document.getElementById('priorityChart'), {
            type: 'doughnut',
            data: priorityData,
            options: {
                responsive: false,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // const monthlyData = {
        //     labels: @json(array_keys($monthlyStats->toArray())),
        //     data: @json(array_values($monthlyStats->toArray()))
        // };
        const monthlyData = {
    labels: @json(array_keys($monthlyStats->toArray())),
    datasets: [{
        label: 'Tasks Completed',
        data: @json(array_values($monthlyStats->toArray())),
        backgroundColor: '#3b82f6', // Tailwind blue-500
        borderRadius: 6 // Rounded bars
    }]
};

new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: monthlyData,
    options: {
        responsive: false, // You can set this to true if your container is responsive
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

    </script>
    @endpush
</x-app-layout>
