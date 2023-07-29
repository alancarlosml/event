<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Coupon;
use App\Models\Event;
use App\Models\Lote;

class CouponController extends Controller
{
    public function coupons($id)
    {

        $event = Event::find($id);

        $coupons = Coupon::where('event_id', $id)->orderBy('created_at')->get();

        return view('coupon.coupons', compact('event', 'coupons'));
    }

    public function create_coupon($id)
    {

        $event = Event::find($id);

        $coupon_code = strtoupper(substr($event->name, 0, 2) . substr(sha1($event->id . $event->created_at . md5($event->name)), 0, 6));

        $lotes = Lote::orderBy('order')
            ->where('event_id', $id)
            ->get();

        return view('coupon.create_coupon', compact('event', 'lotes', 'id', 'coupon_code'));
    }

    public function store_coupon(Request $request, $id)
    {
        $event = Event::find($id);

        $this->validate($request, [
            'code' => 'required|unique:coupons',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'limit_buy' => 'required',
            'limit_tickets' => 'required',
        ]);

        $input = $request->all();

        $id_coupon = Coupon::create($input)->id;

        $coupon_obj = Coupon::find($id_coupon);

        $lotes = $input['lotes'];

        foreach($lotes as $lote) {

            $coupon_obj->lotes()->attach($lote);
        }

        $coupon_obj->fill($input)->save();

        $coupons = Coupon::where('event_id', $id)->orderBy('created_at')->get();

        $lotes = Lote::orderBy('order')
            ->where('event_id', $id)
            ->get();

        return view('coupon.coupons', compact('event', 'coupons', 'lotes'));
    }

    public function editCoupon($id)
    {

        $coupon = Coupon::find($id);

        $event = Event::find($coupon->event_id);

        $lotes = Lote::orderBy('order')
            ->where('event_id', $coupon->event_id)
            ->get();

        // dd($coupon->lotes);

        return view('coupon.coupon_edit', compact('event', 'coupon', 'lotes', 'id'));
    }

    public function update_coupon(Request $request, $id)
    {
        $coupon = Coupon::find($id);

        $event = Event::find($coupon->event_id);

        $this->validate($request, [
            'code' => 'required',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'limit_buy' => 'required',
            'limit_tickets' => 'required',
        ]);

        $input = $request->all();

        $input['event_id'] = $coupon->event_id;

        $lotes = $input['lotes'];

        $coupon->lotes()->detach();

        foreach($lotes as $lote) {

            $coupon->lotes()->attach($lote);
        }

        $coupon->fill($input)->save();

        $coupons = Coupon::where('event_id', $coupon->event_id)->orderBy('created_at')->get();

        return view('coupon.coupons', compact('event', 'coupons'));
    }

    public function destroy_coupon($id)
    {
        $coupon = Coupon::findOrFail($id);

        $coupon->delete();

        return redirect()->route('coupon.coupons');
    }
}
