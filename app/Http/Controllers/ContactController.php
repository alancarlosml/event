<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {

        $contacts = Contact::orderBy('created_at', 'desc')->get();

        return view('contact.index', compact('contacts'));
    }

    public function show($id)
    {

        $contact = Contact::find($id);

        return view('contact.show', compact('contact'));
    }
}
