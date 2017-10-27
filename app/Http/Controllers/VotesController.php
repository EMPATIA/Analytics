<?php

namespace App\Http\Controllers;

use App\Generic\EventInfo;
use App\Generic\UsersInfo;
use App\One\One;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cache;


/**
 * @SWG\Tag(
 *   name="Votes Statistics",
 *   description="Everything about Vote Statistics",
 * )
 *
 *
 *
 *  @SWG\Definition(
 *   definition="votesInfoReply",
 *   type="object",
 *   allOf={
 *     @SWG\Schema(
 *           @SWG\Property(
 *                 property="data",
 *                 type="array",
 *                 @SWG\Items(
 *                      @SWG\Property(property="balance", format="integer", type="integer"),
 *                      @SWG\Property(property="title", format="string", type="string"),
 *                      @SWG\Property(property="budget", format="integer", type="integer"),
 *                      @SWG\Property(property="category", format="string", type="string"),
 *                      @SWG\Property(property="geo_area", format="string", type="string"),
 *                      @SWG\Property(property="positives", format="integer", type="integer"),
 *                      @SWG\Property(property="negatives", format="integer", type="integer"),
 *                      @SWG\Property(property="winner", format="boolean", type="boolean")
 *                  )
 *           ),
 *          @SWG\Property(
 *                 property="summary",
 *                 type="object",
 *                 allOf={
 *                      @SWG\Schema(
 *                          @SWG\Property(property="total", format="integer", type="integer"),
 *                          @SWG\Property(property="total_positives", format="integer", type="integer"),
 *                          @SWG\Property(property="total_negatives", format="integer", type="integer"),
 *                          @SWG\Property(property="total_users_voted", format="integer", type="integer"),
 *                          @SWG\Property(property="start_date", format="date", type="string"),
 *                          @SWG\Property(property="end_date", format="date", type="string"),
 *                          @SWG\Property(property="total_balance", format="integer", type="integer")
 *                      )
 *                 }
 *           ),
 *     )
 *   }
 * )
 *
 *
 *
 *
 *  @SWG\Definition(
 *   definition="votesDailyReply",
 *   type="object",
 *   allOf={
 *     @SWG\Schema(
 *           @SWG\Property(
 *                 property="data",
 *                 type="object",
 *                 allOf={
 *                    @SWG\Schema(
 *                          @SWG\Property(
 *                              property="date_value",
 *                              type="object",
 *                              allOf={
 *                                  @SWG\Schema(
 *                                      @SWG\Property(property="positives", format="integer", type="integer"),
 *                                      @SWG\Property(property="negatives", format="integer", type="integer")
 *                                  )
 *                              }
 *                          )
 *                  )
 *                }
 *           )
 *     )
 *   }
 * )
 *
 *  @SWG\Definition(
 *   definition="votesCountByParamReply",
 *   type="object",
 *   allOf={
 *     @SWG\Schema(
 *           @SWG\Property(
 *                 property="data",
 *                 type="object",
 *                 allOf={
 *                    @SWG\Schema(
 *                          @SWG\Property(
 *                              property="parameter_value",
 *                              type="object",
 *                              allOf={
 *                                  @SWG\Schema(
 *                                      @SWG\Property(property="positives", format="integer", type="integer"),
 *                                      @SWG\Property(property="negatives", format="integer", type="integer"),
 *                                      @SWG\Property(property="total", format="integer", type="integer")
 *                                  )
 *                              }
 *                          )
 *                  )
 *                }
 *           )
 *     )
 *   }
 * )
 *
 *
 *
 *  @SWG\Definition(
 *   definition="votesByProfessionReply",
 *   type="object",
 *   allOf={
 *     @SWG\Schema(
 *           @SWG\Property(
 *                 property="data",
 *                 type="array",
 *                 @SWG\Items(
 *                          @SWG\Property(property="title", format="string", type="string"),
 *                          @SWG\Property(property="budget", format="integer", type="integer"),
 *                          @SWG\Property(property="category", format="string", type="string"),
 *                          @SWG\Property(property="geo_area", format="string", type="string"),
 *                          @SWG\Property(
 *                              property="professions",
 *                              type="object",
 *                              allOf={
 *                                  @SWG\Schema(
 *                                      @SWG\Property(property="balance", format="integer", type="integer"),
 *                                      @SWG\Property(property="positives", format="integer", type="integer"),
 *                                      @SWG\Property(property="negatives", format="integer", type="integer"),
 *                                  )
 *                              }
 *                          )
 *                )
 *           ),
 *          @SWG\Property(
 *                 property="professions",
 *                 type="array",
 *                 @SWG\Items(type="string")
 *           )
 *
 *     )
 *   }
 * )
 *
 *
 *
 *
 *  @SWG\Definition(
 *   definition="votesFirstByParamReply",
 *   type="object",
 *   allOf={
 *     @SWG\Schema(
 *           @SWG\Property(
 *                 property="data",
 *                 type="object",
 *                 allOf={
 *                    @SWG\Schema(
 *                          @SWG\Property(
 *                              property="parameter_type",
 *                              type="object",
 *                              allOf={
 *                                  @SWG\Schema(
 *                                      @SWG\Property(property="positives", format="integer", type="integer"),
 *                                      @SWG\Property(property="negatives", format="integer", type="integer"),
 *                                  )
 *                              }
 *                          )
 *                  )
 *                }
 *           )
 *     )
 *   }
 * )
 *
 *
 *
 *  @SWG\Definition(
 *   definition="votesByChannelReply",
 *   type="object",
 *   allOf={
 *     @SWG\Schema(
 *           @SWG\Property(
 *                 property="data",
 *                 type="array",
 *                 @SWG\Items(
 *                          @SWG\Property(property="title", format="string", type="string"),
 *                          @SWG\Property(property="budget", format="integer", type="integer"),
 *                          @SWG\Property(property="category", format="string", type="string"),
 *                          @SWG\Property(property="geo_area", format="string", type="string"),
 *                          @SWG\Property(
 *                              property="channels",
 *                              type="object",
 *                              allOf={
 *                                  @SWG\Schema(
 *                                      @SWG\Property(property="balance", format="integer", type="integer"),
 *                                      @SWG\Property(property="positives", format="integer", type="integer"),
 *                                      @SWG\Property(property="negatives", format="integer", type="integer"),
 *                                  )
 *                              }
 *                          )
 *
 *                )
 *           )
 *     )
 *   }
 * )
 *
 *
 *
 *
 *  @SWG\Definition(
 *   definition="votesByNbReply",
 *   type="object",
 *   allOf={
 *     @SWG\Schema(
 *           @SWG\Property(
 *                 property="data",
 *                 type="array",
 *                 @SWG\Items(
 *                          @SWG\Property(property="title", format="string", type="string"),
 *                          @SWG\Property(property="budget", format="integer", type="integer"),
 *                          @SWG\Property(property="category", format="string", type="string"),
 *                          @SWG\Property(property="geo_area", format="string", type="string"),
 *                          @SWG\Property(
 *                              property="neighbourhood_area",
 *                              type="object",
 *                              allOf={
 *                                  @SWG\Schema(
 *                                      @SWG\Property(property="balance", format="integer", type="integer"),
 *                                      @SWG\Property(property="positives", format="integer", type="integer"),
 *                                      @SWG\Property(property="negatives", format="integer", type="integer"),
 *                                  )
 *                              }
 *                          )
 *                )
 *           )
 *     )
 *   }
 * )
 *
 *
 *
 *
 *
 *
 *
 *
 */


class VotesController extends Controller
{

    private $totalBugdet = 150;

    /**
     * VotesController constructor.
     */
    public function __construct(Request $request)
    {

    }


    /** Count votes by source. Ex: Kiosk, Mobile, etc.
     * @param $votes
     * @return array
     */
    private function countVotesBySource($votes){
        $data = [];
        foreach ($votes as $vote) {

            if ($vote->value > 0 && empty($data[$vote->source]["positives"])) {
                $data[$vote->source]["positives"] = 1;
            } else if ($vote->value > 0) {
                $data[$vote->source]["positives"]++;
            }
            if ($vote->value <0 && empty($data[$vote->source]["negatives"])) {
                $data[$vote->source]["negatives"] = 1;
            } else if ($vote->value < 0) {
                $data[$vote->source]["negatives"]++;
            }
        }

        return $data;
    }

    /** Count voters by source. Ex: Kiosk, Mobile, etc.
     * @param $votes
     * @return array
     */
    private function countVotersBySource($votes){
        $data = [];
        foreach ($votes as $vote) {
            if (empty($data[$vote->source])) {
                $data[$vote->source] = 1;
            } else if ($vote->value > 0) {
                $data[$vote->source]++;
            }
        }

        return $data;
    }


    /** Get First Vote by Source
     * @param $votes
     * @return array
     */
    private function firstVotesBySource($votes)
    {
        $data = [];
        $usersVoted= [];
        foreach ($votes as $vote) {
            if(!isset($usersVoted[$vote->user_key])){
                $usersVoted[$vote->user_key] = 1;
                if ($vote->value > 0) {
                    if(empty($data[$vote->source]["positives"])){
                        $data[$vote->source]["positives"] =  1;
                    }else{
                        $data[$vote->source]["positives"] = $data[$vote->source]["positives"] +  1;
                    }
                }
                if ($vote->value < 0) {
                    if( empty($data[$vote->source]["negatives"])){
                        $data[$vote->source]["negatives"] = 1;
                    }else{
                        $data[$vote->source]["negatives"] = $data[$vote->source]["negatives"] +  1;
                    }
                }
            }

        }
        return $data;
    }


