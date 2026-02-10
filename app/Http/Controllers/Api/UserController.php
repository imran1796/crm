<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }
    public function findById($id){
        return response()->json($this->service->find($id));

    }

    public function index()
    {
        return response()->json($this->service->list());
    }

    public function store(UserStoreRequest $request)
    {
        $user = $this->service->create($request->validated());

        return response()->json([
            "message" => "User created",
            "data" => $user
        ]);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = $this->service->update($id, $request->validated());

        return response()->json([
            "message" => "User updated",
            "data" => $user
        ]);
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json(["message" => "User deleted"]);
    }

    public function profile()
    {
        $user = auth()->user();
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $updatedUser = $this->service->update($user->id, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $updatedUser
        ]);
    }
}
