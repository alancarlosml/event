<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Area;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Event;
// use App\Models\Faq;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {

        $site_info = [
            'menu' => 'home',
            'title' => 'Home',
            'description' => 'Ticket DZ6 - Venda de ingressos online',
        ];

        $category = new Category();

        $categories = $category->getAll();

        return view('site.home', compact('categories', 'site_info'));
    }

    public function show_contact_form()
    {
        $site_info = [
            'menu' => 'contact',
            'title' => 'Contato',
            'description' => 'Ticket DZ6 - Venda de ingressos online',
        ];

        return view('site.contact', compact('site_info'));
    }

    public function send(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'phone' => 'required|string',
            'subject' => 'required',
            'text' => 'required',
            'g-recaptcha-response' => 'required|recaptchav3:register,0.5',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'phone.required' => 'O campo telefone é obrigatório.',
            'subject.required' => 'O campo assunto é obrigatório.',
            'text.required' => 'O campo mensagem é obrigatório.',
            'g-recaptcha-response.required' => 'O campo captcha é obrigatório.',
        ]);

        $input = $request->all();

        $contact = Contact::create($input);

        return response()->json(['ok' => 'OK']);
    }

    public function getAreas(Request $request)
    {
        $data['areas'] = Area::where('category_id', $request->category_id)
            ->where('status', 1)
            ->get(['name', 'id']);

        return response()->json($data);
    }

    // public function clear()
    // {
    //    Artisan::call('cache:clear');
    //    \Artisan::call('config:clear');
    //    \Artisan::call('view:clear');

    //    return 'All has been cleared';
    // }

}