    /** Get Second votes by source
     * @param $votes
     * @return array
     */
    private function secondVotesBySource($votes)
    {
        $data = [];
        $usersVoted= [];
        foreach ($votes as $vote) {
            $usersVoted[$vote->user_key] = !isset($usersVoted[$vote->user_key]) ? 1 : $usersVoted[$vote->user_key] + 1;
            if($usersVoted[$vote->user_key] == 2){
                if ($vote->value == 1) {
                    if(empty($data[$vote->source]["positives"])){
                        $data[$vote->source]["positives"] =  1;
                    }else{
                        $data[$vote->source]["positives"] = $data[$vote->source]["positives"] +  1;
                    }
                }
                if ($vote->value == -1 ) {
                    if( empty($data[$vote->source]["negatives"])){
                        $data[$vote->source]["negatives"] = 1;
                    }else{
                        $data[$vote->source]["negatives"] = $data[$vote->source]["negatives"] +  1;
                    }
                }
            }
        }
        return $data;

    }





    /**
     * @SWG\Post(
     *  path="/voteEvent/{event_key}/votes",
     *  summary="Show Information about vote",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Parameter(
     *      name="LANG-CODE",
     *      in="header",
     *      description="User Language",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Parameter(
     *      name="LANG-CODE-DEFAULT",
     *      in="header",
     *      description="Entity default Language",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show vote Information",
     *      @SWG\Schema(ref="#/definitions/votesInfoReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */

    /**
     * Request the results of a Vote Event.
     *
     * @param Request $request
     * @param $eventKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function infoVotes(Request $request, $eventKey)
    {
        try {

            $top = $request->json('top') ?? 10;

            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');

            //GET Information on Vote Configuration

            $weight = [
                'positives' => 1,
                'negatives' => 1
            ];

            // Get data from Event
            $eventInfo = new EventInfo($eventKey,$languageCode,$languageCodeDefault);
            $event = $eventInfo->getEvent();

            $startDate = $event->start_date;
            $endDate = $event->end_date;

            //Get data from all Votes
            $allVotes = $eventInfo->getEventVotes();

            /* ------- Get array with topics details and a collection of votes ------- */
            $topicVotesVerification = $eventInfo->verifyValidTopicsAndVotes($allVotes->votes);
            $votes = $topicVotesVerification['votes_filtered'];
            $topics = $topicVotesVerification['topic_details'];

            $votesPositives = $allVotes->positives;
            $votesNegatives = $allVotes->negatives;
            $data = [];
            $countTotalVotes = 0;
            $countTotalPositives = 0;
            $countTotalNegative = 0;
            $countUsersVoted = collect($allVotes->users)->count();
            $countBalance = 0;
            foreach ($topics as $topic) {
                $dataTemp['topic_number'] = $topic->topic_number;
                $dataTemp['balance'] = 0;
                $dataTemp['title'] = $topic->title;
                $dataTemp['budget'] = 0;

                $dataTemp['category'] = "";
                $dataTemp['geo_area'] = "";

                if (isset($topic->parameters)) {
                    foreach ($topic->parameters as $value) {
                        $options = collect($value->options)->keyBy('id');
                        if ($value->code === 'budget') {
                            if ($options->has($value->pivot->value)) {
                                $dataTemp['budget'] = (int)$options->get($value->pivot->value)->label;
                            }
                        }
                        if ($value->code === 'category') {
                            if ($options->has($value->pivot->value)) {
                                $dataTemp['category'] = $options->get($value->pivot->value)->label;
                            }
                        }
                        if ($value->code === 'image_map') {
                            $dataTemp['geo_area'] = ONE::verifyEmpavilleGeoArea($value->pivot->value);
                        }
                    }
                }
                if (!empty($votesPositives->{$topic->topic_key})) {
                    $countTotalVotes = $countTotalVotes + $votesPositives->{$topic->topic_key};
                    $countTotalPositives = $countTotalPositives + $votesPositives->{$topic->topic_key};
                    $dataTemp['positives'] = $votesPositives->{$topic->topic_key};
                } else {
                    $dataTemp['positives'] = 0;
                }
                if (!empty($votesNegatives->{$topic->topic_key})) {
                    $countTotalVotes = $countTotalVotes + $votesNegatives->{$topic->topic_key};
                    $countTotalNegative = $countTotalNegative + $votesNegatives->{$topic->topic_key};
                    $dataTemp['negatives'] = $votesNegatives->{$topic->topic_key};
                } else {
                    $dataTemp['negatives'] = 0;
                }
                $dataTemp['balance'] = ($dataTemp['positives'] * $weight['positives']) - ($dataTemp['negatives'] * $weight['negatives']);
                $countBalance = $countBalance + $dataTemp['balance'];

                $data[] = $dataTemp;
            }
            usort($data, function ($a, $b) {
                return $b['balance'] - $a['balance'];
            });

            // verify if in budget
            $total = 0;
            foreach (!empty($data) ? $data : [] as $key => $proposal) {
                if (($total + $proposal['budget']) <= $this->totalBugdet) {
                    $total = $total + $proposal['budget'];
                    $data[$key]['winner'] = true;
                } else {
                    $data[$key]['winner'] = false;
                }
            }

            $data = collect($data)->take($top)->toArray();

            $summary = [
                'total' => $countTotalVotes,
                'total_positives' => $countTotalPositives,
                'total_negatives' => $countTotalNegative,
                'total_users_voted' => $countUsersVoted,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_balance' => $countBalance
            ];

            return response()->json(["data" => $data, 'summary' => $summary], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }

