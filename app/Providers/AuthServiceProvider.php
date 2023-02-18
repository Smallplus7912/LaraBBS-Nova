<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Link;
use App\Models\User;
use App\Nova\Links;
use App\Policies\CategoriesPolicy;
use App\Policies\EditSettingPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Telescope\Telescope;
use Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Reply::class => \App\Policies\ReplyPolicy::class,
        \App\Models\Topic::class => \App\Policies\TopicPolicy::class,
        Category::class => CategoriesPolicy::class,
        Link::class => EditSettingPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Gate::guessPolicyNamesUsing(function ($modelClass) {
            // 动态返回模型对应的策略名称，如：// 'App\Model\User' => 'App\Policies\UserPolicy',
            return 'App\Policies\\'.class_basename($modelClass).'Policy';
        });

        \Horizon::auth(function ($request) {
            // 是否是站长
            return \Auth::user()->hasRole('founder');
        });
        Telescope::auth(function () {
            return \Auth::user()->hasRole('founder');
        });
    }
}
