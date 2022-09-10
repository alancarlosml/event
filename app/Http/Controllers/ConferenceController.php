<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Category;
use App\Models\Configuration;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Lote;
use App\Models\Participante;
use App\Models\ParticipanteLote;
use App\Models\Place;
use App\Models\Question;
use App\Models\Option;
use App\Models\Order;
use App\Models\Owner;
use App\Models\User;
use App\Models\State;

class ConferenceController extends Controller
{

    public function event(Request $request, $slug){

        // $menu = 'home';
        // $title = 'Home';
        // $url = url('/');
        // $description = 'Bilhete Mania - Venda de ingressos online';
        // $image = url('img/favicon/favicon-96x96.png');

        $event = Event::where('slug', $slug)->first();

        if($event)
        {
            $coupon = $request->session()->get('coupon');
            $subtotal = $request->session()->get('subtotal');
            $coupon_subtotal = $request->session()->get('coupon_subtotal');
            $total = $request->session()->get('total');

            return view('site.event', compact('event', 'coupon', 'subtotal', 'coupon_subtotal', 'total'));
        
        }else{

            $request->session()->forget('coupon');
            $request->session()->forget('subtotal');
            $request->session()->forget('coupon_subtotal');
            $request->session()->forget('total');
            $request->session()->forget('dict_lotes');

            return redirect()->back(); //view de evento não encontrado
        }
        
    }

    public function resume(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)->first();

        $questions = Question::orderBy('order')->where('event_id', $event->id)->get();

        $coupon = $request->session()->get('coupon');
        $subtotal = $request->session()->get('subtotal');
        $coupon_subtotal = $request->session()->get('coupon_subtotal');
        $total = $request->session()->get('total');
        $dict_lotes = $request->session()->get('dict_lotes');

        if($dict_lotes)
        {
            $array_lotes = [];
            foreach($dict_lotes as $dict){

                $quantity = $dict['lote_quantity'];
                $lote = Lote::where('hash', $dict['lote_hash'])->first();

                if($quantity > 0){
                    $array = array($quantity, $lote->value);

                    array_push($array_lotes, $array);
                }
            }

            $request->session()->put('array_lotes', $array_lotes);

            return view('conference.resume', compact('event', 'questions', 'array_lotes', 'coupon', 'subtotal', 'coupon_subtotal', 'total'));
        
        }else{

            $request->session()->forget('coupon');
            $request->session()->forget('subtotal');
            $request->session()->forget('coupon_subtotal');
            $request->session()->forget('total');
            $request->session()->forget('dict_lotes');

            return redirect()->route('conference.index', $event->slug);
        }
    }

    public function getSubTotal(Request $request){

        $data = $request->all();

        $dicts = $data['dict'];

        $subtotal = 0;
        $coupon_subtotal = 0;
        $total = 0;

        $request->session()->put('dict_lotes', $dicts);
        
        if($dicts){
            foreach($dicts as $dict){

                $quantity = $dict['lote_quantity'];
                $lote = Lote::where('hash', $dict['lote_hash'])->first();

                if($lote->type == 0){

                    if($lote->tax_servico == 0){
                        $subtotal += ($lote->value + $lote->value*0.1) * $quantity;
                    }else{
                        $subtotal += $lote->value * $quantity;
                    }
                    
                    $coupon = $request->session()->get('coupon');

                    if($coupon){
                        $couponBelongs = false;
                        foreach($lote->coupons as $lote_cupom){
                            if($coupon[0]['code'] == $lote_cupom->code){
                                $couponBelongs = true;
                            }
                        }

                        if($couponBelongs){

                            $coupon_code = $coupon[0]['code'];
                            $coupon_type = $coupon[0]['type'];
                            $coupon_value = $coupon[0]['value'];

                            if($coupon_type == 0){
                                $coupon_subtotal = $subtotal*$coupon_value;
                            }else if($coupon_type == 1){
                                $coupon_subtotal = $coupon_value;
                            }

                            $total = $subtotal - $coupon_subtotal;
                        }

                    }else{
                        $total = $subtotal;
                    }
                }
            }

            $request->session()->put('subtotal', $subtotal);
            $request->session()->put('coupon_subtotal', $coupon_subtotal);
            $request->session()->put('total', $total);

            return response()->json(['success'=>'Ajax request submitted successfully', 'subtotal' => 'R$ '.number_format($subtotal, 2, ',', '.'), 'coupon_subtotal' => 'R$ '.number_format($coupon_subtotal, 2, ',', '.'), 'total' => 'R$ '.number_format($total, 2, ',', '.')]);
        
        }else{

            return redirect()->back();
        }
        
        return redirect()->back();
    }

    public function getCoupon(Request $request){

        $data = $request->all();

        $eventHash = $data['eventHash'];
        $couponCode = $data['couponCode'];

        // $evento = Ticket::where('hash_id', $hashIdTicket)->first()->sector()->first()->date()->first()->event()->first();
        $evento = Event::where('hash', $eventHash)->first();

        $coupon = Coupon::where('code', $couponCode)->where('status', '1')->where('event_id', $evento->id)->first();
        
        if($coupon != null){

            $subtotal = $request->session()->get('subtotal');

            if($coupon->discount_type == 0){
                $coupon_discount = $subtotal*$coupon->discount_value;
            }else if($coupon->discount_type == 1){
                $coupon_discount = $coupon->discount_value;
            }

            $coupon = array(array('code' => $coupon->code, 'type' => $coupon->discount_type, 'value' => $coupon->discount_value));

            $request->session()->put('coupon', $coupon);
            $request->session()->put('coupon_discount', $coupon_discount);

            return response()->json(['success'=>'Cupom adicionado com sucesso!', 'coupon' => $coupon, 'coupon_discount' => $coupon_discount]);
        
        }else{

            return response()->json(['error'=>'Cupom inválido.']);
        }
    }

    // public function removeCoupon(){

    //     Session::forget('coupon');

    //     return redirect()->route('ticket.details');
    // }
}
