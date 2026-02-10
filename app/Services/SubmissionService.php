<?php

namespace App\Services;

use App\Interfaces\ClientRepositoryInterface;
use App\Interfaces\SubmissionRepositoryInterface;
use App\Models\Form;
use Illuminate\Support\Facades\Log;
use App\Services\ClientService;
use App\Events\FormSubmittedEvent;

class SubmissionService
{
    protected $repo,$clientRepo;

    public function __construct(SubmissionRepositoryInterface $repo, ClientService $clientRepo)
    {
        $this->repo = $repo;
        $this->clientRepo = $clientRepo;
    }

    public function submit($slug, $request)
    {
        // Validate form existence
        $form = Form::where('slug', $slug)->firstOrFail();
        // Validate API key
        $apiKey = $request->header('X-API-Key');
        $hashed = hash('sha256', $apiKey);

        if ($hashed !== $form->api_key_hash) {
            Log::warning("Invalid API Key for Form ID {$form->id}");
            abort(401, 'Invalid API Key.');
        }

        // File upload
        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('submissions', 'public');
        }

        // ✅ Auto-link or auto-create client
        $clientId = $this->resolveClient($request->all());

        // Prepare Data
        $submissionData = [
            'form_id'   => $form->id,
            'client_id' => $clientId,
            'payload'   => $request->except(['file']),
            'file_path' => $path,
        ];

        // Store in DB
        $submission = $this->repo->create($submissionData);

        // ✅ Fire event AFTER storing

        event(new FormSubmittedEvent($submission));


        return $submission;
    }

    /**
     * Auto-detect client from submission payload
     */
    private function resolveClient(array $payload)
    {
        if (!isset($payload['email']) || empty($payload['email'])) {
            return null; // No email = cannot auto-link
        }

        $email = trim($payload['email']);


        // 1. Client exists → return id
        $existing = $this->clientRepo->findByEmail($email);
        if ($existing) {
            return $existing->id;
        }

        // 2. Create minimal client
        $client = $this->clientRepo->create([
            'name'    => $payload['name'] ?? 'Unknown',
            'email'   => $email,
            'phone'   => $payload['phone'] ?? null,
            'company' => $payload['company'] ?? null,
        ]);

        return $client->id;
    }


    public function list()
    {
        return $this->repo->all();
    }

    public function markAsRead($id)
    {
        return $this->repo->markRead($id);
    }


}
