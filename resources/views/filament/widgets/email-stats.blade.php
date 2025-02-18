<x-filament::widget>
    <div class="p-4 bg-white shadow rounded-lg">
        <h2 class="text-lg font-semibold text-gray-700">Email Statistics</h2>
        <div class="mt-4 grid grid-cols-2 gap-4">
            <!-- Total Emails -->
            <div class="flex flex-col items-center">
                <span class="text-3xl font-bold text-gray-900">{{ $totalEmails }}</span>
                <span class="text-sm text-gray-500">Total Emails</span>
            </div>
            <!-- Unread Emails -->
            <div class="flex flex-col items-center">
                <span class="text-3xl font-bold text-gray-900">{{ $unreadEmails }}</span>
                <span class="text-sm text-gray-500">Unread Emails</span>
            </div>
        </div>
    </div>
</x-filament::widget>
