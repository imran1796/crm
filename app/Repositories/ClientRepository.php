<?php

namespace App\Repositories;


use App\Models\Client;
use App\Interfaces\ClientRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ClientRepository implements ClientRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = [])
    {
        $query = Client::with(['submissions',/*'tasks',*/'assignedUser']);

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', (int)$filters['assigned_to']);
        }

        if (!empty($filters['company'])) {
            $query->where('company', 'like', '%'.$filters['company'].'%');
        }

        return $query->orderBy('id','desc')->paginate($perPage);
    }

    public function find(int $id)
    {
        return Client::with(['submissions',/*'tasks',*/'assignedUser'])->findOrFail($id);
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();

            $client = Client::create($data);

            // generate client_number based on id (safe because insert happened)
            $clientNumber = 'CL-' . str_pad($client->id, 5, '0', STR_PAD_LEFT);
            $client->client_number = $clientNumber;
            $client->save();

            DB::commit();
            return $client;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Client Create Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(int $id, array $data)
    {
        try {
            DB::beginTransaction();

            $client = Client::findOrFail($id);
            $client->update($data);

            DB::commit();
            return $client;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Client Update Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            DB::beginTransaction();

            $client = Client::findOrFail($id);
            $client->delete();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Client Delete Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function search(string $term, int $perPage = 15)
    {
        // Try fulltext first, fallback to LIKE
        try {
            $results = Client::select('*')
                ->whereRaw("MATCH(name, email, company) AGAINST (? IN NATURAL LANGUAGE MODE)", [$term])
                ->with(['submissions','tasks','assignedUser'])
                ->paginate($perPage);

            return $results;
        } catch (\Exception $e) {
            // fallback to LIKE queries
            return Client::with(['submissions','tasks','assignedUser'])
                ->where(function($q) use ($term) {
                    $q->where('name', 'like', "%$term%")
                        ->orWhere('email', 'like', "%$term%")
                        ->orWhere('company', 'like', "%$term%");
                })
                ->paginate($perPage);
        }
    }

    public function countAll()
    {
        return Client::count();
    }

    public function findByEmail(string $email)
    {
        return Client::where('email', $email)->first();
    }
}
