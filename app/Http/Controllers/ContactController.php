<?php

namespace App\Http\Controllers;

use App\Jobs\SendContactDeletedNotification;
use App\Models\Contact;
use App\Imports\ContactsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{
    public function index()
    {
        try {
            $contacts = Contact::latest()->paginate(10);
            return Inertia::render('Contacts/Index', [
                'contacts' => $contacts
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching contacts: ' . $e->getMessage());
            return back()->with('error', 'Error loading contacts. Please try again.');
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Storing new contact', $request->all());
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'required|email|unique:contacts,email',
                'phone' => 'required|regex:/^\([0-9]{2}\)\s[0-9]{4,5}-[0-9]{4}$/'
            ]);

            if ($validator->fails()) {
                Log::warning('Contact validation failed', ['errors' => $validator->errors()]);
                return back()->withErrors($validator->errors());
            }

            $data = $request->all();
            $data['phone'] = preg_replace('/\D/', '', $data['phone']);

            $contact = Contact::create($data);
            Log::info('Contact created successfully', ['contact_id' => $contact->id]);

            return redirect()->route('contacts.index')->with('success', 'Contact created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating contact: ' . $e->getMessage());
            return back()->with('error', 'Error creating contact. Please try again.');
        }
    }

    public function update(Request $request, Contact $contact)
    {
        try {
            Log::info('Updating contact', ['contact_id' => $contact->id, 'data' => $request->all()]);
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'required|email|unique:contacts,email,' . $contact->id,
                'phone' => 'required|regex:/^\([0-9]{2}\)\s[0-9]{4,5}-[0-9]{4}$/'
            ]);

            if ($validator->fails()) {
                Log::warning('Contact update validation failed', ['errors' => $validator->errors()]);
                return back()->withErrors($validator->errors());
            }

            $data = $request->all();
            $data['phone'] = preg_replace('/\D/', '', $data['phone']);

            $contact->update($data);
            Log::info('Contact updated successfully', ['contact_id' => $contact->id]);

            return redirect()->route('contacts.index')->with('success', 'Contact updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating contact: ' . $e->getMessage());
            return back()->with('error', 'Error updating contact. Please try again.');
        }
    }

    public function destroy(Contact $contact)
    {
        try {
            Log::info('Deleting contact', ['contact_id' => $contact->id]);
            
            SendContactDeletedNotification::dispatch($contact);
            $contact->delete();
            
            Log::info('Contact deleted successfully', ['contact_id' => $contact->id]);
            return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting contact: ' . $e->getMessage());
            return back()->with('error', 'Error deleting contact. Please try again.');
        }
    }

    public function import(Request $request)
    {
        try {
            Log::info('Starting contact import process');
            
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:csv,xlsx,xls|max:2048'
            ]);

            if ($validator->fails()) {
                Log::warning('Import validation failed', ['errors' => $validator->errors()]);
                return back()->withErrors($validator->errors());
            }

            Log::info('File validation passed, proceeding with import');
            
            $file = $request->file('file');
            Log::info('File details', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize()
            ]);

            Excel::import(new ContactsImport, $file);
            Log::info('Import completed successfully');

            return redirect()->route('contacts.index')->with('success', 'Contacts imported successfully');
        } catch (\Exception $e) {
            Log::error('Error importing contacts', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error importing contacts: ' . $e->getMessage());
        }
    }
} 