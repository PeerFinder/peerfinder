<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\Activitylog\Models\Activity as ModelsActivity;

class Activity extends Resource
{
    public static $group = 'Analytics';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ModelsActivity::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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
            
            Text::make('Subject ID')->readonly(),

            Text::make('Subject Type')->readonly(),

            Text::make('Properties', function () {
                $ret = [];

                $props = $this->properties;

                if ($props->has('old')) {
                    $old_props = $props['old'];
                } else {
                    $old_props = $props['attributes'];
                }

                $ret[] = '<table class="w-full">';

                foreach ($old_props as $key => $prop) {
                    if ($old_props[$key] != $props['attributes'][$key]) {
                        $style = "background: yellow;";
                    } else {
                        $style = "";
                    }

                    $ret[] = sprintf('<tr class="border" style="%s"><td>%s</td><td style="word-break: break-all;">%s</td><td style="word-break: break-all;">%s</td></tr>', $style, $key, $old_props[$key], $props['attributes'][$key]);
                }

                $ret[] = '</table>';

                return join($ret);
            })->onlyOnDetail()->asHtml(),

            Text::make('Description')->readonly(),

            BelongsTo::make('User', 'Causer')->readonly(),

            DateTime::make('Created At')->readonly(),
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
