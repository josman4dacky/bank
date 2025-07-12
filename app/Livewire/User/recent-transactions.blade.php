<table class="min-w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg overflow-hidden shadow">
    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
        <tr>
            <th class="px-4 py-3">Date</th>
            <th class="px-4 py-3">Type</th>
            <th class="px-4 py-3">Amount (Ksh)</th>
            <th class="px-4 py-3">Status</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-100">
        @forelse ($transactions as $transaction)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                <td class="px-4 py-2 capitalize">{{ $transaction->type }}</td>
                <td class="px-4 py-2 font-medium text-gray-800">{{ number_format($transaction->amount, 2) }}</td>
                <td class="px-4 py-2">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold
                        {{ $transaction->status === 'success' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $transaction->status }}
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center py-4 text-gray-500">No transactions found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
