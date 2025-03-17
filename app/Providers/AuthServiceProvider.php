<?php

namespace App\Providers;

use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use App\Models\User;
use App\Policies\IncomingLetterPolicy;
use App\Policies\OutgoingLetterPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        IncomingLetter::class => IncomingLetterPolicy::class,
        OutgoingLetter::class => OutgoingLetterPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
