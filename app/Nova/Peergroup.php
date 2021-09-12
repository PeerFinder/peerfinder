<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasManyThrough;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Matcher\Models\Peergroup as ModelsPeergroup;

class Peergroup extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ModelsPeergroup::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function indexQuery(NovaRequest $request, $query) {
        return $query->withCount('members');
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
            ID::make(__('ID'), 'id')->sortable(),

            Stack::make('Details', [
                Line::make('Title')->sortable()->asHeading(),
                Line::make('Groupname', function() {
                    return sprintf('<a href="%s" target="_blank">%s</a>', $this->getUrl(), htmlspecialchars($this->groupname));
                })->asHtml(),
            ]),

            Text::make('Title')->onlyOnForms()->rules(ModelsPeergroup::rules()['update']['title'])->required(),

            Textarea::make('Description')->hideFromIndex()->rules(ModelsPeergroup::rules()['update']['description'])->required(),

            Number::make('Members', 'members_count')->onlyOnIndex(),

            Number::make('Limit')->rules(ModelsPeergroup::rules()['update']['limit'])->required(),

            Date::make('Begin')->rules(ModelsPeergroup::rules()['update']['begin']),

            Boolean::make('Virtual')->hideFromIndex(),

            Boolean::make('Private')->hideFromIndex(),

            Boolean::make('With Approval')->hideFromIndex(),

            BelongsTo::make('User'),

            Boolean::make('Open'),

            Text::make('Location')->hideFromIndex()->rules(ModelsPeergroup::rules()['update']['location']),

            Text::make('Meeting Link')->hideFromIndex()->rules(ModelsPeergroup::rules()['update']['meeting_link']),

            HasManyThrough::make('Memberships')->hideFromIndex(),

            BelongsToMany::make('Languages')->hideFromIndex(),
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
        return [];
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
