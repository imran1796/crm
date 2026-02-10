<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Hash;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class UserController extends Controller

{
    protected $userService;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

        //     $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'show']]);
        //     $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        //     $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        //     $this->middleware('permission:user-delete', ['only' => ['destroy']]);
        //
    }

    public function index(Request $request)
    {
        $data = $this->userService->list()->groupBy('department_id');
        return view('users.index', compact('data'));
    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {
        $roles = Role::where('name', '!=', 'System Admin')->pluck('name', 'name')->all();


        // if (Auth::user()->getRoleNames()->toArray()[0] == 'System Admin') {
        //     $companies = Company::with('principles')->get();
        // } else {
        //     $companies = Company::with('principles')->where('id', Auth::user()->company_id)->get();
        // }


        return view('users.create', compact('roles'));
    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
        ]);

        // dd($request->roles);
        \DB::beginTransaction();
        try {
            $input = $request->all();
            // $verificationPassword = $input['password'];
            $input['password'] = Hash::make($input['password']);



            $user = User::create($input);
            $user->assignRole($request->input('roles'));
            // $user->notify(new CustomVerifyEmail([$user->name, $user->email, $verificationPassword]));

            \DB::commit();

            return response()->json(['success' => 'Successfully Created User'], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error creating User: ' . $e->getMessage());
            return response()->json(['error' => "Error creating user: " . $e->getMessage()], 500);
        }
    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::where('name', '!=', 'System Admin')->pluck('name', 'name')->all();
        $userRoles = $user->roles->pluck('name')->toArray();
        $departments = Department::all();
        $designations = Designation::all();
        $branches = Branch::all();

        return view('users.edit', compact('user', 'roles', 'userRoles', 'departments', 'designations', 'branches'));
    }




    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required',
            'department' => 'nullable',
            'designation' => 'nullable'
        ]);

        \DB::beginTransaction();
        try {
            $user = User::findOrFail($id);

            // Only update password if provided
            $input = $request->all();
            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                unset($input['password']);
            }

            $user->update($input);

            // Update user roles
            $user->syncRoles($request->input('roles'));

            \DB::commit();
            return response()->json(['success' => 'Successfully Updated User'], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error updating User: ' . $e->getMessage());
            return response()->json(['error' => "Error updating user: " . $e->getMessage()], 500);
        }
    }




    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    // public function verifyUser($id)
    // {
    //     $user = User::findOrFail($id);

    //     $user->email_verified_at = now();
    //     $user->save();

    //     return redirect()->back()->with('success', 'User Verified Successfully.');
    // }

    public function verifyUser($id)
    {
        \DB::beginTransaction();
        try {
            $user = User::findOrFail($id);

            $isNowVerified = is_null($user->email_verified_at);

            $user->email_verified_at = $isNowVerified ? now() : null;
            $user->save();

            $message = $isNowVerified ? 'User verified successfully.' : 'User unverified.';
            \DB::commit();
            return response()->json(['success' => $message], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error Verifying User: ' . $e->getMessage());
            return response()->json(['error' => "An Error Occured"], 500);
        }
    }

    public function userPermission($id)
    {
        $permissions = Permission::all()
            ->groupBy('department')
            ->map(function ($group) {
                return $group->sortBy('name');
            })
            ->sortBy(function ($group) {
                return $group->count();
            });

        $user = User::with(['roles.permissions', 'permissions'])->findOrFail($id);

        return view('users.permission', compact('user', 'permissions'));
    }

    public function updateUserPermission(Request $request)
    {
        \DB::beginTransaction();
        try {
            $user = User::findOrFail($request->user_id);
            $permissions = $request->input('permission', []);
            $user->syncPermissions($permissions);

            \DB::commit();

            return response()->json(['success' => 'Successfully Assigned Permission'], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error Assigning Permission to User: ' . $e->getMessage());
            return response()->json(['error' => "Error Assigning Permission: " . $e->getMessage()], 500);
        }
    }

    public function indexDepartment(Request $request)
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }
    public function storeDepartment(Request $request)
    {
        // dd($request->all());
        \DB::beginTransaction();
        try {
            Department::create($request->toArray());
            \DB::commit();
            return response()->json(['success' => 'Successfully Created Department'], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error Creating Department: ' . $e->getMessage());
            return response()->json(['error' => "Error Creating Department: "], 500);
        }
    }
    public function updateDepartment(Request $request, $id)
    {
        // dd($request->toArray());
        \DB::beginTransaction();
        try {
            $department = Department::findOrFail($id);
            $department->update([
                'department_name' => $request->department_name,
            ]);
            \DB::commit();
            return response()->json(['success' => 'Successfully Updated Department'], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error Updating Department: ' . $e->getMessage());
            return response()->json(['error' => "Error Updating Department: "], 500);
        }
    }

    public function deleteDepartment(Request $request, $id)
    {
        // dd($request->toArray());
        \DB::beginTransaction();
        try {
            $department = Department::findOrFail($id);
            $department->delete();
            \DB::commit();
            return response()->json(['success' => 'Successfully Deleted Department'], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error Deleting Department: ' . $e->getMessage());
            return response()->json(['error' => "Error Deleting Department: "], 500);
        }
    }

    public function indexDesignation(Request $request)
    {
        $designations = Designation::all();
        return view('designations.index', compact('designations'));
    }
    public function storeDesignation(Request $request)
    {
        // dd($request->all());
        \DB::beginTransaction();
        try {
            Designation::create($request->toArray());
            \DB::commit();
            return response()->json(['success' => 'Successfully Created Designation'], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error Creating Designation: ' . $e->getMessage());
            return response()->json(['error' => "Error Creating Designation: "], 500);
        }
    }
    public function updateDesignation(Request $request, $id)
    {
        // dd($request->toArray());
        \DB::beginTransaction();
        try {
            $designation = Designation::findOrFail($id);
            $designation->update([
                'designation' => $request->designation,
                'value' => $request->value
            ]);
            \DB::commit();
            return response()->json(['success' => 'Successfully Updated Designation'], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error Updating Designation: ' . $e->getMessage());
            return response()->json(['error' => "Error Updating Designation: "], 500);
        }
    }

    public function deleteDesignation(Request $request, $id)
    {
        // dd($request->toArray());
        \DB::beginTransaction();
        try {
            $designation = Designation::findOrFail($id);
            $designation->delete();
            \DB::commit();
            return response()->json(['success' => 'Successfully Deleted Designation'], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error Deleting Designation: ' . $e->getMessage());
            return response()->json(['error' => "Error Deleting Designation: "], 500);
        }
    }
}
