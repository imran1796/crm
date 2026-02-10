<?php

namespace App\Repositories;

use App\Models\Form;
use App\Interfaces\FormRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FormRepository implements FormRepositoryInterface
{
    protected Form $model;

    public function __construct(Form $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with('creator')->latest()->get();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {

        try {
            DB::beginTransaction();
            $form = $this->model->create($data);
            DB::commit();
            Log::info('Form created successfully', ['form_id' => $form->id]);
            return $form;
        } catch (\Throwable $e) { 
            DB::rollBack();
            Log::error('Form creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            DB::beginTransaction();
            $form = $this->find($id);
            $form->update($data);
            DB::commit();
            Log::info('Form updated successfully', ['form_id' => $form->id]);
            return $form;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Form update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $form = $this->find($id);
            $form->delete();
            DB::commit();
            Log::info('Form deleted successfully', ['form_id' => $form->id]);
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Form delete failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function toggleStatus($id)
    {
        try {
            DB::beginTransaction();
            $form = $this->find($id);
            $form->status = !$form->status;
            $form->save();
            DB::commit();
            Log::info('Form status toggled', ['form_id' => $form->id, 'status' => $form->status]);
            return $form;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Form status toggle failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
