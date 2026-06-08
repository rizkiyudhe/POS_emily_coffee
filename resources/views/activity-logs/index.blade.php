<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Log Aktivitas') }}
            </h2>
            <form method="GET" action="{{ route('activity-logs.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aksi atau user..." 
                       class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded">🔍</button>
                <a href="{{ route('activity-logs.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1 rounded">Reset</a>
            </form>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $log->user->name ?? 'System' }}
                                @if(!$log->user)
                                    <span class="text-xs text-gray-400">(deleted user)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if(str_contains($log->action, 'create')) bg-green-100 text-green-700
                                    @elseif(str_contains($log->action, 'update')) bg-yellow-100 text-yellow-700
                                    @elseif(str_contains($log->action, 'delete')) bg-red-100 text-red-700
                                    @elseif(str_contains($log->action, 'login')) bg-blue-100 text-blue-700
                                    @elseif(str_contains($log->action, 'logout')) bg-gray-100 text-gray-700
                                    @elseif(str_contains($log->action, 'reprint')) bg-purple-100 text-purple-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst($log->action) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $log->description }}</td>
                            <td class="px-6 py-4 text-sm font-mono">{{ $log->ip_address ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada aktivitas yang tercatat.  
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>