<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Matcher\Models\GroupType as ModelsGroupType;

class GroupType extends Resource
{
    public static $group = 'Matcher';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ModelsGroupType::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title_de';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title_de',
        'title_en',
    ];

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

            Stack::make('Titles', [
                Line::make('title_de', fn () => 'De: ' . $this->title_de),
                Line::make('title_en', fn () => 'En: ' . $this->title_en),
            ])->onlyOnIndex(),

            Text::make('Title_de')->hideFromIndex(),
            Text::make('Title_en')->hideFromIndex(),

            Textarea::make('Description_de')->hideFromIndex(),
            Textarea::make('Description_en')->hideFromIndex(),

            Text::make('Reference_de')->hideFromIndex(),
            Text::make('Reference_en')->hideFromIndex(),

            BelongsTo::make('GroupType')->nullable(),

            HasMany::make('GroupTypes'),
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
