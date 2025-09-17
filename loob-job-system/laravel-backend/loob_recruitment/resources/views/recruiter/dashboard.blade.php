@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 py-3">
    <div class="row justify-content-center">
        <div class="col-xl-11 col-lg-12">
    <!-- Page Header -->
    <div class="page-header mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-1" style="color: var(--gray-800); font-weight: 700;">
                    <i class="bi bi-kanban me-2" style="color: var(--primary-color);"></i>
                    Recruitment Dashboard
                </h1>
                <p class="text-muted mb-0 small">Manage job applications and track candidate progress efficiently</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center">
                    <i class="bi bi-building me-1" style="color: var(--primary-color); font-size: 0.9rem;"></i>
                    <span style="color: var(--gray-600); font-weight: 500; font-size: 0.875rem;">LOOB Holding</span>
                </div>
                <span class="badge px-2 py-1" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; font-size: 0.75rem;">
                    <i class="bi bi-file-earmark-text me-1"></i>
                    {{ $applications->total() }} Applications
                </span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-lg-6">
            <div class="stats-card compact">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 0.75rem; font-weight: 500;">New Applications</p>
                        <h4 class="mb-0" style="color: var(--gray-800); font-weight: 700;">{{ $applications->where('status', 'applied')->count() }}</h4>
                        <small class="text-muted" style="font-size: 0.7rem;">
                            <i class="bi bi-arrow-up text-success me-1"></i>
                            +12% last month
                        </small>
                    </div>
                    <div class="stats-icon compact" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                        <i class="bi bi-file-plus"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6">
            <div class="stats-card compact">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 0.75rem; font-weight: 500;">In Screening</p>
                        <h4 class="mb-0" style="color: var(--gray-800); font-weight: 700;">{{ $applications->where('status', 'screening')->count() }}</h4>
                        <small class="text-muted" style="font-size: 0.7rem;">
                            <i class="bi bi-arrow-up text-success me-1"></i>
                            +8% last month
                        </small>
                    </div>
                    <div class="stats-icon compact" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6">
            <div class="stats-card compact">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 0.75rem; font-weight: 500;">Interviews</p>
                        <h4 class="mb-0" style="color: var(--gray-800); font-weight: 700;">{{ $applications->where('status', 'interview')->count() }}</h4>
                        <small class="text-muted" style="font-size: 0.7rem;">
                            <i class="bi bi-arrow-up text-success me-1"></i>
                            +15% last month
                        </small>
                    </div>
                    <div class="stats-icon compact" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
                </div>

        <div class="col-xl-3 col-lg-6">
            <div class="stats-card compact">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 0.75rem; font-weight: 500;">Offers Made</p>
                        <h4 class="mb-0" style="color: var(--gray-800); font-weight: 700;">{{ $applications->where('status', 'offer')->count() }}</h4>
                        <small class="text-muted" style="font-size: 0.7rem;">
                            <i class="bi bi-arrow-up text-success me-1"></i>
                            +20% last month
                        </small>
                    </div>
                    <div class="stats-icon compact" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="bi bi-award"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card compact">
        <div class="row align-items-end g-3">
            <div class="col-12 mb-1">
                <h6 class="mb-0" style="color: var(--gray-800); font-weight: 600;">
                    <i class="bi bi-funnel me-1" style="color: var(--primary-color); font-size: 0.875rem;"></i>
                    Filter Applications
                </h6>
            </div>
            <div class="col-lg-2 col-md-3">
                <label for="status_filter" class="form-label small" style="font-weight: 500; color: var(--gray-700);">Status</label>
                <select id="status_filter" name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="applied" {{ request('status') == 'applied' ? 'selected' : '' }}>Applied</option>
                    <option value="screening" {{ request('status') == 'screening' ? 'selected' : '' }}>Screening</option>
                    <option value="interview" {{ request('status') == 'interview' ? 'selected' : '' }}>Interview</option>
                    <option value="offer" {{ request('status') == 'offer' ? 'selected' : '' }}>Offer</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-3">
                <label for="position_filter" class="form-label small" style="font-weight: 500; color: var(--gray-700);">Position</label>
                <select id="position_filter" name="position" class="form-select form-select-sm">
                    <option value="">All Positions</option>
                    @foreach($applications->unique('position')->pluck('position') as $position)
                        <option value="{{ $position }}" {{ request('position') == $position ? 'selected' : '' }}>{{ $position }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 col-md-4">
                <label for="search" class="form-label small" style="font-weight: 500; color: var(--gray-700);">Search</label>
                <input type="text" id="search" name="search" class="form-control form-control-sm" placeholder="Name, email, phone..." value="{{ request('search') }}">
            </div>
            <div class="col-lg-2 col-md-2">
                <button type="button" class="btn btn-primary btn-sm w-100" onclick="applyFilters()">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
        </div>
    </div>

                    <!-- Applications Table -->
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom" style="border-color: var(--gray-100) !important;">
            <h6 class="mb-0" style="color: var(--gray-800); font-weight: 600;">
                <i class="bi bi-table me-1" style="color: var(--primary-color); font-size: 0.875rem;"></i>
                Recent Applications
            </h6>
        </div>
        
        @if($applications->count() > 0)
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="font-weight: 600; font-size: 0.8rem;">Candidate</th>
                            <th scope="col" style="font-weight: 600; font-size: 0.8rem;">Position</th>
                            <th scope="col" style="font-weight: 600; font-size: 0.8rem;">Contact</th>
                            <th scope="col" style="font-weight: 600; font-size: 0.8rem;">Experience</th>
                            <th scope="col" style="font-weight: 600; font-size: 0.8rem;">Applied</th>
                            <th scope="col" style="font-weight: 600; font-size: 0.8rem;">Status</th>
                            <th scope="col" style="font-weight: 600; font-size: 0.8rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                        <tr>
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        {{ strtoupper(substr($application->full_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: var(--gray-800); font-size: 0.85rem;">{{ $application->full_name }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Applied {{ $application->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2">
                                <span style="font-weight: 500; color: var(--gray-700); font-size: 0.8rem;">{{ $application->position }}</span>
                            </td>
                            <td class="py-2">
                                <div>
                                    <div style="font-size: 0.75rem; color: var(--gray-700);">
                                        <i class="bi bi-envelope me-1"></i>{{ Str::limit($application->email, 20) }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--gray-700);">
                                        <i class="bi bi-telephone me-1"></i>{{ $application->phone }}
                                    </div>
                                </div>
                            </td>
                            <td class="py-2">
                                <span style="font-weight: 500; color: var(--gray-700); font-size: 0.8rem;" title="{{ $application->work_experience }}">
                                    {{ Str::limit($application->work_experience, 30) }}
                                </span>
                            </td>
                            <td class="py-2">
                                <div style="color: var(--gray-700); font-size: 0.8rem;">{{ $application->created_at->format('M d') }}</div>
                                <div class="text-muted" style="font-size: 0.7rem;">{{ $application->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="py-2">
                                <span class="status-badge-sm status-{{ $application->status }}">
                                    @switch($application->status)
                                        @case('applied')
                                            <i class="bi bi-file-plus me-1"></i>Applied
                                            @break
                                        @case('screening')
                                            <i class="bi bi-search me-1"></i>Screening
                                            @break
                                        @case('interview')
                                            <i class="bi bi-people me-1"></i>Interview
                                            @break
                                        @case('offer')
                                            <i class="bi bi-award me-1"></i>Offer
                                            @break
                                        @case('rejected')
                                            <i class="bi bi-x-circle me-1"></i>Rejected
                                            @break
                                    @endswitch
                                </span>
                            </td>
                            <td class="py-2">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('recruiter.show', $application) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       style="border-radius: 4px; font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                type="button" 
                                                data-bs-toggle="dropdown" 
                                                style="border-radius: 4px; font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><h6 class="dropdown-header small">Update Status</h6></li>
                                            @if($application->status !== 'screening')
                                                <li><a class="dropdown-item small" href="#" onclick="updateStatus({{ $application->id }}, 'screening')">
                                                    <i class="bi bi-search me-2"></i>Screening</a></li>
                                            @endif
                                            @if($application->status !== 'interview')
                                                <li><a class="dropdown-item small" href="#" onclick="updateStatus({{ $application->id }}, 'interview')">
                                                    <i class="bi bi-people me-2"></i>Interview</a></li>
                                            @endif
                                            @if($application->status !== 'offer')
                                                <li><a class="dropdown-item small" href="#" onclick="updateStatus({{ $application->id }}, 'offer')">
                                                    <i class="bi bi-award me-2"></i>Offer</a></li>
                                            @endif
                                            @if($application->status !== 'rejected')
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item small text-danger" href="#" onclick="updateStatus({{ $application->id }}, 'rejected')">
                                                    <i class="bi bi-x-circle me-2"></i>Reject</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-inbox" style="font-size: 2.5rem; color: var(--gray-400);"></i>
                <h6 class="mt-2 mb-1" style="color: var(--gray-600);">No applications found</h6>
                <p class="text-muted small mb-0">Try adjusting your filters or check back later for new applications.</p>
            </div>
        @endif
        
        @if($applications->hasPages())
            <div class="d-flex flex-column align-items-center px-3 py-2 border-top" style="border-color: var(--gray-100) !important;">
                <div class="mb-2">
                    {{ $applications->appends(request()->query())->links('custom.pagination') }}
                </div>
                <div class="text-center">
                    <span class="text-muted small">Showing {{ $applications->firstItem() ?? 0 }}-{{ $applications->lastItem() ?? 0 }} of {{ $applications->total() }} results</span>
                </div>
            </div>
        @endif
    </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Application Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">New Status</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="applied">Applied</option>
                            <option value="screening">Screening</option>
                            <option value="interview">Interview</option>
                            <option value="offer">Offer</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Add any notes about this status change..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
function applyFilters() {
    const status = document.getElementById('status_filter').value;
    const position = document.getElementById('position_filter').value;
    const search = document.getElementById('search').value;
    
    const params = new URLSearchParams();
    if (status) params.set('status', status);
    if (position) params.set('position', position);
    if (search) params.set('search', search);
    
    window.location.href = '{{ route("recruiter.dashboard") }}?' + params.toString();
}

function updateStatus(applicationId, newStatus) {
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    const form = document.getElementById('statusForm');
    const statusSelect = document.getElementById('status');
    
    form.action = `/recruiter/application/${applicationId}/status`;
    statusSelect.value = newStatus;
    
    modal.show();
}

// Auto-apply filters on Enter key
document.getElementById('search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});

// Auto-apply filters on select change
document.getElementById('status_filter').addEventListener('change', applyFilters);
document.getElementById('position_filter').addEventListener('change', applyFilters);
</script>
@endsection