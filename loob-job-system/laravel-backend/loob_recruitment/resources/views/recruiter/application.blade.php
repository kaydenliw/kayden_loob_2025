@extends('layouts.app')
@use('Illuminate\Support\Facades\Storage')

@section('content')
<div class="container-fluid px-3 py-3">
    <div class="row justify-content-center">
        <div class="col-xl-11 col-lg-12">
    <!-- Header with Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <!-- Back Button Row -->
            <div class="mb-3">
                <a href="{{ route('recruiter.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>
            
            <!-- Header Row -->
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h2 class="fw-bold mb-2">
                        <i class="bi bi-person-badge me-2 text-primary"></i>{{ $application->full_name }}
                    </h2>
                    <p class="text-muted mb-0 fs-5">Application for <span class="text-primary fw-semibold">{{ $application->position }}</span></p>
                </div>
                <div>
                    <span class="badge bg-{{ $application->status == 'applied' ? 'primary' : 
                                         ($application->status == 'screening' ? 'info' : 
                                         ($application->status == 'interview' ? 'warning' : 
                                         ($application->status == 'offer' ? 'success' : 'danger'))) }} fs-6 px-3 py-2">
                        @switch($application->status)
                            @case('applied')
                                <i class="bi bi-file-plus me-1"></i>
                                @break
                            @case('screening')
                                <i class="bi bi-search me-1"></i>
                                @break
                            @case('interview')
                                <i class="bi bi-person-video2 me-1"></i>
                                @break
                            @case('offer')
                                <i class="bi bi-check-circle me-1"></i>
                                @break
                            @case('rejected')
                                <i class="bi bi-x-circle me-1"></i>
                                @break
                        @endswitch
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Candidate Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Candidate Information</h5>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted small">FULL NAME</label>
                                <p class="fs-5 fw-semibold mb-0">{{ $application->full_name }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted small">POSITION APPLIED FOR</label>
                                <p class="fs-5 fw-semibold mb-0 text-primary">{{ $application->position }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted small">EMAIL ADDRESS</label>
                                <p class="mb-0">
                                    @if($application->email)
                                        <i class="bi bi-envelope me-2"></i>{{ $application->email }}
                                    @else
                                        <span class="text-muted fst-italic">Not provided</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted small">PHONE NUMBER</label>
                                <p class="mb-0">
                                    <i class="bi bi-telephone me-2"></i>{{ $application->phone }}
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted small">APPLICATION DATE</label>
                                <p class="mb-0">
                                    <i class="bi bi-calendar me-2"></i>{{ $application->created_at->format('M d, Y \a\t g:i A') }}
                                    <br><small class="text-muted">{{ $application->created_at->diffForHumans() }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Work Experience -->
            @if($application->work_experience)
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-briefcase me-2"></i>Work Experience</h5>
                    </div>
                    <div class="card-body">
                        <div class="p-3 bg-light rounded">
                            <p class="mb-0 lh-lg">{{ $application->work_experience }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Resume -->
            @if(isset($application->resume_path) && $application->resume_path)
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-file-earmark-pdf me-2"></i>Resume</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                            <div>
                                <i class="bi bi-file-earmark-pdf fs-3 text-danger me-3"></i>
                                <span class="fw-semibold">{{ $application->full_name }}_Resume.pdf</span>
                            </div>
                            <a href="{{ Storage::url($application->resume_path) }}" 
                               class="btn btn-outline-primary" target="_blank">
                                <i class="bi bi-download me-1"></i>Download
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Status Update Form -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Status Management</h5>
                </div>
                <div class="card-body">
                    <!-- Current Status Display -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">CURRENT STATUS</label>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-{{ $application->status == 'applied' ? 'primary' : 
                                                   ($application->status == 'screening' ? 'info' : 
                                                   ($application->status == 'interview' ? 'warning' : 
                                                   ($application->status == 'offer' ? 'success' : 'danger'))) }} fs-6 px-3 py-2">
                                @switch($application->status)
                                    @case('applied')
                                        <i class="bi bi-file-plus me-1"></i>
                                        @break
                                    @case('screening')
                                        <i class="bi bi-search me-1"></i>
                                        @break
                                    @case('interview')
                                        <i class="bi bi-person-video2 me-1"></i>
                                        @break
                                    @case('offer')
                                        <i class="bi bi-check-circle me-1"></i>
                                        @break
                                    @case('rejected')
                                        <i class="bi bi-x-circle me-1"></i>
                                        @break
                                @endswitch
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Status Update Form -->
                    <form method="POST" action="{{ route('recruiter.updateStatus', $application) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold">Update Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="">Select new status</option>
                                <option value="applied" {{ $application->status == 'applied' ? 'selected' : '' }}>
                                    <i class="bi bi-file-plus"></i> Applied
                                </option>
                                <option value="screening" {{ $application->status == 'screening' ? 'selected' : '' }}>
                                    <i class="bi bi-search"></i> Screening
                                </option>
                                <option value="interview" {{ $application->status == 'interview' ? 'selected' : '' }}>
                                    <i class="bi bi-person-video2"></i> Interview
                                </option>
                                <option value="offer" {{ $application->status == 'offer' ? 'selected' : '' }}>
                                    <i class="bi bi-check-circle"></i> Offer
                                </option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>
                                    <i class="bi bi-x-circle"></i> Rejected
                                </option>
                            </select>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-lg me-1"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Application Summary -->
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Application Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="mb-2">
                                <label class="form-label fw-semibold text-muted small">APPLICATION ID</label>
                                <p class="mb-0 fw-semibold">#{{ $application->id }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-2">
                                <label class="form-label fw-semibold text-muted small">SUBMITTED</label>
                                <p class="mb-0">{{ $application->created_at->diffForHumans() }}</p>
                                <small class="text-muted">{{ $application->created_at->format('M d, Y \a\t g:i A') }}</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-2">
                                <label class="form-label fw-semibold text-muted small">LAST UPDATED</label>
                                <p class="mb-0">{{ $application->updated_at->diffForHumans() }}</p>
                                <small class="text-muted">{{ $application->updated_at->format('M d, Y \a\t g:i A') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>
@endsection
