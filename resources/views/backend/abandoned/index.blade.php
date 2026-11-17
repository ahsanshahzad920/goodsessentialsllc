@extends('layouts.backend')

@section('content')
    <div class="container-fluid pt-3 px-4 pb-5">
        <h3 class="heading">Checkout follow-ups</h3>
        <p class="text-muted small">Shoppers who entered their mobile number at checkout but did not place an order (shown 30 minutes after they stopped). Rows disappear automatically when that number orders.</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card border-0">
            <div class="card-body p-0">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Customer</th><th>Cart</th><th class="text-end">Value</th><th>Last seen</th><th class="text-end">Follow up</th></tr></thead>
                    <tbody>
                        @forelse ($rows as $row)
                            @php
                                $wa = \App\Support\PkPhone::whatsapp($row->phone);
                                $msg = 'Assalam o Alaikum' . ($row->name ? ' ' . $row->name : '') . '! You were ordering from Goods Essentials: '
                                    . collect($row->items)->map(fn ($i) => $i['quantity'] . ' x ' . $i['title'])->implode(', ')
                                    . '. Can we help you complete your order? Cash on Delivery available.';
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $row->name ?: '—' }}</div>
                                    <div class="small">{{ \App\Support\PkPhone::display($row->phone) }}{{ $row->city ? ' · ' . $row->city : '' }}</div>
                                </td>
                                <td class="small">
                                    @foreach ($row->items ?? [] as $i)
                                        <div>{{ $i['quantity'] }} × {{ $i['title'] }}{{ $i['color'] ? ' (' . $i['color'] . ')' : '' }}</div>
                                    @endforeach
                                </td>
                                <td class="text-end">{{ money($row->subtotal) }}</td>
                                <td class="small">{{ $row->updated_at->diffForHumans() }}
                                    @if ($row->contacted_at) <div><span class="badge bg-secondary">Contacted {{ $row->contacted_at->diffForHumans() }}</span></div> @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="https://wa.me/{{ $wa }}?text={{ rawurlencode($msg) }}" target="_blank" rel="noopener" class="btn btn-sm btn-success">WhatsApp</a>
                                    <form action="{{ route('admin.abandoned.contacted', $row) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-secondary">Mark contacted</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No open checkouts right now.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $rows->links() }}</div>
    </div>
@endsection
