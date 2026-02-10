<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    protected $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->getAll()
        ]);
    }

    public function update(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Settings updated',
            'data' => $this->service->update($request->all())
        ]);
    }

    public function getNotifications()
    {
        $allSettings = $this->service->getAll();
        
        // Filter only notification-related settings (email_* and push_*)
        $notifications = [];
        
        if (isset($allSettings['email'])) {
            $notifications['email'] = $allSettings['email'];
        }
        
        if (isset($allSettings['push'])) {
            $notifications['push'] = $allSettings['push'];
        }
        
        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    public function updateNotifications(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Notification settings updated',
            'data' => $this->service->update($request->all())
        ]);
    }

    public function testMail(Request $request)
    {
        try {
            $settings = $this->service->getAll();
            
            // Get SMTP settings from nested structure
            $smtp = $settings['smtp'] ?? [];
            
            // Access SMTP settings with fallback to .env
            $smtpHost = $smtp['host'] ?? config('mail.mailers.smtp.host');
            $smtpPort = $smtp['port'] ?? config('mail.mailers.smtp.port', 587);
            $smtpUsername = $smtp['username'] ?? config('mail.mailers.smtp.username');
            $smtpPassword = $smtp['password'] ?? config('mail.mailers.smtp.password');
            $smtpEncryption = $smtp['encryption'] ?? config('mail.mailers.smtp.encryption', 'tls');
            
            // Get from_email from settings, but don't use Laravel's default "hello@example.com"
            // If not set in settings, use SMTP username instead
            $smtpFromEmail = $smtp['from_email'] ?? null;
            if (empty($smtpFromEmail)) {
                // Use .env value only if it's not the default
                $envFromEmail = config('mail.from.address');
                $smtpFromEmail = ($envFromEmail && $envFromEmail !== 'hello@example.com') 
                    ? $envFromEmail 
                    : $smtpUsername;
            }
            
            $smtpFromName = $smtp['from_name'] ?? config('mail.from.name') ?: 'CRM System';
            
            // Check if we have at least host configured
            if (empty($smtpHost)) {
                return response()->json([
                    'success' => false,
                    'message' => 'SMTP settings not configured. Please configure SMTP settings in the settings page or .env file.'
                ], 400);
            }

            // Ensure port is an integer
            $smtpPort = (int) $smtpPort;

            // Configure mail settings from settings table (with .env fallback)
            // Set the default mailer to smtp
            Config::set('mail.default', 'smtp');
            
            // Configure SMTP settings
            Config::set('mail.mailers.smtp.transport', 'smtp');
            Config::set('mail.mailers.smtp.host', $smtpHost);
            Config::set('mail.mailers.smtp.port', $smtpPort);
            Config::set('mail.mailers.smtp.encryption', $smtpEncryption);
            Config::set('mail.mailers.smtp.username', $smtpUsername);
            Config::set('mail.mailers.smtp.password', $smtpPassword);
            Config::set('mail.mailers.smtp.timeout', null);
            Config::set('mail.mailers.smtp.auth_mode', null);
            
            // Set from address and name (use SMTP username if from_email not set)
            Config::set('mail.from.address', $smtpFromEmail);
            Config::set('mail.from.name', $smtpFromName);

            // Hardcoded recipient email
            $toEmail = 'imuimran92@gmail.com';

            // Clear mail config cache and set complete SMTP configuration
            app()['config']->set('mail.mailers.smtp', [
                'transport' => 'smtp',
                'host' => $smtpHost,
                'port' => $smtpPort,
                'encryption' => $smtpEncryption,
                'username' => $smtpUsername,
                'password' => $smtpPassword,
                'timeout' => null,
                'auth_mode' => null,
            ]);

            // Log SMTP configuration (without password)
            Log::info('Sending test email', [
                'host' => $smtpHost,
                'port' => $smtpPort,
                'encryption' => $smtpEncryption,
                'username' => $smtpUsername,
                'from' => $smtpFromEmail ?: $smtpUsername,
                'to' => $toEmail
            ]);

            // Send test email
            Mail::raw('This is a test email from your CRM system. If you received this, your SMTP configuration is working correctly!', 
            function ($message) use ($toEmail, $smtpFromEmail, $smtpFromName, $smtpUsername) {
                $message->to($toEmail)
                    ->subject('Test Email - SMTP Configuration');
                
                // Set from address (use from_email or username as fallback)
                $fromEmail = $smtpFromEmail ?: $smtpUsername;
                $fromName = $smtpFromName ?: 'CRM System';
                
                if ($fromEmail) {
                    $message->from($fromEmail, $fromName);
                }
            });
    
            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully'
            ]);

        } catch (\Exception $e) {
            // Log the full error for debugging
            Log::error('Test email failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage(),
                'error_details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
    
}

