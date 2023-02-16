<?php

namespace App\Nova;

use App\Models\Category;
use App\Models\Reply;
use App\Models\Topic;
use App\Nova\Filters\isReplies;
use Ek0519\Quilljs\Quilljs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Suenerds\NovaSearchableBelongsToFilter\NovaSearchableBelongsToFilter;

class Topics extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Topic';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = '内容管理';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title'
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'user' => ['name'],
        'category' => ['name']
    ];

    public static function label()
    {
        return '话题';
    }

    public static $with = ['user', 'category'];

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

            Text::make('标题', function (){
                return <<<HTML
            <div  class="no-underline dim text-primary font-bold">
<!--                <img src="{$this->user->full_avatar}" alt="" width="30" style="border-radius: 50%; vertical-align: middle">-->
                {$this->title}
            </div>
HTML;
            })
                ->showOnCreating()
                ->showOnUpdating()
                ->rules('required', 'min:4', 'max:255')->sortable()->asHtml(),

            Text::make('作者', function () {
                return <<<HTML
            <a href="/nova/resources/users/{$this->user->id}" class="no-underline dim text-primary font-bold">
                <img src="{$this->user->full_avatar}" alt="" width="30" style="border-radius: 50%; vertical-align: middle">
                {$this->user->name}
            </a>
HTML;
            })->asHtml(),

            BelongsTo::make('分类', 'category', 'App\Nova\Categories')->searchable(),

            Number::make('评论', function () {
                return Reply::query()->where('topic_id', $this->id)->count();
            })->sortable()
                ->hideWhenUpdating()
                ->hideWhenCreating()
                ->hideFromDetail()
                ->required(true),

            HasMany::make('评论', 'replies', 'App\Nova\Replies'),
            Text::make('操作', function () {
                $route = route('topics.show', $this->id);
                return <<<HTML
            <a class="btn btn-default btn-primary" href="{$route}" target="_blank">论坛详情</a>
HTML;
            })->asHtml()->hideFromDetail(),

            BelongsTo::make('作者', 'user', 'App\Nova\User')->hideFromDetail()->hideFromIndex(),

            Quilljs::make('内容')
                ->withFiles('minio', '/wuguofeng/topic')
                ->placeholder('please enter here')
                ->height(300)
                ->alwaysShow()
                ->rules('required'),
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
            new Filters\CategoriesFilter(),
            new isReplies(),
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
