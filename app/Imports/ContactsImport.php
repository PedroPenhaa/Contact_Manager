<?php

namespace App\Imports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ContactsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithCustomCsvSettings
{
    use Importable;

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => "\t",
            'input_encoding' => 'UTF-8',
            'line_ending' => PHP_EOL,
        ];
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            Log::info('Processing row', ['data' => $row]);

            // Limpa o número de telefone para manter apenas números
            $phone = preg_replace('/\D/', '', $row['phone']);

            $contact = new Contact([
                'name' => $row['name'],
                'email' => $row['e-mail'],
                'phone' => $phone,
            ]);

            Log::info('Contact created from import', [
                'name' => $contact->name,
                'email' => $contact->email,
                'phone' => $contact->phone
            ]);

            return $contact;
        } catch (\Exception $e) {
            Log::error('Error processing import row', [
                'row' => $row,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'e-mail' => 'required|email|unique:contacts,email',
            'phone' => 'required'
        ];
    }

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            Log::warning('Row import failed', [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors()
            ]);
        }
    }
} 