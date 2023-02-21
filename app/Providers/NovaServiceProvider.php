<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

use App\Http\Controllers\TopicsController;
use App\Http\Controllers\UsersController;
use App\Nova\Metrics\NewTopics;
use App\Nova\Metrics\NewUsers;
use App\Nova\Metrics\TopicCounts;
//use App\Nova\Metrics\TopicsPerDay;
use App\Nova\Metrics\UserCounts;
//use App\Nova\Metrics\UsersPerDay;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\Textarea;
use Vyuldashev\NovaPermission\NovaPermissionTool;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Coroowicaksono\ChartJsIntegration\LineChart;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        \OptimistDigital\NovaSettings\NovaSettings::addSettingsFields([
            Text::make('站点名称', 'admin_name'),
            Text::make('站长邮箱', 'admin_email'),
            Textarea::make('SEO - Description', 'seo_description'),
            Textarea::make('SEO - Keywords ', 'seo_keyword'),
        ]);
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }


    //授权策略，定义谁可以访问nova面板
    protected function gate()
    {
        Gate::define('viewNova', function ($request) {
            return Auth::user()->hasRole('founder') || Auth::user()->hasRole('Maintainer') || Auth::user()->hasRole('linshi');

            // function($user)  可以指定邮箱
            //return in_array($user->email, [
            //     'liujialun@lf-network.com'
            // ]);
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
//            (new UserCounts)->width('1/5'),
            (new NewUsers())->width('1/3'),
            (new LineChart())
                ->title('用户增长趋势')
                ->animations([
                    'enabled' => true,
                    'easing' => 'easeinout',
                ])
                ->series(array([
                    'barPercentage' => 0.5,
                    'label' => getThisWeekStartAndEnd(),
                    'borderColor' => 'green',
                    'data' => getPerDay('this', 'users'),
                ],[
                    'barPercentage' => 0.5,
                    'label' => getLastWeekStartAndEnd(),
                    'borderColor' => '#ff6f69',
                    'data' => getPerDay('last', 'users'),
                ]))
                ->options([
                    'xaxis' => [
                        'categories' => getWeekDays('this')
                    ],
                ])
                ->width('2/3'),
//            (new TopicCounts())->width('1/5'),
            (new NewTopics())->width('1/3'),
            (new LineChart())
                ->title('话题增长趋势')
                ->animations([
                    'enabled' => true,
                    'easing' => 'easeinout',
                ])
                ->series(array([
                    'barPercentage' => 0.5,
                    'label' => getThisWeekStartAndEnd(),
                    'borderColor' => 'lightgreen',
                    'data' => getPerDay('this', 'topics'),
                ],[
                    'barPercentage' => 0.5,
                    'label' => getLastWeekStartAndEnd(),
                    'borderColor' => 'orange',
                    'data' => getPerDay('last', 'topics'),
                ]))
                ->options([
                    'xaxis' => [
                        'categories' => getWeekDays('this')
                    ],
                ])
                ->width('2/3'),
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            (\Vyuldashev\NovaPermission\NovaPermissionTool::make())
                ->canSee(function($request) {
                    return $request->user()->can('manage_users');
                }),
            (\OptimistDigital\NovaSettings\NovaSettings::make())
                ->canSee(function ($request) {
                    return $request->user()->can('edit_settings');
                }),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