    public function votesSummary(Request $request, $eventKey)
    {
        try {
            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');

            //GET Information on Vote Configuration

            $weight = [
                'positives' => 1,
                'negatives' => 1
            ];

            // Get data from Event
            $eventInfo = new EventInfo($eventKey,$languageCode,$languageCodeDefault);
            $event = $eventInfo->getEvent();

            $startDate = $event->start_date;
            $endDate = $event->end_date;

            //Get data from all Votes
            $allVotes = $eventInfo->getEventVotes();
            $votes = $allVotes->votes;

            if (empty($votes))
                return response()->json(["data" => [], 'summary' => []], 200);

            /* ------- Get array with topics details and a collection of votes ------- */
            $topicVotesVerification = $eventInfo->verifyValidTopicsAndVotes($allVotes->votes);
            $votes = $topicVotesVerification['votes_filtered'];
            $topics = $topicVotesVerification['topic_details'];

            $votesPositives = $allVotes->positives;
            $votesNegatives = $allVotes->negatives;
            $data = [];
            $countTotalVotes = 0;
            $countTotalPositives = 0;
            $countTotalNegative = 0;
            $countUsersVoted = collect($allVotes->users)->count();
            $countBalance = 0;

            foreach (!empty($topics) ? $topics : [] as $topic) {

                $dataTemp['balance'] = 0;
                $dataTemp['title'] = $topic->title;
                $dataTemp['budget'] = 0;
                $dataTemp['category'] = "";
                $dataTemp['geo_area'] = "";

                foreach (!empty($topic->parameters) ? $topic->parameters : [] as $value) {
                    $options = collect($value->options)->keyBy('id');
                    if ($value->code === 'budget') {
                        if($options->has($value->pivot->value)){
                            $dataTemp['budget'] = (int)$options->get($value->pivot->value)->label;
                        }
                    }
                    if ($value->code === 'category') {
                        if($options->has($value->pivot->value)){
                            $dataTemp['category'] = $options->get($value->pivot->value)->label;
                        }
                    }
                    if ($value->code === 'image_map') {
                        $dataTemp['geo_area'] = ONE::verifyEmpavilleGeoArea($value->pivot->value);
                    }
                }
                if (!empty($votesPositives->{$topic->topic_key})) {
                    $countTotalVotes = $countTotalVotes + $votesPositives->{$topic->topic_key};
                    $countTotalPositives = $countTotalPositives + $votesPositives->{$topic->topic_key};
                    $dataTemp['positives'] = $votesPositives->{$topic->topic_key};
                } else {
                    $dataTemp['positives'] = 0;
                }
                if (!empty($votesNegatives->{$topic->topic_key})) {
                    $countTotalVotes = $countTotalVotes + $votesNegatives->{$topic->topic_key};
                    $countTotalNegative = $countTotalNegative + $votesNegatives->{$topic->topic_key};
                    $dataTemp['negatives'] = $votesNegatives->{$topic->topic_key};
                } else {
                    $dataTemp['negatives'] = 0;
                }
                $dataTemp['balance'] = ($dataTemp['positives'] * $weight['positives']) - ($dataTemp['negatives'] * $weight['negatives']);
                $countBalance = $countBalance + $dataTemp['balance'];

                $data[] = $dataTemp;
            }
            usort($data, function ($a, $b) {
                return $b['balance'] - $a['balance'];
            });

            // verify if in budget
            $total = 0;
            foreach (!empty($data) ? $data : [] as $key => $proposal) {
                if (($total + $proposal['budget']) <= $this->totalBugdet) {
                    $total = $total + $proposal['budget'];
                    $data[$key]['winner'] = true;
                } else {
                    $data[$key]['winner'] = false;
                }
            }

            $data = collect($data)->toArray();

            $summary = [
                'total' => $countTotalVotes,
                'total_positives' => $countTotalPositives,
                'total_negatives' => $countTotalNegative,
                'total_users_voted' => $countUsersVoted,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_balance' => $countBalance
            ];

            return response()->json(["data" => $data, 'summary' => $summary], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }


    /**
     * @SWG\Get(
     *  path="/voteEventDaily/{event_key}/votes/{cb_key}",
     *  summary="Show Information about daily votes",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show vote daily Information",
     *      @SWG\Schema(ref="#/definitions/votesDailyReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */

    /**
     * Request the results of a Vote Event.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dailyInformation(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $votesInfo = json_decode($response->content(), true);

            $votes = $votesInfo['data']['votes'];

            $dailyArray = array();
            foreach ($votes as $vote) {
                $date = Carbon::parse($vote['created_at'])->format('Y-m-d');
                if (!isset($dailyArray[$date])) {
                    $dailyArray[$date] = array("positives" => 0, "negatives" => 0);
                }
                $vote["value"] > 0 ? $dailyArray[$date]["positives"]++ : $dailyArray[$date]["negatives"]++;
            }

            return response()->json(["data" => $dailyArray], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }



    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/countByGender/{cb_key}",
     *  summary="Show votes count by gender statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show vote count by gender statistics",
     *      @SWG\Schema(ref="#/definitions/votesCountByParamReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */

    /**
     * Gender
     */

    /**
     * Request the results of a Vote Event.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countVotesByGenderChart(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $dataTmp = [];
            $array = [];
            foreach ($response->json()->data->votes as $vote) {
                $array[] = $vote->user_key;
                if ($vote->value == 1 && empty($dataTmp[$vote->user_key]["positives"])) {
                    $dataTmp[$vote->user_key]["positives"] = 1;
                } else if ($vote->value == 1) {
                    $dataTmp[$vote->user_key]["positives"]++;
                }
                if ($vote->value == -1 && empty($dataTmp[$vote->user_key]["negatives"])) {
                    $dataTmp[$vote->user_key]["negatives"] = 1;
                } else if ($vote->value == -1) {
                    $dataTmp[$vote->user_key]["negatives"]++;
                }
            }
            $arrayUsers = array_unique($array);

            $response = One::post([
                    'component' => 'auth',
                    'api' => 'auth',
                    'method' => 'listUser',
                    'params' => ["userList" => $arrayUsers],
                    'headers' => ["X-AUTH-TOKEN: " . $request->header("X-AUTH-TOKEN")]
                ]
            );

            $totalMales = 0;
            $totalFemale = 0;
            $totalTransgender = 0;
            foreach ($response->json() as $user) {
                if ($user->gender == 'Male') {
                    $totalMales += 1;
                }
                if ($user->gender == 'Female') {
                    $totalFemale += 1;
                }
                if ($user->gender == 'Transgender') {
                    $totalTransgender += 1;
                }
                $dataTmp[$user->user_key]["gender"] = $user->gender;
            }

            $data["Male"]["Positives"] = 0;
            $data["Male"]["Negatives"] = 0;
            $data["Female"]["Positives"] = 0;
            $data["Female"]["Negatives"] = 0;
            $data["Transgender"]["Positives"] = 0;
            $data["Transgender"]["Negatives"] = 0;
            foreach (!empty($dataTmp) ? $dataTmp : [] as $value) {
                if (!empty($value["gender"])) {

                    if ($value["gender"] == "Male") {
                        if (!empty($value["positives"]))
                            $data["Male"]["Positives"] += $value["positives"];
                        if (!empty($value["negatives"]))
                            $data["Male"]["Negatives"] += $value["negatives"];
                    } else if ($value["gender"] == "Female") {
                        if (!empty($value["positives"]))
                            $data["Female"]["Positives"] += $value["positives"];
                        if (!empty($value["negatives"]))
                            $data["Female"]["Negatives"] += $value["negatives"];
                    }
                    else if ($value["gender"] == "Transgender") {
                        if (!empty($value["positives"]))
                            $data["Transgender"]["Positives"] += $value["positives"];
                        if (!empty($value["negatives"]))
                            $data["Transgender"]["Negatives"] += $value["negatives"];
                    }
                }
            }

            $data["Male"]["Total"] = $totalMales;
            $data["Female"]["Total"] = $totalFemale;
            $data["Transgender"]["Total"] = $totalTransgender;

            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }




    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/firstByGender/{cb_key}",
     *  summary="Show first votes by gender statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show first votes by gender statistics",
     *      @SWG\Schema(ref="#/definitions/votesFirstByParamReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */




    /**
     * Request the results of a Vote Event.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function firstVotesByGenderChart(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $dataTmp = [];
            foreach ($response->json()->data->votes as $vote) {
                $array[] = $vote->user_key;
                if ($vote->value == 1 && empty($dataTmp[$vote->user_key]["positives"]) && empty($dataTmp[$vote->user_key]["negatives"])) {
                    $dataTmp[$vote->user_key]["positives"] = 1;
                }
                if ($vote->value == -1 && empty($dataTmp[$vote->user_key]["negatives"]) && empty($dataTmp[$vote->user_key]["positives"])) {
                    $dataTmp[$vote->user_key]["negatives"] = 1;
                }
            }
            $arrayUsers = array_unique($array);

            $response = One::post([
                    'component' => 'auth',
                    'api' => 'auth',
                    'method' => 'listUser',
                    'params' => ["userList" => $arrayUsers],
                    'headers' => ["X-AUTH-TOKEN: " . $request->header("X-AUTH-TOKEN")]
                ]
            );

            foreach ($response->json() as $user) {
                $dataTmp[$user->user_key]["gender"] = $user->gender;
            }

            $data["Male"]["Positives"] = 0;
            $data["Male"]["Negatives"] = 0;
            $data["Female"]["Positives"] = 0;
            $data["Female"]["Negatives"] = 0;
            $data["Transgender"]["Positives"] = 0;
            $data["Transgender"]["Negatives"] = 0;
            foreach (!empty($dataTmp) ? $dataTmp : [] as $value) {
                if ($value["gender"] == "Male") {
                    if (!empty($value["positives"]))
                        $data["Male"]["Positives"] += $value["positives"];
                    if (!empty($value["negatives"]))
                        $data["Male"]["Negatives"] += $value["negatives"];
                } else if ($value["gender"] == "Female") {
                    if (!empty($value["positives"]))
                        $data["Female"]["Positives"] += $value["positives"];
                    if (!empty($value["negatives"]))
                        $data["Female"]["Negatives"] += $value["negatives"];
                }else if ($value["gender"] == "Transgender") {
                    if (!empty($value["positives"]))
                        $data["Transgender"]["Positives"] += $value["positives"];
                    if (!empty($value["negatives"]))
                        $data["Transgender"]["Negatives"] += $value["negatives"];
                }
            }

            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }


    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/secondByGender/{cb_key}",
     *  summary="Show second votes by gender statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show second votes by gender statistics",
     *      @SWG\Schema(ref="#/definitions/votesFirstByParamReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */

    /**
     * Request the results of a Vote Event.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function secondVotesByGenderChart(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $dataTmp = [];
            foreach ($response->json()->data->votes as $vote) {
                $array[] = $vote->user_key;
                if ($vote->value == 1 && empty($dataTmp[$vote->user_key]["positives"]) && empty($dataTmp[$vote->user_key]["negatives"])) {

                    if (empty($dataTmp[$vote->user_key]["flag"])) {
                        $dataTmp[$vote->user_key]["flag"] = true;
                    } else {
                        $dataTmp[$vote->user_key]["positives"] = 1;
                    }

                }
                if ($vote->value == -1 && empty($dataTmp[$vote->user_key]["negatives"]) && empty($dataTmp[$vote->user_key]["positives"])) {
                    if (empty($dataTmp[$vote->user_key]["flag"])) {
                        $dataTmp[$vote->user_key]["flag"] = true;
                    } else {
                        $dataTmp[$vote->user_key]["negatives"] = 1;
                    }
                }
            }
            $arrayUsers = array_unique($array);

            $response = One::post([
                    'component' => 'auth',
                    'api' => 'auth',
                    'method' => 'listUser',
                    'params' => ["userList" => $arrayUsers],
                    'headers' => ["X-AUTH-TOKEN: " . $request->header("X-AUTH-TOKEN")]
                ]
            );

            foreach ($response->json() as $user) {
                $dataTmp[$user->user_key]["gender"] = $user->gender;
            }

            $data["Male"]["Positives"] = 0;
            $data["Male"]["Negatives"] = 0;
            $data["Female"]["Positives"] = 0;
            $data["Female"]["Negatives"] = 0;
            $data["Transgender"]["Positives"] = 0;
            $data["Transgender"]["Negatives"] = 0;
            foreach (!empty($dataTmp) ? $dataTmp : [] as $value) {
                if ($value["gender"] == "Male") {
                    if (!empty($value["positives"]))
                        $data["Male"]["Positives"] += $value["positives"];
                    if (!empty($value["negatives"]))
                        $data["Male"]["Negatives"] += $value["negatives"];
                } else if ($value["gender"] == "Female") {
                    if (!empty($value["positives"]))
                        $data["Female"]["Positives"] += $value["positives"];
                    if (!empty($value["negatives"]))
                        $data["Female"]["Negatives"] += $value["negatives"];
                }else if ($value["gender"] == "Transgender") {
                    if (!empty($value["positives"]))
                        $data["Transgender"]["Positives"] += $value["positives"];
                    if (!empty($value["negatives"]))
                        $data["Transgender"]["Negatives"] += $value["negatives"];
                }
            }

            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }




    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/votesByProfession/{cb_key}",
     *  summary="Show votes by profession statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="LANG-CODE",
     *      in="header",
     *      description="User Language",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Parameter(
     *      name="LANG-CODE-DEFAULT",
     *      in="header",
     *      description="Entity default Language",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show votes by profession statistics",
     *      @SWG\Schema(ref="#/definitions/votesByProfessionReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */



    /**
     * Profession
     * @param Request $request
     * @param $eventKey
     * @param $cbKey
     * @return \Illuminate\Http\JsonResponse
     */

    public function verifyVotesByProfessionChart(Request $request, $eventKey, $cbKey)
    {
        try {

            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');
            //GET Information on Vote Configuration

            $weight = [
                'positives' => 1,
                'negatives' => 1
            ];

            // Get data from Event
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            //Get data from all Votes
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );


            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => [], 'professions' => []], 200);

            $votes = json_decode($response->content(), true);

            $arrayUsers = [];
            foreach ($votes['data']['users'] as $key => $user) {
                $arrayUsers[] = $key;
            }

            $response = One::post([
                    'component' => 'auth',
                    'api' => 'auth',
                    'method' => 'listUser',
                    'params' => ["userList" => $arrayUsers],
                    'headers' => ["X-AUTH-TOKEN: " . $request->header("X-AUTH-TOKEN")]
                ]
            );


            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            $arrayUsers = [];
            $users = json_decode($response->content(), true);
            foreach ($users as $user) {
                $arrayUsers[$user['user_key']] = $user;
            }

            $response = One::get([
                    'component' => 'cb',
                    'api' => 'cb',
                    'api_attribute' => $cbKey,
                    'method' => 'topicsWithFirstPost'
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }
            $topics = $response->json();

            $response = ONE::get([
                'component' => 'cb',
                'api' => 'cb',
                'method' => 'options',
                'api_attribute' => $cbKey,
                'headers' => [
                    'LANG-CODE: '.$languageCode,
                    'LANG-CODE-DEFAULT: '.$languageCodeDefault
                ]
            ]);

            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            $options = [];
            $parameters = $response->json()->parameters;
            foreach ($parameters as $parameter) {
                if ($parameter->code === 'budget') {
                    foreach ($parameter->options as $option) {
                        $options[$option->id] = $option->label;
                    }
                }
                if ($parameter->code === 'category') {
                    foreach ($parameter->options as $option) {
                        $options[$option->id] = $option->label;
                    }
                }
            }

            $data = [];
            $professions = [];

            foreach ($topics->data as $key => $topic) {
                $dataTemp['title'] = $topic->title;
                $dataTemp['budget'] = 0;
                $dataTemp['category'] = "";
                $dataTemp['geo_area'] = "";
                $dataTemp['professions'] = [];

                foreach ($topic->parameters as $value) {
                    if ($value->code === 'budget') {
                        $dataTemp['budget'] = (int)$options[$value->pivot->value];
                    }
                    if ($value->code === 'category') {
                        $dataTemp['category'] = $options[$value->pivot->value];
                    }
                    if ($value->code === 'image_map') {
                        $dataTemp['geo_area'] = ONE::verifyEmpavilleGeoArea($value->pivot->value);
                    }
                }

                foreach ($votes['data']['votes'] as $vote) {
                    if ($vote['vote_key'] == $topic->topic_key) {
                        if (!in_array($arrayUsers[$vote['user_key']]['job'], $professions)) {
                            $professions[] = $arrayUsers[$vote['user_key']]['job'];
                        }

                        if (empty($dataTemp['professions'][$arrayUsers[$vote['user_key']]['job']]['balance'])) {
                            $dataTemp['professions'][$arrayUsers[$vote['user_key']]['job']]['balance'] = 0;
                        }
                        if ($vote['value'] < 0) {
                            if (!empty($dataTemp['professions'][$arrayUsers[$vote['user_key']]['job']]['negatives'])) {
                                $dataTemp['professions'][$arrayUsers[$vote['user_key']]['job']]['negatives'] += 1;

                            } else {
                                $dataTemp['professions'][$arrayUsers[$vote['user_key']]['job']]['negatives'] = 1;
                            }
                            $dataTemp['professions'][$arrayUsers[$vote['user_key']]['job']]['balance'] += $vote['value'] * $weight['negatives'];
                        } elseif ($vote['value'] > 0) {
                            if (!empty($dataTemp['professions'][$arrayUsers[$vote['user_key']]['job']]['positives'])) {
                                $dataTemp['professions'][$arrayUsers[$vote['user_key']]['job']]['positives'] += 1;
                            } else {
                                $dataTemp['professions'][$arrayUsers[$vote['user_key']]['job']]['positives'] = 1;
                            }
                            $dataTemp['professions'][$arrayUsers[$vote['user_key']]['job']]['balance'] += $vote['value'] * $weight['positives'];
                        }
                    }
                }
                $data[] = $dataTemp;
            }
            return response()->json(["data" => $data, 'professions' => $professions], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }


    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/countByProfession/{cb_key}",
     *  summary="Show votes count by profession statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show vote count by profession statistics",
     *      @SWG\Schema(ref="#/definitions/votesCountByParamReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */


    /**
     * Request the results of a Vote Event.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countVotesByProfessionChart(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $dataTmp = [];
            foreach ($response->json()->data->votes as $vote) {
                $array[] = $vote->user_key;
                if ($vote->value == 1 && empty($dataTmp[$vote->user_key]["positives"])) {
                    $dataTmp[$vote->user_key]["positives"] = 1;
                } else if ($vote->value == 1) {
                    $dataTmp[$vote->user_key]["positives"]++;
                }
                if ($vote->value == -1 && empty($dataTmp[$vote->user_key]["negatives"])) {
                    $dataTmp[$vote->user_key]["negatives"] = 1;
                } else if ($vote->value == -1) {
                    $dataTmp[$vote->user_key]["negatives"]++;
                }
            }
            $arrayUsers = array_unique($array);
            $response = One::post([
                    'component' => 'auth',
                    'api' => 'auth',
                    'method' => 'listUser',
                    'params' => ["userList" => $arrayUsers],
                    'headers' => ["X-AUTH-TOKEN: " . $request->header("X-AUTH-TOKEN")]
                ]
            );

            foreach ($response->json() as $user) {
                $dataTmp[$user->user_key]["profession"] = $user->job;
            }

            $data = [];
            foreach (!empty($dataTmp) ? $dataTmp : [] as $value) {
                if (empty($data[$value['profession']]['Positives'])) {
                    $data[$value['profession']]['Positives'] = 0;
                    $data[$value['profession']]['Negatives'] = 0;
                }
                if (!empty($value["positives"]))
                    $data[$value['profession']]["Positives"] += $value["positives"];
                if (!empty($value["negatives"]))
                    $data[$value['profession']]["Negatives"] += $value["negatives"];
            }

            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }



    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/firstByProfession/{cb_key}",
     *  summary="Show first votes by profession statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show first votes by profession statistics",
     *      @SWG\Schema(ref="#/definitions/votesFirstByParamReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */


    /**
     * Request the results of a Vote Event.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function firstVotesByProfessionChart(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $dataTmp = [];
            foreach ($response->json()->data->votes as $vote) {
                $array[] = $vote->user_key;
                if ($vote->value == 1 && empty($dataTmp[$vote->user_key]["positives"]) && empty($dataTmp[$vote->user_key]["negatives"])) {
                    $dataTmp[$vote->user_key]["positives"] = 1;
                }
                if ($vote->value == -1 && empty($dataTmp[$vote->user_key]["negatives"]) && empty($dataTmp[$vote->user_key]["positives"])) {
                    $dataTmp[$vote->user_key]["negatives"] = 1;
                }
            }
            $arrayUsers = array_unique($array);

            $response = One::post([
                    'component' => 'auth',
                    'api' => 'auth',
                    'method' => 'listUser',
                    'params' => ["userList" => $arrayUsers],
                    'headers' => ["X-AUTH-TOKEN: " . $request->header("X-AUTH-TOKEN")]
                ]
            );

            foreach ($response->json() as $user) {
                $dataTmp[$user->user_key]["profession"] = $user->job;
            }

            $data = [];
            foreach (!empty($dataTmp) ? $dataTmp : [] as $value) {
                if (empty($data[$value['profession']]['Positives'])) {
                    $data[$value['profession']]['Positives'] = 0;
                    $data[$value['profession']]['Negatives'] = 0;
                }
                if (!empty($value["positives"]))
                    $data[$value['profession']]["Positives"] += $value["positives"];
                if (!empty($value["negatives"]))
                    $data[$value['profession']]["Negatives"] += $value["negatives"];
            }

            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }


    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/secondByProfession/{cb_key}",
     *  summary="Show second votes by profession statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show second votes by profession statistics",
     *      @SWG\Schema(ref="#/definitions/votesFirstByParamReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */


    /**
     * Request the results of a Vote Event.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function secondVotesByProfessionChart(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $dataTmp = [];
            foreach ($response->json()->data->votes as $vote) {
                $array[] = $vote->user_key;
                if ($vote->value == 1 && empty($dataTmp[$vote->user_key]["positives"]) && empty($dataTmp[$vote->user_key]["negatives"])) {

                    if (empty($dataTmp[$vote->user_key]["flag"])) {
                        $dataTmp[$vote->user_key]["flag"] = true;
                    } else {
                        $dataTmp[$vote->user_key]["positives"] = 1;
                    }

                }
                if ($vote->value == -1 && empty($dataTmp[$vote->user_key]["negatives"]) && empty($dataTmp[$vote->user_key]["positives"])) {
                    if (empty($dataTmp[$vote->user_key]["flag"])) {
                        $dataTmp[$vote->user_key]["flag"] = true;
                    } else {
                        $dataTmp[$vote->user_key]["negatives"] = 1;
                    }
                }
            }
            $arrayUsers = array_unique($array);

            $response = One::post([
                    'component' => 'auth',
                    'api' => 'auth',
                    'method' => 'listUser',
                    'params' => ["userList" => $arrayUsers],
                    'headers' => ["X-AUTH-TOKEN: " . $request->header("X-AUTH-TOKEN")]
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }


            foreach ($response->json() as $user) {
                $dataTmp[$user->user_key]["profession"] = $user->job;
            }

            $data = [];
            foreach (!empty($dataTmp) ? $dataTmp : [] as $value) {
                if (empty($data[$value['profession']]['Positives'])) {
                    $data[$value['profession']]['Positives'] = 0;
                    $data[$value['profession']]['Negatives'] = 0;
                }
                if (!empty($value["positives"]))
                    $data[$value['profession']]["Positives"] += $value["positives"];
                if (!empty($value["negatives"]))
                    $data[$value['profession']]["Negatives"] += $value["negatives"];
            }


            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }



    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/votesByChannel/{cb_key}",
     *  summary="Show votes by channel statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="LANG-CODE",
     *      in="header",
     *      description="User Language",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Parameter(
     *      name="LANG-CODE-DEFAULT",
     *      in="header",
     *      description="Entity default Language",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show votes by channel statistics",
     *      @SWG\Schema(ref="#/definitions/votesByChannelReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */


    /**
     * Channel
     */

    public function verifyVotesByChannelChart(Request $request, $eventKey, $cbKey)
    {
        try {

            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');
            //GET Information on Vote Configuration

            $weight = [
                'positives' => 1,
                'negatives' => 1
            ];

            // Get data from Event
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            //Get data from all Votes
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }
            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);


            $votes = json_decode($response->content(), true);

            $arrayUsers = [];
            foreach ($votes['data']['users'] as $key => $user) {
                $arrayUsers[] = $key;
            }

            $response = One::post([
                    'component' => 'auth',
                    'api' => 'auth',
                    'method' => 'listUser',
                    'params' => ["userList" => $arrayUsers],
                    'headers' => ["X-AUTH-TOKEN: " . $request->header("X-AUTH-TOKEN")]
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            $arrayUsers = [];
            $users = json_decode($response->content(), true);
            foreach ($users as $user) {
                $arrayUsers[$user['user_key']] = $user;
            }

            $response = One::get([
                    'component' => 'cb',
                    'api' => 'cb',
                    'api_attribute' => $cbKey,
                    'method' => 'topicsWithFirstPost'
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            $topics = $response->json();

            $response = ONE::get([
                'component' => 'cb',
                'api' => 'cb',
                'method' => 'options',
                'api_attribute' => $cbKey,
                'headers' => [
                    'LANG-CODE: '.$languageCode,
                    'LANG-CODE-DEFAULT: '.$languageCodeDefault
                ]
            ]);

            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            $options = [];
            $parameters = $response->json()->parameters;
            foreach ($parameters as $parameter) {
                if ($parameter->code === 'budget') {
                    foreach ($parameter->options as $option) {
                        $options[$option->id] = $option->label;
                    }
                }
                if ($parameter->code === 'category') {
                    foreach ($parameter->options as $option) {
                        $options[$option->id] = $option->label;
                    }
                }
            }

            $data = [];
            foreach ($topics->data as $key => $topic) {
                $dataTemp['title'] = $topic->title;
                $dataTemp['budget'] = 0;
                $dataTemp['category'] = "";
                $dataTemp['geo_area'] = "";
                $dataTemp['channels'] = [];
                foreach ($topic->parameters as $value) {
                    if ($value->code === 'budget') {
                        $dataTemp['budget'] = (int)$options[$value->pivot->value];
                    }
                    if ($value->code === 'category') {
                        $dataTemp['category'] = $options[$value->pivot->value];
                    }
                    if ($value->parameter === 'image_map') {
                        $dataTemp['geo_area'] = ONE::verifyEmpavilleGeoArea($value->pivot->value);
                    }
                }
                foreach ($votes['data']['votes'] as $vote) {
                    if ($vote['vote_key'] == $topic->topic_key) {
                        if (empty($dataTemp['channels'][$vote['source']]['balance'])) {
                            $dataTemp['channels'][$vote['source']]['balance'] = 0;
                        }
                        if ($vote['value'] < 0) {
                            if (!empty($dataTemp['channels'][$vote['source']]['negatives'])) {
                                $dataTemp['channels'][$vote['source']]['negatives'] += 1;

                            } else {
                                $dataTemp['channels'][$vote['source']]['negatives'] = 1;
                            }
                            $dataTemp['channels'][$vote['source']]['balance'] += $vote['value'] * $weight['negatives'];
                        } elseif ($vote['value'] > 0) {
                            if (!empty($dataTemp['channel'][$vote['source']]['positives'])) {
                                $dataTemp['channels'][$vote['source']]['positives'] += 1;
                            } else {
                                $dataTemp['channels'][$vote['source']]['positives'] = 1;
                            }
                            $dataTemp['channels'][$vote['source']]['balance'] += $vote['value'] * $weight['positives'];
                        }
                    }
                }
                $data[] = $dataTemp;
            }

            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }


    /** Get Votes By Channel
     * @param Request $request
     * @param $eventKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function votesByChannel(Request $request, $eventKey)
    {
        try {
            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');
            $authToken = $request->header('X-AUTH-TOKEN');
            $entityKey = $request->header('X-ENTITY-KEY');
            //GET Information on Vote Configuration

            $weight = [
                'positives' => 1,
                'negatives' => 1
            ];


            // Get data from Event
            $eventInfo = new EventInfo($eventKey,$languageCode,$languageCodeDefault);
            //Get data from all Votes
            $allVotes = $eventInfo->getEventVotes();
            $votes = $allVotes->votes;

            if (empty($votes)){
                return response()->json(["data" => []], 200);
            }

            /* ------- Get array with topics details and a collection of votes ------- */
            $topicVotesVerification = $eventInfo->verifyValidTopicsAndVotes($allVotes->votes);
            $votes = $topicVotesVerification['votes_filtered'];
            $topics = $topicVotesVerification['topic_details'];

            $votesBySource = [];
            foreach ($topics as $topic) {
                $dataTemp['title'] = $topic->title;
                $dataTemp['budget'] = 0;
                $dataTemp['category'] = "";
                $dataTemp['geo_area'] = "";
                $dataTemp['channels'] = [];
                $dataTemp['total'] = 0;

                foreach ($topic->parameters??[] as $value) {
                    $options = collect($value->options)->keyBy('id');
                    if ($value->code === 'budget') {
                        if($options->has($value->pivot->value)){
                            $dataTemp['budget'] = (int)$options->get($value->pivot->value)->label;
                        }
                    }
                    if ($value->code === 'category') {
                        if($options->has($value->pivot->value)){
                            $dataTemp['category'] = $options->get($value->pivot->value)->label;
                        }
                    }
                    if ($value->code === 'image_map') {
                        $dataTemp['geo_area'] = ONE::verifyEmpavilleGeoArea($value->pivot->value);
                    }
                }
                foreach ($votes as $vote) {
                    if ($vote->vote_key == $topic->topic_key) {
                        if (empty($dataTemp['channels'][$vote->source]['balance'])) {
                            $dataTemp['channels'][$vote->source]['balance'] = 0;
                        }
                        if ($vote->value < 0) {
                            if (!empty($dataTemp['channels'][$vote->source]['negatives'])) {
                                $dataTemp['channels'][$vote->source]['negatives'] += 1;

                            } else {
                                $dataTemp['channels'][$vote->source]['negatives'] = 1;
                            }
                            $dataTemp['channels'][$vote->source]['balance'] += $vote->value * $weight['negatives'];
                        } elseif ($vote->value > 0) {
                            if (!empty($dataTemp['channel'][$vote->source]['positives'])) {
                                $dataTemp['channels'][$vote->source]['positives'] += 1;
                            } else {
                                $dataTemp['channels'][$vote->source]['positives'] = 1;
                            }
                            $dataTemp['channels'][$vote->source]['balance'] += $vote->value * $weight['positives'];
                        }


                        $dataTemp['total'] += $vote->value;
                    }
                }
                $votesBySource[] = $dataTemp;
            }

            /** Count votes by source, first and second votes */
            $countBySource = $this->countVotesBySource($votes);
            $firsVoteBySource = $this->firstVotesBySource($votes);
            $secondVoteBySource = $this->secondVotesBySource($votes);

            $data = [];
            $data['votes_by_channel'] = $votesBySource;
            $data['count_by_channel'] = $countBySource;
            $data['first_by_channel'] = $firsVoteBySource;
            $data['second_by_channel'] = $secondVoteBySource;

            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }





    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/countByChannel/{cb_key}",
     *  summary="Show votes count by channel statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show vote count by channel statistics",
     *      @SWG\Schema(ref="#/definitions/votesCountByParamReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */


    /**
     * Request the results of a Vote Event.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countVotesByChannelChart(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }
            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);


            $data = [];
            foreach ($response->json()->data->votes as $vote) {

                if ($vote->value == 1 && empty($data[$vote->source]["positives"])) {
                    $data[$vote->source]["positives"] = 1;
                } else if ($vote->value == 1) {
                    $data[$vote->source]["positives"]++;
                }
                if ($vote->value == -1 && empty($data[$vote->source]["negatives"])) {
                    $data[$vote->source]["negatives"] = 1;
                } else if ($vote->value == -1) {
                    $data[$vote->source]["negatives"]++;
                }
            }

            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }


    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/firstByChannel/{cb_key}",
     *  summary="Show first votes by channel statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show first votes by channel statistics",
     *      @SWG\Schema(ref="#/definitions/votesFirstByParamReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */


    /**
     * Request the results of a Vote Event.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function firstVotesByChannelChart(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $data = [];
            $usersVoted= [];

            foreach ($response->json()->data->votes as $vote) {
                if(!isset($usersVoted[$vote->user_key])){
                    $usersVoted[$vote->user_key] = 1;
                    if ($vote->value == 1) {
                        if(empty($data[$vote->source]["positives"])){
                            $data[$vote->source]["positives"] =  1;
                        }else{
                            $data[$vote->source]["positives"] = $data[$vote->source]["positives"] +  1;
                        }
                    }
                    if ($vote->value == -1 ) {
                        if( empty($data[$vote->source]["negatives"])){
                            $data[$vote->source]["negatives"] = 1;
                        }else{
                            $data[$vote->source]["negatives"] = $data[$vote->source]["negatives"] +  1;
                        }
                    }
                }

            }
            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }

    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/secondByChannel/{cb_key}",
     *  summary="Show second votes by channel statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show second votes by channel statistics",
     *      @SWG\Schema(ref="#/definitions/votesFirstByParamReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */

    /**
     * Request the results of a Vote Event.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function secondVotesByChannelChart(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $data = [];
            $usersVoted= [];
            foreach ($response->json()->data->votes as $vote) {
                $usersVoted[$vote->user_key] = !isset($usersVoted[$vote->user_key]) ? 1 : $usersVoted[$vote->user_key] + 1;
                if($usersVoted[$vote->user_key] == 2){
                    if ($vote->value == 1) {
                        if(empty($data[$vote->source]["positives"])){
                            $data[$vote->source]["positives"] =  1;
                        }else{
                            $data[$vote->source]["positives"] = $data[$vote->source]["positives"] +  1;
                        }
                    }
                    if ($vote->value == -1 ) {
                        if( empty($data[$vote->source]["negatives"])){
                            $data[$vote->source]["negatives"] = 1;
                        }else{
                            $data[$vote->source]["negatives"] = $data[$vote->source]["negatives"] +  1;
                        }
                    }
                }
            }


            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }




    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/votesByNb/{cb_key}",
     *  summary="Show votes by neighbourhood statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="LANG-CODE",
     *      in="header",
     *      description="User Language",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Parameter(
     *      name="LANG-CODE-DEFAULT",
     *      in="header",
     *      description="Entity default Language",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show votes by neighbourhood statistics",
     *      @SWG\Schema(ref="#/definitions/votesByNbReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */



    /**
     * Neighbourhood
     */


    public function verifyVotesByNbChart(Request $request, $eventKey, $cbKey)
    {
        try {

            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');

            //GET Information on Vote Configuration

            $weight = [
                'positives' => 1,
                'negatives' => 1
            ];

            // Get data from Event
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }


            //Get data from all Votes
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );


            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $votes = json_decode($response->content(), true);

            $arrayUsers = [];
            foreach ($votes['data']['users'] as $key => $user) {
                $arrayUsers[] = $key;
            }

            $response = One::post([
                    'component' => 'auth',
                    'api' => 'auth',
                    'method' => 'listUser',
                    'params' => ["userList" => $arrayUsers],
                    'headers' => ["X-AUTH-TOKEN: " . $request->header("X-AUTH-TOKEN")]
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            $arrayUsers = [];
            $users = json_decode($response->content(), true);
            foreach ($users as $user) {
                $arrayUsers[$user['user_key']] = $user;
            }


            $response = One::get([
                    'component' => 'cb',
                    'api' => 'cb',
                    'api_attribute' => $cbKey,
                    'method' => 'topicsWithFirstPost'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }
            $topics = $response->json();

            $response = ONE::get([
                'component' => 'cb',
                'api' => 'cb',
                'method' => 'options',
                'api_attribute' => $cbKey,
                'headers' => [
                    'LANG-CODE: '.$languageCode,
                    'LANG-CODE-DEFAULT: '.$languageCodeDefault
                ]
            ]);

            if ($response->statusCode() != 200) {
                throw new Exception();
            }
            $options = [];
            $parameters = $response->json()->parameters;
            foreach ($parameters as $parameter) {
                if ($parameter->code === 'budget') {
                    foreach ($parameter->options as $option) {
                        $options[$option->id] = $option->label;
                    }
                }
                if ($parameter->code === 'category') {
                    foreach ($parameter->options as $option) {
                        $options[$option->id] = $option->label;
                    }
                }
            }

            $data = [];
            foreach ($topics->data as $key => $topic) {
                $dataTemp['title'] = $topic->title;
                $dataTemp['geo_area'] = "";
                $dataTemp['Uptown']['positives'] = 0;
                $dataTemp['Uptown']['negatives'] = 0;
                $dataTemp['Uptown']['balance'] = 0;
                $dataTemp['Middletown']['positives'] = 0;
                $dataTemp['Middletown']['negatives'] = 0;
                $dataTemp['Middletown']['balance'] = 0;
                $dataTemp['Downtown']['positives'] = 0;
                $dataTemp['Downtown']['negatives'] = 0;
                $dataTemp['Downtown']['balance'] = 0;
                $dataTemp['budget'] = 0;
                $dataTemp['category'] = "";
                $dataTemp['geo_area'] = "";

                foreach ($topic->parameters as $value) {
                    if ($value->code === 'budget') {
                        $dataTemp['budget'] = (int)$options[$value->pivot->value];
                    }
                    if ($value->code === 'category') {
                        $dataTemp['category'] = $options[$value->pivot->value];
                    }
                    if ($value->code === 'image_map') {
                        $dataTemp['geo_area'] = ONE::verifyEmpavilleGeoArea($value->pivot->value);
                    }
                }
                foreach ($votes['data']['votes'] as $vote) {
                    if ($vote['vote_key'] == $topic->topic_key) {

                        if ($vote['value'] < 0) {
                            if(!empty($arrayUsers[$vote['user_key']]['street'])) {
                                $dataTemp[$arrayUsers[$vote['user_key']]['street']]['negatives'] += 1;
                                $dataTemp[$arrayUsers[$vote['user_key']]['street']]['balance'] += $vote['value'] * $weight['negatives'];
                            }
                        } elseif ($vote['value'] > 0) {
                            if(!empty($arrayUsers[$vote['user_key']]['street'])) {
                                $dataTemp[$arrayUsers[$vote['user_key']]['street']]['positives'] += 1;
                                $dataTemp[$arrayUsers[$vote['user_key']]['street']]['balance'] += $vote['value'] * $weight['positives'];
                            }
                        }
                    }
                }
                $data[] = $dataTemp;
            }

            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }



    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/countByNb/{cb_key}",
     *  summary="Show votes count by neighbourhood statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show vote count by neighbourhood statistics",
     *      @SWG\Schema(ref="#/definitions/votesCountByParamReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */


    /**
     * Request the results of a Vote Event.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countVotesByNbChart(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $dataTmp = [];
            foreach ($response->json()->data->votes as $vote) {
                $array[] = $vote->user_key;
                if ($vote->value == 1 && empty($dataTmp[$vote->user_key]["positives"])) {
                    $dataTmp[$vote->user_key]["positives"] = 1;
                } else if ($vote->value == 1) {
                    $dataTmp[$vote->user_key]["positives"]++;
                }
                if ($vote->value == -1 && empty($dataTmp[$vote->user_key]["negatives"])) {
                    $dataTmp[$vote->user_key]["negatives"] = 1;
                } else if ($vote->value == -1) {
                    $dataTmp[$vote->user_key]["negatives"]++;
                }
            }
            $arrayUsers = array_unique($array);
            $response = One::post([
                    'component' => 'auth',
                    'api' => 'auth',
                    'method' => 'listUser',
                    'params' => ["userList" => $arrayUsers],
                    'headers' => ["X-AUTH-TOKEN: " . $request->header("X-AUTH-TOKEN")]
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            foreach ($response->json() as $user) {
                $dataTmp[$user->user_key]["street"] = $user->street;
            }

            $data = [];
            foreach (!empty($dataTmp) ? $dataTmp : [] as $value) {
                if (empty($data[$value['street']]['Positives'])) {
                    $data[$value['street']]['Positives'] = 0;
                    $data[$value['street']]['Negatives'] = 0;
                }
                if (!empty($value["positives"]))
                    $data[$value['street']]["Positives"] += $value["positives"];
                if (!empty($value["negatives"]))
                    $data[$value['street']]["Negatives"] += $value["negatives"];
            }


            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }


    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/firstByNb/{cb_key}",
     *  summary="Show first votes by neighbourhood statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show first votes by neighbourhood statistics",
     *      @SWG\Schema(ref="#/definitions/votesFirstByParamReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */




    /**
     * Request the results of a Vote Event.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function firstVotesByNbChart(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $dataTmp = [];
            foreach ($response->json()->data->votes as $vote) {
                $array[] = $vote->user_key;
                if ($vote->value == 1 && empty($dataTmp[$vote->user_key]["positives"]) && empty($dataTmp[$vote->user_key]["negatives"])) {
                    $dataTmp[$vote->user_key]["positives"] = 1;
                }
                if ($vote->value == -1 && empty($dataTmp[$vote->user_key]["negatives"]) && empty($dataTmp[$vote->user_key]["positives"])) {
                    $dataTmp[$vote->user_key]["negatives"] = 1;
                }
            }
            $arrayUsers = array_unique($array);

            $response = One::post([
                    'component' => 'auth',
                    'api' => 'auth',
                    'method' => 'listUser',
                    'params' => ["userList" => $arrayUsers],
                    'headers' => ["X-AUTH-TOKEN: " . $request->header("X-AUTH-TOKEN")]
                ]
            );

            foreach ($response->json() as $user) {
                $dataTmp[$user->user_key]["street"] = $user->street;
            }

            $data = [];
            foreach (!empty($dataTmp) ? $dataTmp : [] as $value) {
                if (empty($data[$value['street']]['Positives'])) {
                    $data[$value['street']]['Positives'] = 0;
                    $data[$value['street']]['Negatives'] = 0;
                }
                if (!empty($value["positives"]))
                    $data[$value['street']]["Positives"] += $value["positives"];
                if (!empty($value["negatives"]))
                    $data[$value['street']]["Negatives"] += $value["negatives"];
            }

            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }



    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/secondByNb/{cb_key}",
     *  summary="Show second votes by neighbourhood statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show second votes by neighbourhood statistics",
     *      @SWG\Schema(ref="#/definitions/votesFirstByParamReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */


    /**
     * Request the results of a Vote Event.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function secondVotesByNbChart(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $dataTmp = [];
            foreach ($response->json()->data->votes as $vote) {
                $array[] = $vote->user_key;
                if ($vote->value == 1 && empty($dataTmp[$vote->user_key]["positives"]) && empty($dataTmp[$vote->user_key]["negatives"])) {

                    if (empty($dataTmp[$vote->user_key]["flag"])) {
                        $dataTmp[$vote->user_key]["flag"] = true;
                    } else {
                        $dataTmp[$vote->user_key]["positives"] = 1;
                    }

                }
                if ($vote->value == -1 && empty($dataTmp[$vote->user_key]["negatives"]) && empty($dataTmp[$vote->user_key]["positives"])) {
                    if (empty($dataTmp[$vote->user_key]["flag"])) {
                        $dataTmp[$vote->user_key]["flag"] = true;
                    } else {
                        $dataTmp[$vote->user_key]["negatives"] = 1;
                    }
                }
            }
            $arrayUsers = array_unique($array);

            $response = One::post([
                    'component' => 'auth',
                    'api' => 'auth',
                    'method' => 'listUser',
                    'params' => ["userList" => $arrayUsers],
                    'headers' => ["X-AUTH-TOKEN: " . $request->header("X-AUTH-TOKEN")]
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }


            foreach ($response->json() as $user) {
                $dataTmp[$user->user_key]["street"] = $user->street;
            }

            $data = [];
            foreach (!empty($dataTmp) ? $dataTmp : [] as $value) {
                if (empty($data[$value['street']]['Positives'])) {
                    $data[$value['street']]['Positives'] = 0;
                    $data[$value['street']]['Negatives'] = 0;
                }
                if (!empty($value["positives"]))
                    $data[$value['street']]["Positives"] += $value["positives"];
                if (!empty($value["negatives"]))
                    $data[$value['street']]["Negatives"] += $value["negatives"];
            }


            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }



    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/countByAge/{cb_key}",
     *  summary="Show votes count by age statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show vote count by age statistics",
     *      @SWG\Schema(ref="#/definitions/votesCountByParamReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */


    /**
     * Request the results of a Vote Event.
     *
     * @param Request $request
     * @param $eventKey
     * @param $cbKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function countVotesByAgeChart(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $dataTmp = [];
            foreach ($response->json()->data->votes as $vote) {
                $array[] = $vote->user_key;
                if ($vote->value == 1 && empty($dataTmp[$vote->user_key]["positives"])) {
                    $dataTmp[$vote->user_key]["positives"] = 1;
                } else if ($vote->value == 1) {
                    $dataTmp[$vote->user_key]["positives"]++;
                }
                if ($vote->value == -1 && empty($dataTmp[$vote->user_key]["negatives"])) {
                    $dataTmp[$vote->user_key]["negatives"] = 1;
                } else if ($vote->value == -1) {
                    $dataTmp[$vote->user_key]["negatives"]++;
                }
            }
            $arrayUsers = array_unique($array);
            $response = One::post([
                    'component' => 'auth',
                    'api' => 'auth',
                    'method' => 'listUser',
                    'params' => ["userList" => $arrayUsers],
                    'headers' => ["X-AUTH-TOKEN: " . $request->header("X-AUTH-TOKEN")]
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            foreach ($response->json() as $user) {
                $birthday = Carbon::createFromFormat('Y-m-d', $user->birthday);
                $dataTmp[$user->user_key]["age"] = $birthday->age;
            }
            $data = [];

            foreach (!empty($dataTmp) ? $dataTmp : [] as $value) {
                if ($value['age'] < 20) {
                    $age = 20;
                } elseif ($value['age'] < 30) {
                    $age = 30;
                } elseif ($value['age'] < 40) {
                    $age = 40;
                } elseif ($value['age'] < 50) {
                    $age = 50;
                } elseif ($value['age'] < 60) {
                    $age = 60;
                } elseif ($value['age'] < 70) {
                    $age = 70;
                } elseif ($value['age'] < 80) {
                    $age = 80;
                }

                if (isset($age) && empty($data[$age]['Positives'])) {
                    $data[$age]['Positives'] = 0;
                    $data[$age]['Negatives'] = 0;
                }
                if (isset($age) && !empty($value["positives"]))
                    $data[$age]["Positives"] += $value["positives"];
                if (isset($age) && !empty($value["negatives"]))
                    $data[$age]["Negatives"] += $value["negatives"];
            }


            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }



    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/firstByAge/{cb_key}",
     *  summary="Show first votes by age statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show first votes by age statistics",
     *      @SWG\Schema(ref="#/definitions/votesFirstByParamReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */



    /**
     * Request the results of a Vote Event.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function firstVotesByAgeChart(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $dataTmp = [];
            foreach ($response->json()->data->votes as $vote) {
                $array[] = $vote->user_key;
                if ($vote->value == 1 && empty($dataTmp[$vote->user_key]["positives"]) && empty($dataTmp[$vote->user_key]["negatives"])) {
                    $dataTmp[$vote->user_key]["positives"] = 1;
                }
                if ($vote->value == -1 && empty($dataTmp[$vote->user_key]["negatives"]) && empty($dataTmp[$vote->user_key]["positives"])) {
                    $dataTmp[$vote->user_key]["negatives"] = 1;
                }
            }
            $arrayUsers = array_unique($array);

            $response = One::post([
                    'component' => 'auth',
                    'api' => 'auth',
                    'method' => 'listUser',
                    'params' => ["userList" => $arrayUsers],
                    'headers' => ["X-AUTH-TOKEN: " . $request->header("X-AUTH-TOKEN")]
                ]
            );

            foreach ($response->json() as $user) {
                $birthday = Carbon::createFromFormat('Y-m-d', $user->birthday);
                $dataTmp[$user->user_key]["age"] = $birthday->age;
            }

            $data = [];
            foreach (!empty($dataTmp) ? $dataTmp : [] as $value) {
                if ($value['age'] < 20) {
                    $age = 20;
                } elseif ($value['age'] < 30) {
                    $age = 30;
                } elseif ($value['age'] < 40) {
                    $age = 40;
                } elseif ($value['age'] < 50) {
                    $age = 50;
                } elseif ($value['age'] < 60) {
                    $age = 60;
                } elseif ($value['age'] < 70) {
                    $age = 70;
                } elseif ($value['age'] < 80) {
                    $age = 80;
                }

                if (empty($data[$age]['Positives'])) {
                    $data[$age]['Positives'] = 0;
                    $data[$age]['Negatives'] = 0;
                }
                if (!empty($value["positives"]))
                    $data[$age]["Positives"] += $value["positives"];
                if (!empty($value["negatives"]))
                    $data[$age]["Negatives"] += $value["negatives"];
            }

            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }



    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/secondByAge/{cb_key}",
     *  summary="Show second votes by age statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="cb_key",
     *      in="path",
     *      description="Cb Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show second votes by age statistics",
     *      @SWG\Schema(ref="#/definitions/votesFirstByParamReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */


    /**
     * Request the results of a Vote Event.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function secondVotesByAgeChart(Request $request, $eventKey, $cbKey)
    {
        try {
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => []], 200);

            $dataTmp = [];
            foreach ($response->json()->data->votes as $vote) {
                $array[] = $vote->user_key;
                if ($vote->value == 1 && empty($dataTmp[$vote->user_key]["positives"]) && empty($dataTmp[$vote->user_key]["negatives"])) {

                    if (empty($dataTmp[$vote->user_key]["flag"])) {
                        $dataTmp[$vote->user_key]["flag"] = true;
                    } else {
                        $dataTmp[$vote->user_key]["positives"] = 1;
                    }

                }
                if ($vote->value == -1 && empty($dataTmp[$vote->user_key]["negatives"]) && empty($dataTmp[$vote->user_key]["positives"])) {
                    if (empty($dataTmp[$vote->user_key]["flag"])) {
                        $dataTmp[$vote->user_key]["flag"] = true;
                    } else {
                        $dataTmp[$vote->user_key]["negatives"] = 1;
                    }
                }
            }
            $arrayUsers = array_unique($array);

            $response = One::post([
                    'component' => 'auth',
                    'api' => 'auth',
                    'method' => 'listUser',
                    'params' => ["userList" => $arrayUsers],
                    'headers' => ["X-AUTH-TOKEN: " . $request->header("X-AUTH-TOKEN")]
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }


            foreach ($response->json() as $user) {
                $birthday = Carbon::createFromFormat('Y-m-d', $user->birthday);
                $dataTmp[$user->user_key]["age"] = $birthday->age;
            }

            $data = [];
            foreach (!empty($dataTmp) ? $dataTmp : [] as $value) {
                if ($value['age'] < 20) {
                    $age = 20;
                } elseif ($value['age'] < 30) {
                    $age = 30;
                } elseif ($value['age'] < 40) {
                    $age = 40;
                } elseif ($value['age'] < 50) {
                    $age = 50;
                } elseif ($value['age'] < 60) {
                    $age = 60;
                } elseif ($value['age'] < 70) {
                    $age = 70;
                } elseif ($value['age'] < 80) {
                    $age = 80;
                }

                if (empty($data[$age]['Positives'])) {
                    $data[$age]['Positives'] = 0;
                    $data[$age]['Negatives'] = 0;
                }
                if (!empty($value["positives"]))
                    $data[$age]["Positives"] += $value["positives"];
                if (!empty($value["negatives"]))
                    $data[$age]["Negatives"] += $value["negatives"];
            }

            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }

    public function voteTrend(Request $request, $eventKey)
    {

    }


    public function empavilleSchools(Request $request, $eventKey, $cbKey)
    {
        try {

            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');

            //GET Information on Vote Configuration

            $weight = [
                'positives' => 1,
                'negatives' => 1
            ];

            // Get data from Event
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey
                ]
            );
            if ($response->statusCode() != 200) {
                throw new Exception();
            }
            $startDate = $response->json()->start_date;
            $endDate = $response->json()->end_date;


            //Get data from all Votes
            $response = One::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );


            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes))
                return response()->json(["data" => [], 'summary' => []], 200);

            $votes = json_decode($response->content(), true);
            $response = One::get([
                    'component' => 'cb',
                    'api' => 'cb',
                    'api_attribute' => $cbKey,
                    'method' => 'topicsWithFirstPost'
                ]
            );


            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            $topics = $response->json();
            $votesPositives = $votes['data']['positives'];
            $votesNegatives = $votes['data']['negatives'];
            $data = [];
            $countTotalVotes = 0;
            $countTotalPositives = 0;
            $countTotalNegative = 0;
            $countUsersVoted = count($votes['data']['users']);
            $countBalance = 0;

            $response = ONE::get([
                'component' => 'cb',
                'api' => 'cb',
                'method' => 'options',
                'api_attribute' => $cbKey,
                'headers' => [
                    'LANG-CODE: '.$languageCode,
                    'LANG-CODE-DEFAULT: '.$languageCodeDefault,
                ]
            ]);

            $options = [];
            $parameters = $response->json()->parameters;
            foreach ($parameters as $parameter) {
                if ($parameter->code === 'budget') {
                    foreach ($parameter->options as $option) {
                        $options[$option->id] = $option->label;
                    }
                }
                if ($parameter->code === 'category') {
                    foreach ($parameter->options as $option) {
                        $options[$option->id] = $option->label;
                    }
                }
            }

            foreach ($topics->data as $topic) {
                $dataTemp['balance'] = 0;
                $dataTemp['title'] = $topic->title;
                $dataTemp['budget'] = 0;
                $dataTemp['category'] = "";
                $dataTemp['geo_area'] = "";

                foreach ($topic->parameters as $value) {
                    if ($value->code === 'budget') {
                        $dataTemp['budget'] = (int)$options[$value->pivot->value];
                    }
                    if ($value->code === 'category') {
                        $dataTemp['category'] = $options[$value->pivot->value];
                    }
                    if ($value->code === 'image_map') {
                        $dataTemp['geo_area'] = ONE::verifyEmpavilleGeoArea($value->pivot->value);
                    }
                }
                if (!empty($votesPositives[$topic->topic_key])) {
                    $countTotalVotes = $countTotalVotes + $votesPositives[$topic->topic_key];
                    $countTotalPositives = $countTotalPositives + $votesPositives[$topic->topic_key];
                    $dataTemp['positives'] = $votesPositives[$topic->topic_key];
                } else {
                    $dataTemp['positives'] = 0;
                }
                if (!empty($votesNegatives[$topic->topic_key])) {
                    $countTotalVotes = $countTotalVotes + $votesNegatives[$topic->topic_key];
                    $countTotalNegative = $countTotalNegative + $votesNegatives[$topic->topic_key];
                    $dataTemp['negatives'] = $votesNegatives[$topic->topic_key];
                } else {
                    $dataTemp['negatives'] = 0;
                }
                $dataTemp['balance'] = ($dataTemp['positives'] * $weight['positives']) - ($dataTemp['negatives'] * $weight['negatives']);
                $countBalance = $countBalance + $dataTemp['balance'];

                $data[] = $dataTemp;
            }
            usort($data, function ($a, $b) {
                return $b['balance'] - $a['balance'];
            });

            // verify if in budget


            $total = 0;
            foreach (!empty($data) ? $data : [] as $key => $proposal) {
                if (($total + $proposal['budget']) <= $this->totalBugdet) {
                    $total = $total + $proposal['budget'];
                    $data[$key]['winner'] = true;
                } else {
                    $data[$key]['winner'] = false;
                }
            }

            $max = 0;
            foreach (!empty($data) ? $data : [] as $key => $proposal) {
                if (empty($proposal['budget']) && $proposal['balance'] >= $max){
                    $max = $proposal['balance'];
                    $data[$key]['winner'] = true;
                } else {
                    $data[$key]['winner'] = false;
                }
            }

            $summary = [
                'total' => $countTotalVotes,
                'total_positives' => $countTotalPositives,
                'total_negatives' => $countTotalNegative,
                'total_users_voted' => $countUsersVoted,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_balance' => $countBalance
            ];

            return response()->json(["data" => $data, 'summary' => $summary], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }


    /** Get Voters By Channel
     * @param Request $request
     * @param $eventKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function votersByChannel(Request $request, $eventKey)
    {
        try {
            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');
            //GET Information on Vote Configuration

            // Get data from Event
            $eventInfo = new EventInfo($eventKey,$languageCode,$languageCodeDefault);
            //Get data from all Votes
            $allVotes = $eventInfo->getEventVotes();
            $votes = $allVotes->votes;
            $voters = [];

            if (empty($votes)){
                return response()->json(["data" => []], 200);
            }

            /* ------- Get array with topics details and a collection of votes ------- */
            $topicVotesVerification = $eventInfo->verifyValidTopicsAndVotes($allVotes->votes);
            $votes = $topicVotesVerification['votes_filtered'];

            $topics = $topicVotesVerification['topic_details'];

            $votesBySource = [];
            foreach ($topics as $topic) {
                $dataTemp['title'] = $topic->title;
                $dataTemp['budget'] = 0;
                $dataTemp['category'] = "";
                $dataTemp['geo_area'] = "";
                $dataTemp['channels'] = [];
                $dataTemp['total'] = 0;

                foreach ($topic->parameters??[] as $value) {
                    $options = collect($value->options)->keyBy('id');
                    if ($value->code === 'budget') {
                        if($options->has($value->pivot->value)){
                            $dataTemp['budget'] = (int)$options->get($value->pivot->value)->label;
                        }
                    }
                    if ($value->code === 'category') {
                        if($options->has($value->pivot->value)){
                            $dataTemp['category'] = $options->get($value->pivot->value)->label;
                        }
                    }
                    if ($value->code === 'image_map') {
                        $dataTemp['geo_area'] = ONE::verifyEmpavilleGeoArea($value->pivot->value);
                    }
                }
                foreach ($votes as $vote) {
                    if ($vote->vote_key == $topic->topic_key) {
                        $dataTemp['channels'][$vote->source][$vote->user_key] = $vote->user_key;
                        $voters[$vote->source][$vote->user_key] = $vote->user_key;
                    }
                }

                foreach ($dataTemp['channels'] as &$channel){
                    $channel = count($channel);
                    $dataTemp['total'] += $channel;
                }

                $votesBySource[] = $dataTemp;
            }

            /** Count votes by source, first and second votes */
            $countBySource = [];
            foreach ($voters as $source => $voter){
                $countBySource[$source] = count($voter);
            }

            $data = [];
            $data['votes_by_channel'] = $votesBySource;
            $data['count_by_channel'] = $countBySource;

            return response()->json(["data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }
}
