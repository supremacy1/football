@extends('layouts.app')
@section('title', 'Withdraw Funds')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-danger text-white py-3"><h5 class="mb-0 fw-bold">Withdraw to Bank</h5></div>
                <div class="card-body p-4">
                    <div class="alert alert-light border mb-4">
                        <small class="text-muted d-block">Available Balance</small>
                        <h3 class="fw-bold text-dark mb-0">₦{{ number_format($wallet->balance ?? 0, 2) }}</h3>
                    </div>
                    <form action="{{ route('withdrawal.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="account_name" id="account_name_hidden">
                        <input type="hidden" name="bank_name" id="bank_name_hidden">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Bank</label>
                            <select name="bank_code" id="bank_code" class="form-select" required>
                                <option value="">Choose bank...</option>
                                @foreach($banks as $bank) <option value="{{ $bank['code'] }}">{{ $bank['name'] }}</option> @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Account Number</label>
                            <input type="text" name="account_number" id="account_number" class="form-control" maxlength="10" required>
                            <div id="verify_msg" class="small mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Amount (₦)</label>
                            <input type="number" name="amount" class="form-control" min="100" required>
                        </div>
                        <button type="submit" id="submitBtn" class="btn btn-danger w-100 py-2 fw-bold" disabled>Confirm Withdrawal</button>
                    </form>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white"><h6 class="mb-0">History</h6></div>
                <div class="list-group list-group-flush">
                    @forelse($withdrawals as $w)
                    <div class="list-group-item d-flex justify-content-between">
                        <div><div class="fw-bold">₦{{ number_format($w->amount, 2) }}</div><small>{{ $w->bank_name }}</small></div>
                        <span class="badge {{ $w->status === 'success' ? 'bg-success' : 'bg-warning' }}">{{ strtoupper($w->status) }}</span>
                    </div>
                    @empty <div class="p-3 text-center text-muted">No history.</div> @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    const bank = document.getElementById('bank_code');
    const acc = document.getElementById('account_number');
    const msg = document.getElementById('verify_msg');
    const btn = document.getElementById('submitBtn');

    async function verify() {
        if (bank.value && acc.value.length === 10) {
            msg.className = 'text-primary small'; msg.textContent = 'Verifying...';
            try {
                const res = await fetch("{{ route('withdrawal.verify') }}", {
                    method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({bank_code: bank.value, account_number: acc.value})
                });
                const data = await res.json();
                if (data.success) {
                    msg.className = 'text-success small fw-bold'; msg.textContent = data.account_name;
                    document.getElementById('account_name_hidden').value = data.account_name;
                    document.getElementById('bank_name_hidden').value = bank.options[bank.selectedIndex].text;
                    btn.disabled = false;
                } else {
                    msg.className = 'text-danger small'; msg.textContent = 'Invalid account.';
                    btn.disabled = true;
                }
            } catch (e) { btn.disabled = true; }
        }
    }
    bank.addEventListener('change', verify);
    acc.addEventListener('input', verify);
</script>
@endsection