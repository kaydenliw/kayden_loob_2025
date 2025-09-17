<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RecruiterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the recruiter dashboard
     */
    public function dashboard(Request $request): View
    {
        $query = Application::query();

        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by position if provided
        if ($request->has('position') && $request->position !== '') {
            $query->where('position', 'like', '%' . $request->position . '%');
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(15);
        $statusOptions = ['applied', 'screening', 'interview', 'offer', 'rejected'];

        return view('recruiter.dashboard', compact('applications', 'statusOptions'));
    }

    /**
     * Show application details
     */
    public function show(Application $application): View
    {
        return view('recruiter.application', compact('application'));
    }

    /**
     * Update application status
     */
    public function updateStatus(Request $request, Application $application): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:applied,screening,interview,offer,rejected'
        ]);

        $application->update([
            'status' => $request->status
        ]);

        return redirect()->route('recruiter.show', $application)
            ->with('success', 'Application status updated successfully!');
    }
}
