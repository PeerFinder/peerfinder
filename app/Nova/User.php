<?php

namespace App\Nova;

use App\Helpers\Facades\Avatar;
use App\Models\User as UserModel;
use App\Nova\Actions\ShowUserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email', 'company', 'slogan'
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
            ID::make()->sortable(),

            Stack::make('Details', [
                Line::make('Name')
                    ->sortable()
                    ->rules('required', 'max:255')->asHeading(),

                Line::make('Username')->asSmall(),
            ]),


            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Text::make('Slogan')
                ->hideFromIndex()
                ->rules(UserModel::getValidationRules()['create']['slogan']),

            Text::make('Homepage')
                ->hideFromIndex()
                ->rules(UserModel::getValidationRules()['create']['homepage']),
            
            Textarea::make('About')
                ->hideFromIndex()
                ->rules(UserModel::getValidationRules()['create']['about']),

            Text::make('Company')
                ->hideFromIndex()
                ->rules(UserModel::getValidationRules()['create']['company']),           
            
            Text::make('Facebook Profile')
                ->hideFromIndex()
                ->rules(UserModel::getValidationRules()['create']['facebook_profile']),  

            Text::make('Twitter Profile')
                ->hideFromIndex()
                ->rules(UserModel::getValidationRules()['create']['twitter_profile']),       
                
            Text::make('LinkedIn Profile')
                ->hideFromIndex()
                ->rules(UserModel::getValidationRules()['create']['linkedin_profile']),

            Text::make('Xing Profile')
                ->hideFromIndex()
                ->rules(UserModel::getValidationRules()['create']['xing_profile']),

            Text::make('Xing Profile')
                ->hideFromIndex()
                ->rules(UserModel::getValidationRules()['create']['xing_profile']),

/*             Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'), */
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
        return [
            (new ShowUserProfile())->showOnTableRow()->withoutConfirmation(),
        ];
    }
}
