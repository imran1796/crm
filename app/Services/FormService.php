<?php


namespace App\Services;

use App\Interfaces\FormRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FormService
{
    protected FormRepositoryInterface $forms;

    public function __construct(FormRepositoryInterface $forms)
    {
        $this->forms = $forms;
    }

    public function getAllForms()
    {
        return $this->forms->all();
    }

    public function createForm(array $data, int $userId)
    {
        try {
            DB::beginTransaction();

            $slug = Str::slug($data['name']);
            $apiKey = Str::random(40);

            $formData = [
                'name' => $data['name'],
                'slug' => $slug,
                'api_key_hash' => hash('sha256', $apiKey),
                'status' => true,
                'created_by' => $userId,
            ];


            $form = $this->forms->create($formData);



            DB::commit();
            Log::info('Form created with API key', ['form_id' => $form->id]);

            // Return API key (visible once)
            return ['form' => $form, 'api_key' => $apiKey];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('FormService::createForm failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function toggleStatus($id)
    {
        return $this->forms->toggleStatus($id);
    }

    public function deleteForm($id)
    {
        return $this->forms->delete($id);
    }
}
