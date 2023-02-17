<?php

namespace App\Nova;

use App\Nova\Topics;
use Ek0519\Quilljs\Quilljs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Suenerds\NovaSearchableBelongsToFilter\NovaSearchableBelongsToFilter;

class Replies extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Reply';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'content';

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
        'content',
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'user' => ['name'],
        'topic' => ['title']
    ];

    public static function label()
    {
        return '回复';
    }

    public static $with = ['user', 'topic'];

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

            Text::make('内容', function () {
                $content = strip_tags($this->content);
                if (strlen($content) > 40) {
                    return substr($content, 0, 40). '...';
                }

                return $content;
            })->onlyOnIndex(),

            Text::make('内容', function () {
                return <<<HTML
                {$this->content}
HTML;

            })->asHtml()->onlyOnDetail(),

            Textarea::make('内容', 'content')
                ->alwaysShow()
                ->rules('required', 'min:4')->hideFromDetail(),

            BelongsTo::make('用户', 'user', 'App\Nova\User')->sortable(),

            BelongsTo::make('话题', 'topic', 'App\Nova\Topics')->sortable(),
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
            (new NovaSearchableBelongsToFilter('用户过滤'))
                ->fieldAttribute('user')
                ->filterBy('user_id'),
            (new NovaSearchableBelongsToFilter('话题过滤'))
                ->fieldAttribute('topic')
                ->filterBy('topic_id')
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
