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

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    'login' => [
        'cover_title' => 'Sign in to organize, sell, and track your events.',
        'cover_subtitle' => 'One account to create events, review registrations, track certificates, and operate the dashboard without switching contexts.',
        'benefit_1' => 'Purchase flow, certificates, and management in the same product.',
        'benefit_2' => 'Consistent experience on desktop and mobile.',
        'benefit_3' => 'Quick access to what you need to do now.',
        'welcome_back' => 'Welcome back',
        'title' => 'Sign in',
        'description' => 'Access your account to continue managing events, registrations, and payments in one place.',
        'email' => 'Email',
        'password' => 'Password',
        'remember' => 'Remember Me',
        'forgot_password' => 'Forgot password?',
        'not_registered' => 'Not registered?',
        'register_link' => 'Sign up',
    ],

    'register' => [
        'title' => 'Sign up',
        'name' => 'Full name',
        'name_placeholder' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'password' => 'Password',
        'password_confirmation' => 'Confirm password',
        'agree_terms' => 'I agree to the :terms and :privacy',
        'terms' => 'terms',
        'privacy' => 'privacy policy',
        'already_registered' => 'Already registered?',
        'login_link' => 'Sign in',
    ],

    'forgot_password_page' => [
        'title' => 'Forgot your password?',
        'description' => 'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.',
        'email_label' => 'Enter your email',
        'submit_button' => 'Send email',
    ],

    'verify_email' => [
        'title' => 'Verify your email',
        'description' => "Thanks for signing up for Ticket DZ6! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.",
        'sent' => 'A new verification link has been sent to the email address you provided during registration.',
        'resend_button' => 'Resend email',
        'logout_button' => 'Log Out',
    ],

];
