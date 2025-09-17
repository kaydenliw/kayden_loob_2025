<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationConfirmation;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $applications = Application::with('jobListing')
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $applications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch applications.'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApplicationRequest $request): JsonResponse
    {
        try {
            $application = Application::create($request->validated());

            // Send confirmation email if email is provided
            if ($application->email) {
                Mail::to($application->email)->send(new ApplicationConfirmation($application));
            }

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully',
                'application_id' => $application->id
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit application. Please try again.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Get application status by phone or email
     */
    public function status(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required_without:email|string',
            'email' => 'required_without:phone|email',
        ]);

        $query = Application::query();

        if ($request->phone) {
            $query->where('phone', $request->phone);
        } elseif ($request->email) {
            $query->where('email', $request->email);
        }

        $application = $query->latest()->first();

        if (!$application) {
            return response()->json([
                'success' => false,
                'message' => 'No application found for the provided contact information.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'status' => $application->status,
            'application_id' => $application->id,
            'position' => $application->position,
            'created_at' => $application->created_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
