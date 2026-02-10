<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MailTestService;
use Illuminate\Http\Request;

class MailTestController extends Controller
{
    protected $service;

    public function __construct(MailTestService $service)
    {
        $this->service = $service;
    }

    public function test(Request $request)
    {
        $this->service->sendTestEmail($request->email);

        return response()->json([
            'success' => true,
            'message' => 'Test email sent successfully'
        ]);
    }
}
