<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PasswordResetService;
use App\Http\Requests\PasswordResetSendRequest;
use App\Http\Requests\PasswordResetUpdateRequest;
use Illuminate\Support\Facades\Log;

class PasswordResetController extends Controller
{
    protected $service;

    public function __construct(PasswordResetService $service)
    {
        $this->service = $service;
    }

    /**
     * Send reset link to email (does not reveal existence)
     */
    public function sendResetLink(PasswordResetSendRequest $request)
    {
        try {
            $this->service->sendResetLink($request->email);
            return response()->json(['success' => true, 'message' => 'If the email exists, a reset link has been sent.']);
        } catch (\Exception $e) {
            Log::error('SendResetLink Error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send reset link'], 500);
        }
    }

    /**
     * Reset password using token
     */
    public function reset(PasswordResetUpdateRequest $request)
    {
        try {
            $this->service->resetPassword($request->email, $request->token, $request->password);
            return response()->json(['success' => true, 'message' => 'Password reset successfully.']);
        } catch (\Exception $e) {
            Log::error('ResetPassword Error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
