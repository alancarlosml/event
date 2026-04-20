<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'Estas credenciales no coinciden con nuestros registros.',
    'password' => 'La contraseña proporcionada es incorrecta.',
    'throttle' => 'Demasiados intentos de acceso. Por favor, intente nuevamente en :seconds segundos.',

    'login' => [
        'cover_title' => 'Inicie sesión para organizar, vender y realizar el seguimiento de sus eventos.',
        'cover_subtitle' => 'Una sola cuenta para crear eventos, revisar inscripciones, realizar el seguimiento de certificados y operar el panel sin cambiar de contexto.',
        'benefit_1' => 'Flujo de compra, certificados y gestión en el mismo producto.',
        'benefit_2' => 'Experiencia consistente en escritorio y móvil.',
        'benefit_3' => 'Acceso rápido a lo que necesita hacer ahora.',
        'welcome_back' => 'Bienvenido de nuevo',
        'title' => 'Entrar',
        'description' => 'Acceda a su cuenta para continuar gestionando eventos, inscripciones y pagos en un solo lugar.',
        'email' => 'Correo electrónico',
        'password' => 'Contraseña',
        'remember' => 'Recordarme',
        'forgot_password' => '¿Olvidó su contraseña?',
        'not_registered' => '¿No está registrado?',
        'register_link' => 'Registrarse',
    ],

    'register' => [
        'title' => 'Registrarse',
        'name' => 'Nombre completo',
        'name_placeholder' => 'Nombre',
        'email' => 'Correo electrónico',
        'phone' => 'Teléfono',
        'password' => 'Contraseña',
        'password_confirmation' => 'Confirmar contraseña',
        'agree_terms' => 'Acepto los :terms y la :privacy',
        'terms' => 'términos',
        'privacy' => 'política de privacidad',
        'already_registered' => '¿Ya está registrado?',
        'login_link' => 'Entrar',
    ],

    'forgot_password_page' => [
        'title' => '¿Olvidó su contraseña?',
        'description' => '¿Olvidó su contraseña? No hay problema. Simplemente díganos su dirección de correo electrónico y le enviaremos un enlace para restablecer su contraseña que le permitirá elegir una nueva.',
        'email_label' => 'Ingrese su correo electrónico',
        'submit_button' => 'Enviar correo electrónico',
    ],

    'verify_email' => [
        'title' => 'Verifique su correo electrónico',
        'description' => '¡Gracias por registrarse en Ticket DZ6! Antes de comenzar, ¿podría verificar su dirección de correo electrónico haciendo clic en el enlace que acabamos de enviarle? Si no recibió el correo electrónico, con gusto le enviaremos otro.',
        'sent' => 'Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionó durante el registro.',
        'resend_button' => 'Reenviar correo electrónico',
        'logout_button' => 'Cerrar sesión',
    ],

];
