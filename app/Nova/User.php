<?php

namespace App\Nova;

use App\Handlers\ImageUploadHandler;
use App\Nova\Filters\checkRole;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Vyuldashev\NovaPermission\PermissionBooleanGroup;
use Vyuldashev\NovaPermission\RoleBooleanGroup;
use Vyuldashev\NovaPermission\RoleSelect;
use Vyuldashev\NovaPermission\Permission;
use Laravel\Nova\Fields\DateTime;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\User';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = '角色及权限';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];

    public static function label()
    {
        return '用户';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Avatar::make('头像', 'avatar')->disk('oss'),
                
            Text::make('用户名', 'name')
                ->rules('required', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}')
                ->sortable(),

            Text::make('邮箱', 'email')
                ->rules('required', 'email', 'max:254')
                ->rules(function($request) {return $request->user()->hasRole('founder');})
                ->updateRules(function($request) {return $request->user()->hasRole('founder');})
                // ->creationRules('unique:users,email')
                // ->updateRules('unique:users,email,{{resourceId}}')
                ->sortable(),

            //注册时间
            DateTime::make('注册时间','created_at')
            //->canSee(function ($request) {return $request->user()->hasRole('founder');})
                ->sortable(),
            //注册时间

                

            Password::make('密码', 'password')
                ->onlyOnForms()
                //->canSee(function ($request) {return $request->user()->hasRole('founder');})
                ->creationRules('required', 'string', 'min:6')
                ->updateRules('nullable', 'string', 'min:6'),

            // MorphToMany::make('角色', 'roles', \Vyuldashev\NovaPermission\Role::class)
            //     //->canSee(function ($request) {return $request->user()->can('manage_users');})
            //     //->canSee(function ($request) {return $request->user()->hasRole('founder');})
            //     ->creationRules(function($request) {return $request->user()->hasRole('founder');})
            //     ->updateRules(function($request) {return $request->user()->hasRole('founder');}),

            MorphToMany::make('权限', 'permissions', \Vyuldashev\NovaPermission\Permission::class)
                //->canSee(function ($request) {return $request->user()->can('manage_users');})
                ->canSee(function ($request) {return $request->user()->hasRole('founder');}),

            RoleSelect::make('角色', 'roles')
                //->canSee(function ($request) {return $request->user()->can('manage_users');})
                ->canSee(function ($request) {return $request->user()->hasRole('founder');})
                ->creationRules(function($request) {return $request->user()->hasRole('founder');})
                ->updateRules(function($request) {return $request->user()->hasRole('founder');}),

            Text::make('操作', function () {
                $route = route('users.show', $this->id);
                return <<<HTML
<a class="btn btn-default btn-primary" href="{$route}" target="_blank">用户详情</a>
HTML;
            })->asHtml(),

            HasMany::make('话题', 'topics', 'App\Nova\Topics'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new checkRole()
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
