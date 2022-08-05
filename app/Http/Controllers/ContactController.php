<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Contact;

class ContactController extends Controller
{
    public function index(){
        
        $contacts = Contact::orderBy('created_at')->get();

        return view('contact.index', compact('contacts'));
    }

    public function show($id){
                
        $contact = Contact::find($id);

        return view('contact.show', compact('contact'));
    }
}
