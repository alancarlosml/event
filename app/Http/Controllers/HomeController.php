<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Event;
// use App\Models\Faq;

class HomeController extends Controller
{
    public function home(){

        // $menu = 'home';
        // $title = 'Home';
        // $url = url('/');
        // $description = 'Bilhete Mania - Venda de ingressos online';
        // $image = url('img/favicon/favicon-96x96.png');

        $category = new Category;
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

    public function events(){

        // $menu = 'home';
        // $title = 'Home';
        // $url = url('/');
        // $description = 'Bilhete Mania - Venda de ingressos online';
        // $image = url('img/favicon/favicon-96x96.png');

        $event = new Event;
        // $event = new Event;
        // // $faq = new Faq;

        $events = $event->getAll();
        // $events = $event->getAll();
        // // $faqs = $faq->getAll();

        // $nextevents = DB::table('events')->where('active', 1)->take(6)->orderBy('created_at', 'desc')->get();

        // $spotlights = $event->getAllSpotlights();

        // dd($events->get(0)->category);

        return view('site.events', compact('events'));
    }

    public function event($slug){

        // $menu = 'home';
        // $title = 'Home';
        // $url = url('/');
        // $description = 'Bilhete Mania - Venda de ingressos online';
        // $image = url('img/favicon/favicon-96x96.png');

        $event = Event::where('slug', $slug)->first();
        // $event = new Event;
        // // $faq = new Faq;

        // $events = $event->getAll();
        // // $faqs = $faq->getAll();

        // $nextevents = DB::table('events')->where('active', 1)->take(6)->orderBy('created_at', 'desc')->get();

        // $spotlights = $event->getAllSpotlights();

        // dd($events->get(0)->category);

        return view('site.event', compact('event'));
    }

    // public function clear()
    // {
    //    Artisan::call('cache:clear'); 
    //    \Artisan::call('config:clear'); 
    //    \Artisan::call('view:clear'); 

    //    return 'All has been cleared';
    // }

}
