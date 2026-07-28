@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <div class="card-header bg-white py-3">
            <div
                class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <div>
                    <h2 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                        Solo Registrations - {{ $tournament->title }}
                    </h2>
                </div>
                <div class="d-flex gap-2">
                    @if ($tournament->winner_team_id)
                        <div class="btn btn-warning text-white p-2">
                            <i class="fas fa-trophy me-1"></i> Winner: {{ $tournament->winner_team_name }}
                        </div>
                    @endif
                    <a href="{{ route('admin.tournament-registrations.solo') }}"
                        class="text-white btn btn-sm btn-outline-secondary bg-secondary w-sm-auto d-flex align-items-center justify-content-center">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @php
            $totalPrize = $tournament->prize_pool ?? 0;
            $distributedAmount = $registrations->sum('prize_amount') ?? 0;
            $remainingAmount = $totalPrize - $distributedAmount;
            $hasPrizeDistributed = $registrations->where('prize_amount', '>', 0)->count() > 0;
            $tournamentEnded = \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($tournament->end_date));
            $winner = $registrations->where('id', $tournament->winner_team_id)->first();
            $nonWinners = $registrations->where('id', '!=', $tournament->winner_team_id);
        @endphp

        <!-- Prize Summary Cards -->
        <div class="row mt-3">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6 class="card-title">Total Prize Pool</h6>
                        <h3>{{ number_format($totalPrize, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title">Distributed</h6>
                        <h3>{{ number_format($distributedAmount, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6 class="card-title">Remaining</h6>
                        <h3>{{ number_format($remainingAmount, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="card-title">Total Players</h6>
                        <h3>{{ $registrations->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Game:</strong> {{ $tournament->game->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Solo Players:</strong> <span
                                class="badge bg-primary">{{ $registrations->total() }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Player Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>User Account</th>
                                <th>Prize Amount</th>
                                <th>Rank</th>
                                <th>Registered At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $reg)
                                <tr class="{{ $tournament->winner_team_id == $reg->id ? 'table-success' : '' }}">
                                    <td>{{ $reg->id }}</td>
                                    <td>
                                        {{ $reg->name }}
                                        @if ($tournament->winner_team_id == $reg->id)
                                            <span class="badge bg-warning ms-2">
                                                <i class="fas fa-trophy"></i> Winner
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $reg->email }}</td>
                                    <td>{{ $reg->phone }}</td>
                                    <td>{{ $reg->user->email ?? '-' }}</td>
                                    <td>
                                        @if (!is_null($reg->prize_amount) && $reg->prize_amount > 0)
                                            <span
                                                class="badge bg-success">{{ number_format($reg->prize_amount, 2) }}</span>
                                            @if ($reg->is_prize_claimed)
                                                <span class="badge bg-info ms-1">Claimed</span>
                                            @endif
                                        @elseif(!is_null($reg->prize_amount) && $reg->prize_amount == 0)
                                            <span class="badge bg-secondary">0.00</span>
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($reg->prize_rank)
                                            <span class="badge bg-dark">{{ $reg->prize_rank }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $reg->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        {!! $reg->status
                                            ? '<span class="badge bg-success">Active</span>'
                                            : '<span class="badge bg-danger">Inactive</span>' !!}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <!-- Declare Winner Button -->
                                          <!-- Declare Winner Button -->
                                            @if (!$tournament->winner_team_id)
                                                <form
                                                    action="{{ route('admin.solo-registrations.declare-winner', $tournament->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to declare {{ $reg->name }} as the winner?');"
                                                    class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="winner_id" value="{{ $reg->id }}">
                                                    <button type="submit" class="btn btn-sm btn-warning"
                                                        title="Declare Winner">
                                                        <i class="fas fa-trophy"></i>
                                                    </button>
                                                </form>
                                            @elseif($tournament->winner_team_id == $reg->id)
                                                <span class="badge bg-success p-2">
                                                    <i class="fas fa-check-circle"></i> Winner
                                                </span>
                                            @endif

                                            <!-- Delete Button -->
                                            @if (!$tournament->winner_team_id || $tournament->winner_team_id != $reg->id)
                                                @if (!$tournamentEnded)
                                                    <form action="{{ route('admin.solo-registrations.delete', $reg->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this registration?');"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No registrations found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $registrations->links() }}
                </div>
            </div>
        </div>

        <!-- Prize Distribution Section - Only for Winner -->
        @if ($tournament->winner_team_id && $totalPrize > 0 && $tournamentEnded)
            <div class="card mt-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        @if ($hasPrizeDistributed)
                            Prize Distribution Details - {{ $winner->name ?? 'Winner' }}
                        @else
                            Distribute Prize Money to Winner
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if ($hasPrizeDistributed && $winner && !is_null($winner->prize_amount))
                        {{-- View Mode - Show distributed prize for winner only --}}
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Player</th>
                                        <th>Prize Amount ()</th>
                                        <th>Rank/Position</th>
                                        <th>Status</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success">
                                        <td>
                                            {{ $winner->name }}
                                            <span class="badge bg-warning ms-1">Winner</span>
                                        </td>
                                        <td>
                                            @if (!is_null($winner->prize_amount))
                                                <span
                                                    class="badge bg-{{ $winner->prize_amount > 0 ? 'success' : 'secondary' }} p-2">
                                                    {{ number_format($winner->prize_amount, 2) }}
                                                </span>
                                            @else
                                                <span class="text-muted">Not assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($winner->prize_rank)
                                                <span class="badge bg-dark">{{ $winner->prize_rank }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($winner->is_prize_claimed)
                                                <span class="badge bg-success">Claimed</span>
                                                <small class="d-block text-muted">
                                                    {{ $winner->prize_distributed_at ? $winner->prize_distributed_at->format('Y-m-d') : '' }}
                                                </small>
                                            @elseif(!is_null($winner->prize_amount) && $winner->prize_amount > 0)
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        {{-- <td>
                                            @if (!is_null($winner->prize_amount) && $winner->prize_amount > 0 && !$winner->is_prize_claimed)
                                                <form
                                                    action="{{ route('admin.solo-registrations.mark-claimed', $winner->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-info"
                                                        onclick="return confirm('Mark this prize as claimed?');">
                                                        <i class="fas fa-check"></i> Mark Claimed
                                                    </button>
                                                </form>
                                            @endif
                                        </td> --}}
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <td class="text-end fw-bold">Total Distributed:</td>
                                        <td colspan="4">
                                            <span class="fw-bold text-success">
                                                {{ number_format($winner->prize_amount ?? 0, 2) }}
                                            </span>
                                            <small class="text-muted ms-2">
                                                (of {{ number_format($totalPrize, 2) }})
                                            </small>
                                        </td>
                                    </tr>
                                    <tr class="table-active">
                                        <td class="text-end fw-bold">Remaining:</td>
                                        <td colspan="4">
                                            <span class="fw-bold text-warning">
                                                {{ number_format($totalPrize - ($winner->prize_amount ?? 0), 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Action buttons for reset --}}
                        {{-- <div class="mt-3">
                            <form action="{{ route('admin.solo-registrations.reset-prize', $tournament->id) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to reset the prize distribution? This action cannot be undone.');"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-undo me-1"></i> Reset Distribution
                                </button>
                            </form>
                        </div> --}}
                    @elseif($winner)
                        {{-- Edit Mode - Show input field for winner only --}}
                        <form action="{{ route('admin.solo-registrations.distribute-prize', $tournament->id) }}"
                            method="POST">
                            @csrf

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Player</th>
                                            <th>Prize Amount ()</th>
                                            <th>Rank/Position</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-success">
                                            <td>
                                                {{ $winner->name }}
                                                <span class="badge bg-warning ms-1">Winner</span>
                                                <small class="d-block text-muted">Only the winner is eligible for
                                                    prize</small>
                                            </td>
                                            <td>
                                                <input type="hidden" name="distributions[0][registration_id]"
                                                    value="{{ $winner->id }}">
                                                <input type="number"
                                                    class="form-control prize-amount @error('distributions.0.amount') is-invalid @enderror"
                                                    name="distributions[0][amount]"
                                                    value="{{ old('distributions.0.amount', 0) }}" min="0"
                                                    step="0.01" placeholder="Enter amount" style="max-width: 200px;">
                                                @error('distributions.0.amount')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="distributions[0][rank]"
                                                    value="{{ old('distributions.0.rank', '1st') }}"
                                                    placeholder="e.g., 1st, 2nd, MVP" style="max-width: 200px;">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-active">
                                            <td class="text-end fw-bold">Total Distributed:</td>
                                            <td colspan="2">
                                                <span id="totalAmount" class="fw-bold text-success">0.00</span>
                                                <small class="text-muted ms-2">
                                                    (Max: {{ number_format($totalPrize, 2) }})
                                                </small>
                                            </td>
                                        </tr>
                                        <tr class="table-active">
                                            <td class="text-end fw-bold">Remaining:</td>
                                            <td colspan="2">
                                                <span id="remainingAmount"
                                                    class="fw-bold text-warning">{{ number_format($totalPrize, 2) }}</span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary" onclick="return validateDistribution()">
                                    <i class="fas fa-save me-1"></i> Distribute Prize
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        @elseif($tournament->winner_team_id && $totalPrize == 0)
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                This tournament has no prize pool set.
            </div>
        @elseif($tournament->winner_team_id && !$tournamentEnded)
            <div class="alert alert-warning mt-3">
                <i class="fas fa-clock me-2"></i>
                Prize distribution will be available after the tournament ends on
                <strong>{{ \Carbon\Carbon::parse($tournament->end_date)->format('Y-m-d H:i') }}</strong>.
            </div>
        @elseif(!$tournament->winner_team_id && $tournamentEnded)
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                Please declare a winner first to distribute the prize money.
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function validateDistribution() {
            const amounts = document.querySelectorAll('.prize-amount');
            let total = 0;
            const totalPrize = {{ $totalPrize }};

            amounts.forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
            });

            if (total === 0) {
                alert('Please distribute at least some amount');
                return false;
            }

            if (total > totalPrize) {
                alert('Total distributed amount (' + total.toFixed(2) + ') exceeds prize pool (' + totalPrize.toFixed(2) +
                    ')');
                return false;
            }

            if (total < totalPrize) {
                const confirmMsg = 'You are distributing ' + total.toFixed(2) + ' out of ' + totalPrize.toFixed(2) +
                    '. Remaining ' + (totalPrize - total).toFixed(2) + ' will be unallocated. Continue?';
                return confirm(confirmMsg);
            }

            return confirm('Are you sure you want to distribute ' + total.toFixed(2) + ' to the winner?');
        }

        // Auto-calculate total on input change
        document.addEventListener('DOMContentLoaded', function() {
            const amountInputs = document.querySelectorAll('.prize-amount');
            const totalSpan = document.getElementById('totalAmount');
            const remainingSpan = document.getElementById('remainingAmount');
            const totalPrize = {{ $totalPrize }};

            function updateTotals() {
                let total = 0;
                amountInputs.forEach(input => {
                    const value = parseFloat(input.value) || 0;
                    total += value;
                });

                totalSpan.textContent = '' + total.toFixed(2);
                const remaining = totalPrize - total;
                remainingSpan.textContent = '' + remaining.toFixed(2);

                if (total > totalPrize) {
                    totalSpan.style.color = 'red';
                } else {
                    totalSpan.style.color = 'green';
                }

                if (remaining < 0) {
                    remainingSpan.style.color = 'red';
                } else {
                    remainingSpan.style.color = 'orange';
                }
            }

            amountInputs.forEach(input => {
                input.addEventListener('input', updateTotals);
            });

            // Initial calculation
            updateTotals();
        });
    </script>
@endpush
