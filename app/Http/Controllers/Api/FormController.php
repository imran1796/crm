<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FormService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormController extends Controller
{
    protected FormService $formService;

    public function __construct(FormService $formService)
    {
        $this->middleware(['auth:sanctum', 'role:system-admin|Manager']);
        $this->formService = $formService;
    }

    public function index()
    {
        $forms = $this->formService->getAllForms();
        return response()->json(['success' => true, 'data' => $forms]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $result = $this->formService->createForm($validated, Auth::id());
        return response()->json([
            'success' => true,
            'message' => 'Form created successfully',
            'data' => $result,
        ]);
    }

    public function toggle($id)
    {
        $form = $this->formService->toggleStatus($id);
        return response()->json([
            'success' => true,
            'message' => 'Form status updated',
            'data' => $form,
        ]);
    }
    public function destroy($id)
    {
        $this->formService->deleteForm($id);
        return response()->json([
            'success' => true,
            'message' => 'Form deleted successfully',
        ]);
    }
}
