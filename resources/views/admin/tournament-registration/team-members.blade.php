{{-- resources/views/admin/team-members.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card-header bg-white py-3">
            <div
                class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <div>
                    <h2 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                        Team Members - {{ $captain->team_name }}
                    </h2>
                    <small class="text-muted">Tournament: {{ $tournament->title }}</small>
                </div>
                <div class="d-flex gap-2">
                    @if ($winnerTeamId == $captain->id)
                        <div class="btn btn-warning text-white p-2">
                            <i class="fas fa-trophy me-1"></i> Winner Team
                        </div>
                    @endif
                     @if (!$tournamentEnded)
                        <a href="{{ route('admin.team-registrations.add-member-form', $captain->id) }}" 
                           class="btn btn-sm btn-success">
                            <i class="fas fa-user-plus me-1"></i> Add Member
                        </a>
                    @endif
                    <a href="{{ route('admin.tournament-registrations.team', ['tournament' => $tournament->id]) }}"
                        class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                    <!-- Change Captain Form -->
                    @if (!$tournamentEnded && $members->count() > 1)
                        <div class="">
                            
                            <div class="card-body">
                                <form action="{{ route('admin.team-registrations.change-captain', $captain->id) }}" method="POST">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-6">
                                            <select name="new_captain_id" id="new_captain_id" class="form-select" required>
                                                <option value="">-- Captain New Captain --</option>
                                                @foreach($members as $member)
                                                    @if(!$member->is_captain)
                                                        <option value="{{ $member->id }}">
                                                            {{ $member->name ?? $member->email }} 
                                                            @if($member->is_captain) (Current Captain) @endif
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to change the team captain? Both members will be notified via email.');">
                                                <i class="fas fa-exchange-alt me-1"></i> Change Captain
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
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

        @php
            $tournamentEnded = \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($tournament->end_date));
            $tournamentOngoing = \Carbon\Carbon::now()->between(
                \Carbon\Carbon::parse($tournament->start_date),
                \Carbon\Carbon::parse($tournament->end_date)
            );
            
            // ✅ Check if prize has been distributed for this team
            // A team has prize distributed if any member has prize_amount > 0
            $hasPrizeDistributed = $members->where('prize_amount', '>', 0)->count() > 0;
            
            // ✅ Check if prize is fully distributed or claimed
            $isPrizeFullyDistributed = $members->where('prize_amount', '>', 0)->count() > 0;
            
            // ✅ Determine if remove button should be hidden
            // Hide remove button if:
            // 1. Tournament has ended, OR
            // 2. Prize has been distributed for this team
            $hideRemoveButton = $tournamentEnded || $hasPrizeDistributed;
        @endphp

        <!-- Prize Summary Cards -->
        <div class="row mt-3">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6 class="card-title">Total Prize Pool</h6>
                        <h3>{{ number_format($tournament->prize_pool ?? 0, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title">Distributed</h6>
                        <h3>{{ number_format($members->sum('prize_amount') ?? 0, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6 class="card-title">Remaining</h6>
                        <h3>{{ number_format(($tournament->prize_pool ?? 0) - ($members->sum('prize_amount') ?? 0), 2) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="card-title">Total Members</h6>
                        <h3>{{ $members->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Members Table -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sr.No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Prize Amount (QR)</th>
                                <th>Rank</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $index => $member)
                                <tr class="{{ $member->is_captain ? 'table-primary' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $member->name ?? "N/A" }}
                                        @if ($member->is_captain)
                                            <span class="badge bg-primary ms-1">Captain</span>
                                        @endif
                                    </td>
                                    <td>{{ $member->email  ?? "N/A" }}</td>
                                    <td>{{ $member->phone  ?? "N/A" }}</td>
                                    <td>
                                        @if ($member->is_captain)
                                            <span class="badge bg-info">Captain</span>
                                        @else
                                            <span class="badge bg-secondary">Member</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!is_null($member->prize_amount) && $member->prize_amount > 0)
                                            <span
                                                class="badge bg-success">{{ number_format($member->prize_amount, 2) }}</span>
                                            @if ($member->is_prize_claimed)
                                                <span class="badge bg-info ms-1">Claimed</span>
                                            @endif
                                        @elseif(!is_null($member->prize_amount) && $member->prize_amount == 0)
                                            <span class="badge bg-secondary">0.00</span>
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($member->prize_rank)
                                            <span class="badge bg-dark">{{ $member->prize_rank }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($member->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Remove button - Hide if tournament ended OR prize distributed --}}
                                        @if(!$hideRemoveButton)
                                            <form
                                                action="{{ route('admin.team-registrations.remove-member', $member->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to remove {{ $member->is_captain ? 'the captain' : 'this member' }} from the team?');"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-user-minus"></i> 
                                                    {{ $member->is_captain ? 'Remove' : 'Remove' }}
                                                </button>
                                            </form>
                                        @else
                                            {{-- ow reason why remove is disabled --}}
                                            @if($hasPrizeDistributed)
                                                <span class="text-muted" title="Prize has been distributed for this team">
                                                    <i class="fas fa-lock me-1"></i> Prize Distributed
                                                </span>
                                            @elseif($tournamentEnded)
                                                <span class="text-muted" title="Tournament has ended">
                                                    <i class="fas fa-lock me-1"></i> Tournament Ended
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No members found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Prize Distribution Form -->
        @php
            $hasPrizeDistributed = $members->where('prize_amount', '>', 0)->count() > 0;
        @endphp

        @if ($winnerTeamId == $captain->id && $tournament->prize_pool > 0)
            <div class="card mt-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        @if ($hasPrizeDistributed)
                            Prize Distribution Details
                        @else
                            Distribute Prize Money
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if ($hasPrizeDistributed)
                        {{-- View Mode - Show distributed prizes --}}
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Member</th>
                                        <th>Role</th>
                                        <th>Prize Amount (QR)</th>
                                        <th>Rank/Position</th>
                                        <!--<th>Status</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($members as $member) 
                                        <tr>
                                            <td>
                                                {{ $member->first_name ?? $member->email ?? "N/A" }}
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
                                                @if (!is_null($member->prize_amount))
                                                    <span
                                                        class="badge bg-{{ $member->prize_amount > 0 ? 'success' : 'secondary' }} p-2">
                                                        {{ number_format($member->prize_amount, 2) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($member->prize_rank)
                                                    <span class="badge bg-dark">{{ $member->prize_rank }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <!--<td>-->
                                            <!--    @if ($member->is_prize_claimed)-->
                                            <!--        <span class="badge bg-success">Claimed</span>-->
                                            <!--        <small class="d-block text-muted">-->
                                            <!--            {{ $member->prize_distributed_at ? $member->prize_distributed_at->format('Y-m-d') : '' }}-->
                                            <!--        </small>-->
                                            <!--    @elseif(!is_null($member->prize_amount) && $member->prize_amount > 0)-->
                                            <!--        <span class="badge bg-warning">Pending</span>-->
                                            <!--    @else-->
                                            <!--        <span class="badge bg-secondary">-</span>-->
                                            <!--    @endif-->
                                            <!--</td>-->
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <td colspan="2" class="text-end fw-bold">Total Distributed:</td>
                                        <td colspan="3">
                                            <span class="fw-bold text-success">
                                                QR{{ number_format($members->sum('prize_amount') ?? 0, 2) }}
                                            </span>
                                            <small class="text-muted ms-2">
                                                (of {{ number_format($tournament->prize_pool, 2) }})
                                            </small>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        {{-- Edit Mode - Show input fields for distribution --}}
                        <form action="{{ route('admin.team-registrations.distribute-prize', $tournament->id) }}"
                            method="POST">
                            @csrf

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr> 
                                            <th>Member</th>
                                            <th>Role</th>
                                            <th>Prize Amount (QR)</th>
                                            <th>Rank/Position</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($members as $index => $member)
                                            <tr>
                                                <td>
                                                    {{ $member->name ?? $member->email }}
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
                                                    <input type="hidden"
                                                        name="distributions[{{ $loop->index }}][member_id]"
                                                        value="{{ $member->id }}">
                                                    <input type="number"
                                                        class="form-control prize-amount @error('distributions.' . $loop->index . '.amount') is-invalid @enderror"
                                                        name="distributions[{{ $loop->index }}][amount]"
                                                        value="{{ old('distributions.' . $loop->index . '.amount', 0) }}"
                                                        min="0" step="0.01" placeholder="Enter amount"
                                                        style="max-width: 200px;">
                                                    @error('distributions.' . $loop->index . '.amount')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control"
                                                        name="distributions[{{ $loop->index }}][rank]"
                                                        value="{{ old('distributions.' . $loop->index . '.rank', '') }}"
                                                        placeholder="e.g., 1st, 2nd, MVP" style="max-width: 200px;">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-active">
                                            <td colspan="2" class="text-end fw-bold">Total Distributed:</td>
                                            <td colspan="2">
                                                <span id="totalAmount" class="fw-bold text-success">0.00</span>
                                                <small class="text-muted ms-2">
                                                    (Max: {{ number_format($tournament->prize_pool, 2) }})
                                                </small>
                                            </td>
                                        </tr>
                                        <tr class="table-active">
                                            <td colspan="2" class="text-end fw-bold">Remaining:</td>
                                            <td colspan="2">
                                                <span id="remainingAmount"
                                                    class="fw-bold text-warning">{{ number_format($tournament->prize_pool, 2) }}</span>
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
        @elseif($winnerTeamId == $captain->id && $tournament->prize_pool == 0)
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                This tournament has no prize pool set.
            </div>
        @endif
    </div>
@endsection

<script>
    function validateDistribution() {
        const amounts = document.querySelectorAll('.prize-amount');
        let total = 0;
        const totalPrize = {{ $tournament->prize_pool ?? 0 }};

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

        return confirm('Are you sure you want to distribute ' + total.toFixed(2) + ' among team members?');
    }

    // Auto-calculate total on input change
    document.addEventListener('DOMContentLoaded', function() {
        const amountInputs = document.querySelectorAll('.prize-amount');
        const totalSpan = document.getElementById('totalAmount');
        const remainingSpan = document.getElementById('remainingAmount');
        const totalPrize = {{ $tournament->prize_pool ?? 0 }};

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
        }

        amountInputs.forEach(input => {
            input.addEventListener('input', updateTotals);
        });

        // Initial calculation
        updateTotals();
    });
</script>