<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        DB::listen(function ($query) {
            Log::info(
                $query->sql,
                $query->bindings,
                $query->time
            );
        });

        // ResetPassword::createUrlUsing(function ($user, string $token) {
        //     // return 'http://localhost:/reset-password?token='.$token;
        //     return env('APP_URL_CLIENT').'/oauth/reset-password?token='.$token.'&email='.$user->email;
        // });

        // VerifyEmail::toMailUsing(function ($notifiable, $url) {
        //     dd(Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)));
        //     $new_url = URL::temporarySignedRoute(
        //         'verification.verify',
        //         Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
        //         [
        //             'id' => $notifiable->getKey(),
        //             'hash' => sha1($notifiable->getEmailForVerification()),
        //         ]
        //     );

        //     return (new MailMessage)
        //     ->subject(Lang::get('Verify Email Address'))
        //     ->line(Lang::get('Please click the button below to verify your email address.'))
        //     ->action(Lang::get('Verify Email Address'), $new_url)
        //     ->line(Lang::get('If you did not create an account, no further action is required.'));
        // });

        // VerifyEmail::toMailUsing(function ($notifiable){
        //     $verifyUrl = URL::temporarySignedRoute(
        //         'verification.verify',
        //         Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
        //         ['id' => $notifiable->getKey()]
        //     );
        //     return new EmailVerification($verifyUrl, $notifiable);
        // });
    }
}
