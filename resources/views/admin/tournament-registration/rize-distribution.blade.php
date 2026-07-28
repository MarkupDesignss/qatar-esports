{{-- resources/views/admin/prize-distribution.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card-header bg-white py-3">
            <div
                class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <div>
                    <h2 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                        Prize Distribution - {{ $tournament->title }}
                    </h2>
                    <small class="text-muted">Winning Team: {{ $tournament->winner_team_name }}</small>
                </div>
                <a href="{{ route('admin.tournament-registrations.team', ['tournament' => $tournament->id]) }}"
                    class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6 class="card-title">Total Prize Pool</h6>
                        <h3>₹{{ number_format($totalPrize, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title">Distributed</h6>
                        <h3>₹{{ number_format($distributedAmount, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6 class="card-title">Remaining</h6>
                        <h3>₹{{ number_format($remainingAmount, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribution Form -->
        <div class="card mt-3">
            <div class="card-body">
                <form action="{{ route('admin.team-registrations.distribute-prize', $tournament->id) }}" method="POST">
                    @csrf

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Member</th>
                                    <th>Role</th>
                                    <th>Prize Amount (₹)</th>
                                    <th>Rank</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members as $member)
                                    <tr>
                                        <td>
                                            {{ $member->name }}
                                            @if ($member->is_captain)
                                                <span class="badge bg-primary ms-1">Captain</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($member->is_captain)
                                                <span class="badge bg-info">Captain</span>
                                            @else
                                                <span class="badge bg-secondary">Member</span>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="hidden" name="distributions[{{ $loop->index }}][member_id]"
                                                value="{{ $member->id }}">
                                            <input type="number"
                                                class="form-control prize-amount @error('distributions.' . $loop->index . '.amount') is-invalid @enderror"
                                                name="distributions[{{ $loop->index }}][amount]"
                                                value="{{ old('distributions.' . $loop->index . '.amount', $member->prize_amount ?? 0) }}"
                                                min="0" step="0.01" placeholder="Enter amount">
                                            @error('distributions.' . $loop->index . '.amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="text" class="form-control"
                                                name="distributions[{{ $loop->index }}][rank]"
                                                value="{{ old('distributions.' . $loop->index . '.rank', $member->prize_rank ?? '') }}"
                                                placeholder="e.g., 1st, 2nd, MVP">
                                        </td>
                                        <td>
                                            @if ($member->is_prize_claimed)
                                                <span class="badge bg-success">Claimed</span>
                                                <small
                                                    class="d-block text-muted">{{ $member->prize_distributed_at->format('Y-m-d') }}</small>
                                            @elseif($member->prize_amount)
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-secondary">Not Assigned</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" onclick="return validateDistribution()">
                            <i class="fas fa-money-bill-wave me-1"></i> Distribute Prize
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function validateDistribution() {
            const amounts = document.querySelectorAll('.prize-amount');
            let total = 0;
            const totalPrize = {{ $totalPrize }};

            amounts.forEach(input => {
                total += parseFloat(input.value) || 0;
            });

            if (total > totalPrize) {
                alert('Total distributed amount (₹' + total.toFixed(2) + ') exceeds prize pool (₹' + totalPrize.toFixed(2) +
                    ')');
                return false;
            }

            if (total === 0) {
                alert('Please distribute at least some amount');
                return false;
            }

            return confirm('Are you sure you want to distribute ₹' + total.toFixed(2) + ' among team members?');
        }
    </script>
@endsection
