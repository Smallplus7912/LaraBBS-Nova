<?php

namespace App\Nova;

use App\Handlers\ImageUploadHandler;
use App\Nova\Filters\checkRole;
use App\Nova\Filters\UserCreated;
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

            //Avatar::make('头像', 'avatar')->disk('minio')->path('/wuguofeng/avatar'),

            Text::make('用户名',  function () {
                return <<<HTML
            <a href="/nova/resources/users/{$this->id}" class="no-underline dim text-primary font-bold">
<!--                <img src="{$this->full_avatar}" alt="" width="30" style="border-radius: 50%; vertical-align: middle">-->
                {$this->name}
            </a>
HTML;
            })->asHtml()
                ->rules('required', 'max:255')
                ->sortable(),

            Text::make('邮箱', 'email')
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}')
                ->sortable(),

            Password::make('密码', 'password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

            MorphToMany::make('角色', 'roles', \Vyuldashev\NovaPermission\Role::class)->canSee(function ($request) {
                return $request->user()->can('manage_users');
            }),
            MorphToMany::make('权限', 'permissions', \Vyuldashev\NovaPermission\Permission::class)->canSee(function ($request) {
                return $request->user()->can('manage_users');
            }),
            RoleSelect::make('角色', 'roles')->canSee(function ($request) {
                return $request->user()->can('manage_users');
            }),

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
