<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ClientService;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    protected $service;

    public function __construct(ClientService $service)
    {
        $this->service = $service;
        $this->middleware(['auth:sanctum', 'role:system-admin|Manager']);

    }

    public function index(Request $request)
    {
        $params = [
            'per_page' => $request->query('per_page', 15),
            'filters' => $request->only(['assigned_to','company'])
        ];

        $clients = $this->service->list($params);
        return response()->json($clients);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'custom_fields' => 'nullable|array'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success'=>false,'errors'=>$validator->errors()], 422);
        }

        $client = $this->service->create($request->only([
            'name','surname','company','role','phone','email','street','postal_code','city','country','assigned_to','custom_fields'
        ]));

        return response()->json(['success'=>true,'data'=>$client]);
    }

    public function show($id)
    {
        $client = $this->service->find((int)$id);
        return response()->json(['success'=>true,'data'=>$client]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'custom_fields' => 'nullable|array'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success'=>false,'errors'=>$validator->errors()], 422);
        }

        $client = $this->service->update((int)$id, $request->only([
            'name','surname','company','role','phone','email','street','postal_code','city','country','assigned_to','custom_fields'
        ]));

        return response()->json(['success'=>true,'data'=>$client]);
    }

    public function destroy($id)
    {
        $this->service->delete((int)$id);
        return response()->json(['success'=>true,'message'=>'Client deleted']);
    }

    public function search(Request $request)
    {
        $term = $request->query('q', '');
        $perPage = $request->query('per_page', 15);
        $results = $this->service->search($term, ['per_page' => $perPage]);
        return response()->json($results);
    }
}
