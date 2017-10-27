<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['authOne']], function () {


    /*
    |--------------------------------------------------------------------------
    | Performance Routes
    |--------------------------------------------------------------------------
    |
    | This route group applies the «Performance» group to every route
    | it contains.
    |
    */
    Route::get('PerformanceController/getDataPerformanceAndSave', 'PerformanceController@getDataPerformanceAndSave');

    /* ---- END Performance Controller ---- */


    /*
    |--------------------------------------------------------------------------
    | Questionnaire Routes
    |--------------------------------------------------------------------------
    |
    | This route group applies the «Questionnaire» group to every route
    | it contains.
    |
    */
    Route::get('q/{form_key}/statistics', 'QuestionnairesController@statistics');



    /*
    |--------------------------------------------------------------------------
    | votes Routes
    |--------------------------------------------------------------------------
    |
    | This route group applies the «Votes» group to every route
    | it contains.
    |
    */


    Route::get('voteEvent/{event_key}/countByGender/{cb_key}', 'VotesController@countVotesByGenderChart');
    Route::get('voteEvent/{event_key}/firstByGender/{cb_key}', 'VotesController@firstVotesByGenderChart');
    Route::get('voteEvent/{event_key}/secondByGender/{cb_key}', 'VotesController@secondVotesByGenderChart');
    Route::get('voteEvent/{event_key}/votesByProfession/{cb_key}', 'VotesController@verifyVotesByProfessionChart');
    Route::get('voteEvent/{event_key}/countByProfession/{cb_key}', 'VotesController@countVotesByProfessionChart');
    Route::get('voteEvent/{event_key}/firstByProfession/{cb_key}', 'VotesController@firstVotesByProfessionChart');
    Route::get('voteEvent/{event_key}/secondByProfession/{cb_key}', 'VotesController@secondVotesByProfessionChart');

//TODO:DELETE
    Route::get('voteEvent/{event_key}/votesByChannel/{cb_key}', 'VotesController@verifyVotesByChannelChart');
    Route::get('voteEvent/{event_key}/countByChannel/{cb_key}', 'VotesController@countVotesByChannelChart');
    Route::get('voteEvent/{event_key}/firstByChannel/{cb_key}', 'VotesController@firstVotesByChannelChart');
    Route::get('voteEvent/{event_key}/secondByChannel/{cb_key}', 'VotesController@secondVotesByChannelChart');
//endTODO


    Route::get('voteEvent/{event_key}/votesByChannel', 'VotesController@votesByChannel');



    Route::get('voteEvent/{event_key}/votesByNb/{cb_key}', 'VotesController@verifyVotesByNbChart');
    Route::get('voteEvent/{event_key}/countByNb/{cb_key}', 'VotesController@countVotesByNbChart');
    Route::get('voteEvent/{event_key}/firstByNb/{cb_key}', 'VotesController@firstVotesByNbChart');
    Route::get('voteEvent/{event_key}/secondByNb/{cb_key}', 'VotesController@secondVotesByNbChart');
    Route::get('voteEvent/{event_key}/countByAge/{cb_key}', 'VotesController@countVotesByAgeChart');
    Route::get('voteEvent/{event_key}/firstByAge/{cb_key}', 'VotesController@firstVotesByAgeChart');
    Route::get('voteEvent/{event_key}/secondByAge/{cb_key}', 'VotesController@secondVotesByAgeChart');
    Route::get('voteEvent/{event_key}/votersByChannel', 'VotesController@votersByChannel');

    Route::post('voteEvent/{event_key}/votes', 'VotesController@infoVotes');
    Route::post('voteEvent/{event_key}/votesSummary', 'VotesController@votesSummary');
    Route::get('voteEvent/{event_key}/empavilleSchools/{cb_key}', 'VotesController@empavilleSchools');

    Route::get('voteEventDaily/{event_key}/votes/{cb_key}', 'VotesController@dailyInformation');

    /*
    |--------------------------------------------------------------------------
    | Votes statistics Routes
    |--------------------------------------------------------------------------
    |
    | This route group applies the «Votes statistics» group to every route
    | it contains.
    |
    */
    Route::get('voteEvent/{event_key}/statisticsTopicParameters', 'VotesStatisticsController@voteStatisticsTopicParameters');

    Route::get('voteEvent/{event_key}/statisticsVotersPerDate', 'VotesStatisticsController@voteStatisticsVotersPerDate');
    Route::get('voteEvent/{event_key}/statisticsByDate', 'VotesStatisticsController@voteStatisticsByDate');
    Route::get('voteEvent/{event_key}/statisticsByTown', 'VotesStatisticsController@voteStatisticsByTown');
    Route::get('voteEvent/{event_key}/statisticsByAge', 'VotesStatisticsController@voteStatisticsByAge');
    Route::get('voteEvent/{event_key}/statisticsByGender', 'VotesStatisticsController@voteStatisticsByGender');
    Route::get('voteEvent/{event_key}/statisticsByProfession', 'VotesStatisticsController@voteStatisticsByProfession');
    Route::get('voteEvent/{event_key}/statisticsByEducation', 'VotesStatisticsController@voteStatisticsByEducation');


    Route::post('voteEvent/{event_key}/statisticsByTop', 'VotesStatisticsController@voteStatisticsByTop');
    Route::post('voteEvent/{event_key}/statisticsTopByDate', 'VotesStatisticsController@voteStatisticsTopByDate');
    Route::post('voteEvent/{event_key}/statisticsByParameter', 'VotesStatisticsController@voteStatisticsByParameter');

    Route::post('voteEvent/{event_key}/statisticsLastDay', 'VotesStatisticsController@voteStatisticsLastDay');


    Route::post('voteStatistics/{event_key}/topTopics', 'VotesStatisticsController@topTopics');



});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});