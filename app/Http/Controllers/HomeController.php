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

        // $menu = 'home';
        // $title = 'Home';
        // $url = url('/');
        // $description = 'Bilhete Mania - Venda de ingressos online';
        // $image = url('img/favicon/favicon-96x96.png');

        // dd(Auth::getDefaultDriver());

        $category = new Category();
        // $event = new Event;
        // // $faq = new Faq;

        $categories = $category->getAll();
        // $events = $event->getAll();
        // // $faqs = $faq->getAll();

        // $nextevents = DB::table('events')->where('active', 1)->take(6)->orderBy('created_at', 'desc')->get();

        // $spotlights = $event->getAllSpotlights();

        // dd($events->get(0)->category);

        return view('site.home', compact('categories'));
    }

    public function show_contact_form()
    {
        return view('site.contact');
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
        ]);

        $input = $request->all();

        $contact = Contact::create($input);

        return response()->json(['ok' => 'OK']);

        // $menu = 'contact';
        // $title = 'Entre em contato conosco';
        // $url = url('/contato');
        // $description = 'Entre em contato com a Bilhete Mania';
        // $image = url('img/favicon/favicon-96x96.png');

        // $category = new Category;

        // $categories = $category->getAll();

        // $nextevents = DB::table('events')->where('active', 1)->take(6)->get();

        // return view('contact', compact('title', 'url', 'description', 'image', 'menu', 'categories', 'nextevents'));
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
