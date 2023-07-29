<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Participante;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Notifications\WelcomeEmailNotification;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        session()->put('previousUrl', url()->previous());

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:participantes',
            'phone' => 'required|string',
            'read_terms' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // 'g-recaptcha-response' => 'required|recaptchav3:register,0.5'
        ], [
            'read_terms.required' => 'Você deve aceitar os termos e política de privacidade para se registrar.',
            // 'g-recaptcha-response.recaptchav3' => 'Por favor, verifique se você não é um robô.'
        ]);

        $participante = Participante::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'read_terms' => $request->read_terms,
            'password' => Hash::make($request->password),
            'status' => 1, // cadastrado mas não confirmado ainda
        ]);

        $email_participante = $request->email;

        $participante->notify(new WelcomeEmailNotification($participante));

        event(new Registered($participante));

        // Auth::login($user);

        // $previousUrl = str_replace(url('/'), '', session()->get('previousUrl', '/'));

        return view('auth.verify', compact('email_participante'));
        // return redirect('/cadastro/sucesso')->with('email_participante');

        // return redirect()->intended($previousUrl);
    }

    public function verify(Request $request)
    {

        return view('auth.verify');
    }
}
