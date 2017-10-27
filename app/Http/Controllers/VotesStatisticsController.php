<?php

namespace App\Http\Controllers;

use App\Generic\EventInfo;
use App\Generic\UsersInfo;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use ONE;

/**
 * @SWG\Tag(
 *   name="Vote Statistics",
 *   description="Everything about Vote Statistics",
 * )
 *
 *  @SWG\Definition(
 *      definition="analyticsErrorDefault",
 *      @SWG\Property(property="error", type="string", format="string")
 *  )
 *
 *   @SWG\Definition(
 *   definition="topStatistics",
 *   @SWG\Property(property="top", format="integer", type="integer")
 * )
 *
 *   @SWG\Definition(
 *   definition="parameterStatistics",
 *   @SWG\Property(property="parameter_key", format="string", type="string")
 * )
 *
 *
 *  @SWG\Definition(
 *   definition="votesChannelReply",
 *   type="object",
 *   allOf={
 *       @SWG\Schema(
 *           @SWG\Property(
 *                 property="in_person_votes",
 *                 type="object",
 *                 allOf={
 *                    @SWG\Schema(
 *                          @SWG\Property(property="value", format="integer", type="integer"),
 *                      )
 *                 }
 *             ),
 *           @SWG\Property(
 *                 property="web_votes",
 *                 type="object",
 *                 allOf={
 *                    @SWG\Schema(
 *                          @SWG\Property(property="value", format="integer", type="integer"),
 *                      )
 *                 }
 *             ),
 *           @SWG\Property(
 *                 property="all_votes",
 *                 type="object",
 *                 allOf={
 *                    @SWG\Schema(
 *                          @SWG\Property(property="value", format="integer", type="integer"),
 *                      )
 *                 }
 *             ),
 *
 *          )
 *      }
 *  )
 *
 *
 *
 *  @SWG\Definition(
 *   definition="votesByTownReply",
 *   type="object",
 *   allOf={
 *
 *     @SWG\Schema(
 *           @SWG\Property(
 *                 property="data",
 *                 type="object",
 *                 allOf={
 *
 *       @SWG\Schema(
 *           @SWG\Property(
 *                 property="in_person_votes",
 *                 type="object",
 *                 allOf={
 *                    @SWG\Schema(
 *                          @SWG\Property(property="town_name", format="integer", type="integer"),
 *                      )
 *                 }
 *             ),
 *           @SWG\Property(
 *                 property="web_votes",
 *                 type="object",
 *                 allOf={
 *                    @SWG\Schema(
 *                          @SWG\Property(property="town_name", format="integer", type="integer"),
 *                      )
 *                 }
 *             ),
 *           @SWG\Property(
 *                 property="all_votes",
 *                 type="object",
 *                 allOf={
 *                    @SWG\Schema(
 *                          @SWG\Property(property="town_name", format="integer", type="integer"),
 *                      )
 *                 }
 *             ),
 *
 *          )
 * }
 *     )
 * )
 *      }
 *  )
 *
 *
 *
 *
 *
 *  @SWG\Definition(
 *   definition="voteByDateReply",
 *   type="object",
 *   allOf={
 *
 *     @SWG\Schema(
 *           @SWG\Property(
 *                 property="data",
 *                 type="object",
 *                 allOf={
 *
 *       @SWG\Schema(
 *           @SWG\Property(property="negative_vote", format="boolean", type="boolean"),
 *           @SWG\Property(
 *                 property="total",
 *                 type="object",
 *                 allOf={
 *                    @SWG\Schema(ref="#/definitions/votesChannelReply")
 *                 }
 *             ),
 *           @SWG\Property(
 *                 property="balance",
 *                 type="object",
 *                 allOf={
 *                    @SWG\Schema(ref="#/definitions/votesChannelReply")
 *                 }
 *             ),
 *           @SWG\Property(
 *                 property="positive",
 *                 type="object",
 *                 allOf={
 *                    @SWG\Schema(ref="#/definitions/votesChannelReply")
 *                 }
 *             ),
 *           @SWG\Property(
 *                 property="negative",
 *                 type="object",
 *                 allOf={
 *                    @SWG\Schema(ref="#/definitions/votesChannelReply")
 *                 }
 *             ),
 *
 *          )
 * }
 *     )
 * )
 *      }
 *  )
 *
 *  @SWG\Definition(
 *   definition="votesByParameterTownReply",
 *   type="object",
 *   allOf={
 *
 *     @SWG\Schema(
 *           @SWG\Property(
 *                 property="data",
 *                 type="object",
 *                 allOf={
 *
 *       @SWG\Schema(
 *           @SWG\Property(
 *                 property="in_person_votes",
 *                 type="object",
 *                 allOf={
 *                    @SWG\Schema(
 *                          @SWG\Property(
 *                      property="town",
 *                      type="object",
 *                      allOf={
 *                          @SWG\Schema(
 *                              @SWG\Property(property="value", format="integer", type="integer"),
 *                          )
 *                      }
 *                      ),
 *                      )
 *                 }
 *             ),
 *           @SWG\Property(
 *                 property="web_votes",
 *                 type="object",
 *                 allOf={
 *                    @SWG\Schema(
 *                          @SWG\Property(property="value", format="integer", type="integer"),
 *                      )
 *                 }
 *             )
 *
 *          )
 * }
 *     )
 * )
 *      }
 *  )
 *
 *  @SWG\Definition(
 *   definition="voteByTopReply",
 *   type="object",
 *   allOf={
 *
 *     @SWG\Schema(
 *           @SWG\Property(
 *                 property="data",
 *                 type="object",
 *                 allOf={
 *
 *       @SWG\Schema(
 *           @SWG\Property(property="negative_vote", format="boolean", type="boolean"),
 *           @SWG\Property(
 *                 property="total",
 *                 type="array",
 *                 @SWG\Items(
 *                      @SWG\Property(property="position", format="integer", type="integer"),
 *                      @SWG\Property(property="topic_name", format="string", type="string"),
 *                      @SWG\Property(property="total_votes", format="integer", type="integer")
 *                      )
 *             ),
 *           @SWG\Property(
 *                 property="balance",
 *                 type="array",
 *                 @SWG\Items(
 *                      @SWG\Property(property="position", format="integer", type="integer"),
 *                      @SWG\Property(property="topic_name", format="string", type="string"),
 *                      @SWG\Property(property="total_votes", format="integer", type="integer")
 *                      )
 *             ),
 *           @SWG\Property(
 *                 property="positive",
 *                 type="array",
 *                 @SWG\Items(
 *                      @SWG\Property(property="position", format="integer", type="integer"),
 *                      @SWG\Property(property="topic_name", format="string", type="string"),
 *                      @SWG\Property(property="total_votes", format="integer", type="integer")
 *                      )
 *             ),
 *           @SWG\Property(
 *                 property="negative",
 *                 type="array",
 *                 @SWG\Items(
 *                      @SWG\Property(property="position", format="integer", type="integer"),
 *                      @SWG\Property(property="topic_name", format="string", type="string"),
 *                      @SWG\Property(property="total_votes", format="integer", type="integer")
 *                      )
 *             )
 *
 *          )
 * }
 *     )
 * )
 *      }
 *  )
 *
 *
 *  @SWG\Definition(
 *   definition="voteByTopDateReply",
 *   type="object",
 *   allOf={
 *     @SWG\Schema(
 *           @SWG\Property(
 *                 property="data",
 *                 type="object",
 *                 allOf={
 *                  @SWG\Schema(
 *                      @SWG\Property(property="negative_vote", format="boolean", type="boolean"),
 *                      @SWG\Property(
 *                          property="total",
 *                          type="array",
 *                          @SWG\Items(
 *                                  @SWG\Property(property="topic_name", format="string", type="string"),
 *                                  @SWG\Property(
 *                                      property="votes",
 *                                      type="object",
 *                                      allOf={
 *                                          @SWG\Schema(
 *                                                  @SWG\Property(property="date_value", format="integer", type="integer"),
 *                                              )
 *                                      }
 *                                  )
 *                              )
 *                      ),
 *           @SWG\Property(
 *                 property="balance",
 *                 type="array",
 *                 @SWG\Items(
 *                      @SWG\Property(property="topic_name", format="string", type="string"),
 *                      @SWG\Property(
 *                          property="votes",
 *                          type="object",
 *                           allOf={
 *                              @SWG\Schema(
 *                                      @SWG\Property(property="date_value", format="integer", type="integer"),
 *                                  )
 *                          }
 *                      )
 *                  )
 *             ),
 *            @SWG\Property(
 *                 property="positive",
 *                 type="array",
 *                 @SWG\Items(
 *                      @SWG\Property(property="topic_name", format="string", type="string"),
 *                      @SWG\Property(
 *                          property="votes",
 *                          type="object",
 *                           allOf={
 *                              @SWG\Schema(
 *                                      @SWG\Property(property="date_value", format="integer", type="integer"),
 *                                  )
 *                          }
 *                      )
 *                  )
 *             ),
 *            @SWG\Property(
 *                 property="negative",
 *                 type="array",
 *                 @SWG\Items(
 *                      @SWG\Property(property="topic_name", format="string", type="string"),
 *                      @SWG\Property(
 *                          property="votes",
 *                          type="object",
 *                           allOf={
 *                              @SWG\Schema(
 *                                      @SWG\Property(property="date_value", format="integer", type="integer"),
 *                                  )
 *                          }
 *                      )
 *                  )
 *             ),
 *          )
 *
 *     }
 *     )
 * )
 *
 *      }
 *  )
 *
 *
 *
 *
 *  @SWG\Definition(
 *   definition="voteByParameterReply",
 *   type="object",
 *   allOf={
 *     @SWG\Schema(
 *           @SWG\Property(
 *                 property="data",
 *                 type="object",
 *                 allOf={
 *
 *       @SWG\Schema(
 *           @SWG\Property(property="negative_vote", format="boolean", type="boolean"),
 *           @SWG\Property(
 *                 property="total",
 *                 type="array",
 *                 @SWG\Items(
 *                      @SWG\Property(property="value", format="integer", type="integer")
 *                  )
 *             ),
 *           @SWG\Property(
 *                 property="balance",
 *                 type="array",
 *                 @SWG\Items(
 *                      @SWG\Property(property="value", format="integer", type="integer")
 *                  )
 *             ),
 *            @SWG\Property(
 *                 property="positive",
 *                 type="array",
 *                 @SWG\Items(
 *                      @SWG\Property(property="value", format="integer", type="integer")
 *                  )
 *             ),
 *            @SWG\Property(
 *                 property="negative",
 *                 type="array",
 *                 @SWG\Items(
 *                      @SWG\Property(property="value", format="integer", type="integer")
 *                  )
 *             )
 *          )
 * }
 *     )
 * )
 *      }
 *  )
 *
 *
 *
 *
 *
 *
 *
 */



class VotesStatisticsController extends Controller
{

    /**
     * VotesController constructor.
     */
    public function __construct()
    {

    }

    /** Generate array of dates between date range
     * @param Carbon $start_date
     * @param Carbon $end_date
     * @return array
     */
    private function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];

        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $dates[$date->format('Y-m-d')] = 0;
        }

        return $dates;
    }

    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/statisticsByDate",
     *  summary="Show vote statistics by date",
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
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show vote statistics by date",
     *      @SWG\Schema(ref="#/definitions/voteByDateReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */

    /** Get vote statistics by date and channel
     * @param Request $request
     * @param $eventKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function voteStatisticsByDate(Request $request, $eventKey){
        try {
            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');

            $eventInfo = new EventInfo($eventKey,$languageCode,$languageCodeDefault);
            $event = $eventInfo->getEvent();
            $negativeVote = $eventInfo->verifyNegativeVoteExists($event);

            $startDate = $event->start_date;
            $endDate = $event->end_date;


            $dateRange = $this->generateDateRange(Carbon::parse($startDate),Carbon::parse($endDate));
            $allVotes = $eventInfo->getEventVotes()->votes;
            $topicVotesVerification = $eventInfo->verifyValidTopicsAndVotes($allVotes);
            $votes = $topicVotesVerification['votes_filtered'];

            if ($votes->isEmpty()) {
                $balance = ['in_person_votes' => [],'web_votes' => [], 'all_votes' => []];
                $positive = ['in_person_votes' => [],'web_votes' => [], 'all_votes' => []];
                $negative = ['in_person_votes' => [],'web_votes' => [], 'all_votes' => []];
                $total = ['in_person_votes' => [],'web_votes' => [], 'all_votes' => []];
                $data = ['negative_vote' => $negativeVote,'total' => $total,'balance' => $balance, 'positive' => $positive, 'negative' => $negative];
                return response()->json(["data" => $data], 200);
            }

            $balance = ['in_person_votes' => $dateRange,'web_votes' => $dateRange, 'all_votes' => $dateRange];
            $positive = ['in_person_votes' => $dateRange,'web_votes' => $dateRange, 'all_votes' => $dateRange];
            $negative = ['in_person_votes' => $dateRange,'web_votes' => $dateRange, 'all_votes' => $dateRange];
            $total = ['in_person_votes' => $dateRange,'web_votes' => $dateRange, 'all_votes' => $dateRange];

            foreach ($votes as $vote){
                $date = Carbon::parse($vote->created_at);
                if(!array_key_exists($date->format('Y-m-d'),$dateRange)){
                    continue;
                }
                switch ($vote->source)
                {
                    case 'in_person':
                        if($vote->value > 0){
                            $positive['in_person_votes'][$date->format('Y-m-d')] += $vote->value;
                        }else{
                            $negative['in_person_votes'][$date->format('Y-m-d')] += abs($vote->value);
                        }
                        $balance['in_person_votes'][$date->format('Y-m-d')] += $vote->value;
                        $total['in_person_votes'][$date->format('Y-m-d')] += abs($vote->value);

                        break;
                    default:
                        if($vote->value > 0){
                            $positive['web_votes'][$date->format('Y-m-d')] += $vote->value;
                        }else{
                            $negative['web_votes'][$date->format('Y-m-d')] += abs($vote->value);
                        }
                        $balance['web_votes'][$date->format('Y-m-d')] += $vote->value;
                        $total['web_votes'][$date->format('Y-m-d')] += abs($vote->value);
                        break;
                }
                if($vote->value > 0){
                    $positive['all_votes'][$date->format('Y-m-d')] += $vote->value;
                }else{
                    $negative['all_votes'][$date->format('Y-m-d')] += abs($vote->value);
                }
                $balance['all_votes'][$date->format('Y-m-d')] += $vote->value;
                $total['all_votes'][$date->format('Y-m-d')] += abs($vote->value);

            }
            $data = ['negative_vote' => $negativeVote,'total' => $total, 'balance' => $balance, 'positive' => $positive, 'negative' => $negative];
            return response()->json(["data" => $data], 200);
        }catch (Exception $e){
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }

    }

    /*Get statistics voters per date*/
    public function voteStatisticsVotersPerDate(Request $request, $eventKey){
        try {
            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');

            $eventInfo = new EventInfo($eventKey,$languageCode,$languageCodeDefault);
            $event = $eventInfo->getEvent();
            $negativeVote = $eventInfo->verifyNegativeVoteExists($event);

            $startDate = $event->start_date;
            $endDate = $event->end_date;

            $dateRange = $this->generateDateRange(Carbon::parse($startDate),Carbon::parse($endDate));
            $allVotes = $eventInfo->getEventVotes()->votes;
            $topicVotesVerification = $eventInfo->verifyValidTopicsAndVotes($allVotes);
            $votes = $topicVotesVerification['votes_filtered']; //get all votes between star_date and end_date

            if ($votes->isEmpty()) {
                $balance = ['in_person_votes' => [],'web_votes' => [], 'all_votes' => []];
                $positive = ['in_person_votes' => [],'web_votes' => [], 'all_votes' => []];
                $negative = ['in_person_votes' => [],'web_votes' => [], 'all_votes' => []];
                $total = ['in_person_votes' => [],'web_votes' => [], 'all_votes' => []];
                $data = ['negative_vote' => $negativeVote,'total' => $total,'balance' => $balance, 'positive' => $positive, 'negative' => $negative];
                return response()->json(["data" => $data], 200);
            }

            $balance = ['in_person_votes' => $dateRange,'web_votes' => $dateRange, 'all_votes' => $dateRange];
            $positive = ['in_person_votes' => $dateRange,'web_votes' => $dateRange, 'all_votes' => $dateRange];
            $negative = ['in_person_votes' => $dateRange,'web_votes' => $dateRange, 'all_votes' => $dateRange];
            $total = ['in_person_votes' => $dateRange,'web_votes' => $dateRange, 'all_votes' => $dateRange];


            foreach($votes as $vote){
                $date = Carbon::parse($vote->created_at);
                if(!array_key_exists($date->format('Y-m-d'),$dateRange)){
                    continue;
                }
                $vote->created_at = $date->format('Y-m-d');
            }

            $votesByDate = $votes->groupBy('created_at');
            $votersPerDay = collect();

            /*filer voters -> without duplicated user_key per day*/
            foreach ($votesByDate as $item){
                $userKeys = [];
                foreach ($item as $voteItem){
                    if (!in_array($voteItem->user_key, $userKeys)){
                        $votersPerDay->push($voteItem);
                        $userKeys[] = $voteItem->user_key;
                    }
                }
            }

            foreach ($votersPerDay as $vote){
                $date = Carbon::parse($vote->created_at);
                if(!array_key_exists($date->format('Y-m-d'),$dateRange)){
                    continue;
                }
                switch ($vote->source)
                {
                    case 'in_person':
                        if($vote->value > 0){
                            $positive['in_person_votes'][$date->format('Y-m-d')] += $vote->value;
                        }else{
                            $negative['in_person_votes'][$date->format('Y-m-d')] += abs($vote->value);
                        }
                        $balance['in_person_votes'][$date->format('Y-m-d')] += $vote->value;
                        $total['in_person_votes'][$date->format('Y-m-d')] += abs($vote->value);

                        break;
                    default:
                        if($vote->value > 0){
                            $positive['web_votes'][$date->format('Y-m-d')] += $vote->value;
                        }else{
                            $negative['web_votes'][$date->format('Y-m-d')] += abs($vote->value);
                        }
                        $balance['web_votes'][$date->format('Y-m-d')] += $vote->value;
                        $total['web_votes'][$date->format('Y-m-d')] += abs($vote->value);
                        break;
                }
                if($vote->value > 0){
                    $positive['all_votes'][$date->format('Y-m-d')] += $vote->value;
                }else{
                    $negative['all_votes'][$date->format('Y-m-d')] += abs($vote->value);
                }
                $balance['all_votes'][$date->format('Y-m-d')] += $vote->value;
                $total['all_votes'][$date->format('Y-m-d')] += abs($vote->value);

            }
            $data = ['negative_vote' => $negativeVote,'total' => $total, 'balance' => $balance, 'positive' => $positive, 'negative' => $negative];

            return response()->json(["data" => $data], 200);
        }catch (Exception $e){
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }

    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/statisticsByTown",
     *  summary="Show vote statistics by town",
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
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Parameter(
     *      name="X-ENTITY-KEY",
     *      in="header",
     *      description="Entity Key",
     *      required=true,
     *      type="string"
     *  ),
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
     *      description="Show vote statistics by town",
     *      @SWG\Schema(ref="#/definitions/votesByTownReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */

    /** Get vote statistics by town and channel
     * @param Request $request
     * @param $eventKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function voteStatisticsByTown(Request $request, $eventKey){

        try {

            // Get data from Event
            $response = ONE::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }
            $inPersonVotes = $webVotes = $allVotes = [];
            //Get all votes
            $response = ONE::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes)) {
                $data = ['in_person_votes' => $inPersonVotes,'web_votes' => $webVotes, 'all_votes' => $allVotes];
                return response()->json(["data" => $data], 200);
            }
            $votes = $response->json()->data->votes;
            $votesKeyByUserKey = collect($votes)->keyBy('user_key')->toArray();
            $usersKeys = array_keys($votesKeyByUserKey);

            $entityKey = $request->header('X-ENTITY-KEY');
            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');
            $authToken = $request->header('X-AUTH-TOKEN');
            $usersInfo = new UsersInfo($authToken,$entityKey,$languageCode, $languageCodeDefault);

            $usersData = $usersInfo->getUsersParameters($usersKeys);
            $parameterKey = $usersInfo->getParameterKey('TOWN');

            foreach ($votes as $vote){
                if(!array_key_exists($vote->user_key,$usersData)){
                    continue;
                }
                if(!array_key_exists($parameterKey,$usersData[$vote->user_key])){
                    continue;
                }
                switch ($vote->source)
                {
                    case 'in_person':
                        if(array_key_exists(mb_strtoupper($usersData[$vote->user_key][$parameterKey]),$inPersonVotes)){
                            $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKey])] += 1;
                        }else{
                            $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKey])] = 1;
                        }

                        break;
                    default:
                        if(array_key_exists(mb_strtoupper($usersData[$vote->user_key][$parameterKey]),$webVotes)){
                            $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKey])] += 1;
                        }else{
                            $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKey])] = 1;
                        }
                        break;
                }
                if(array_key_exists(mb_strtoupper($usersData[$vote->user_key][$parameterKey]),$allVotes)){
                    $allVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKey])] += 1;
                }else{
                    $allVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKey])] = 1;
                }
            }
            $data = ['in_person_votes' => $inPersonVotes,'web_votes' => $webVotes, 'all_votes' => $allVotes];

            return response()->json(["data" => $data], 200);
        }catch (Exception $e){
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }

    }

    public function voteStatisticsTopicParameters(Request $request, $eventKey){
        try {
            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');

            $eventInfo = new EventInfo($eventKey,$languageCode,$languageCodeDefault);
            $event = $eventInfo->getEvent();
            $negativeVote = $eventInfo->verifyNegativeVoteExists($event);

            $startDate = $event->start_date;
            $endDate = $event->end_date;

            $dateRange = $this->generateDateRange(Carbon::parse($startDate),Carbon::parse($endDate));
            $allVotes = $eventInfo->getEventVotes()->votes;
            $topicVotesVerification = $eventInfo->verifyValidTopicsAndVotes($allVotes);
            $votes = $topicVotesVerification['votes_filtered']; //get all votes between star_date and end_date
            $topics = $topicVotesVerification['topic_details']; //get topics with all details with parameters

            $topicFiltered = [];
            foreach (!empty($topics) ? $topics : [] as $topic){
                if(!empty($topic->parameters) ){
                    foreach ($topic->parameters as $parameter){
                        if($parameter->id == $request->paramId){
                            $topicFiltered [] = $topic;
                        }
                    }
                }
            }

            $votesFiltered = [];
            foreach (!empty($votes) ? $votes : [] as $vote){
                foreach ($topicFiltered as $item){
                    if($vote->vote_key == $item->topic_key){
                        $votesFiltered [] = $vote;
                    }
                }
            }

            $votesFiltered = collect($votesFiltered);
            if ($votesFiltered->isEmpty()) {
                $balance = ['in_person_votes' => [],'web_votes' => [], 'all_votes' => []];
                $positive = ['in_person_votes' => [],'web_votes' => [], 'all_votes' => []];
                $negative = ['in_person_votes' => [],'web_votes' => [], 'all_votes' => []];
                $total = ['in_person_votes' => [],'web_votes' => [], 'all_votes' => []];
                $data = ['negative_vote' => $negativeVote,'total' => $total,'balance' => $balance, 'positive' => $positive, 'negative' => $negative];
                return response()->json(["data" => $data], 200);
            }

            $balance = ['in_person_votes' => $dateRange,'web_votes' => $dateRange, 'all_votes' => $dateRange];
            $positive = ['in_person_votes' => $dateRange,'web_votes' => $dateRange, 'all_votes' => $dateRange];
            $negative = ['in_person_votes' => $dateRange,'web_votes' => $dateRange, 'all_votes' => $dateRange];
            $total = ['in_person_votes' => $dateRange,'web_votes' => $dateRange, 'all_votes' => $dateRange];

            foreach ($votesFiltered as $vote){
                $date = Carbon::parse($vote->created_at);
                if(!array_key_exists($date->format('Y-m-d'),$dateRange)){
                    continue;
                }
                switch ($vote->source)
                {
                    case 'in_person':
                        if($vote->value > 0){
                            $positive['in_person_votes'][$date->format('Y-m-d')] += $vote->value;
                        }else{
                            $negative['in_person_votes'][$date->format('Y-m-d')] += abs($vote->value);
                        }
                        $balance['in_person_votes'][$date->format('Y-m-d')] += $vote->value;
                        $total['in_person_votes'][$date->format('Y-m-d')] += abs($vote->value);

                        break;
                    default:
                        if($vote->value > 0){
                            $positive['web_votes'][$date->format('Y-m-d')] += $vote->value;
                        }else{
                            $negative['web_votes'][$date->format('Y-m-d')] += abs($vote->value);
                        }
                        $balance['web_votes'][$date->format('Y-m-d')] += $vote->value;
                        $total['web_votes'][$date->format('Y-m-d')] += abs($vote->value);
                        break;
                }
                if($vote->value > 0){
                    $positive['all_votes'][$date->format('Y-m-d')] += $vote->value;
                }else{
                    $negative['all_votes'][$date->format('Y-m-d')] += abs($vote->value);
                }
                $balance['all_votes'][$date->format('Y-m-d')] += $vote->value;
                $total['all_votes'][$date->format('Y-m-d')] += abs($vote->value);

            }
            $data = ['negative_vote' => $negativeVote,'total' => $total, 'balance' => $balance, 'positive' => $positive, 'negative' => $negative];

            return response()->json(["data" => $data], 200);
        }catch (Exception $e){
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }

    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/statisticsByAge",
     *  summary="Show vote statistics by age",
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
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Parameter(
     *      name="X-ENTITY-KEY",
     *      in="header",
     *      description="Entity Key",
     *      required=true,
     *      type="string"
     *  ),
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
     *      description="Show vote statistics by age",
     *      @SWG\Schema(ref="#/definitions/votesByParameterTownReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */

    /** Get vote statistics by Age, channel and town
     * @param Request $request
     * @param $eventKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function voteStatisticsByAge(Request $request, $eventKey){

        try {

            // Get data from Event
            $response = ONE::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }
            $inPersonVotes = $webVotes = $allVotes = [];
            //Get all votes
            $response = ONE::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }

            if (empty($response->json()->data->votes)) {
                $data = ['in_person_votes' => $inPersonVotes,'web_votes' => $webVotes];
                return response()->json(["data" => $data], 200);
            }
            $votes = $response->json()->data->votes;
            $votesKeyByUserKey = collect($votes)->keyBy('user_key')->toArray();
            $usersKeys = array_keys($votesKeyByUserKey);

            $entityKey = $request->header('X-ENTITY-KEY');
            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');
            $authToken = $request->header('X-AUTH-TOKEN');
            $usersInfo = new UsersInfo($authToken,$entityKey,$languageCode, $languageCodeDefault);

            $usersData = $usersInfo->getUsersParameters($usersKeys);
            $parameterKeyBirthday = $usersInfo->getParameterKey('BIRTHDAY');
            $parameterKeyTown = $usersInfo->getParameterKey('TOWN');

            if(!$parameterKeyBirthday || !$parameterKeyTown){
                $data = ['in_person_votes' => $inPersonVotes,'web_votes' => $webVotes];
                return response()->json(["data" => $data], 200);
            }

            foreach ($votes as $vote){
                if(!array_key_exists($vote->user_key,$usersData)){
                    continue;
                }
                if(!array_key_exists($parameterKeyTown,$usersData[$vote->user_key]) || !array_key_exists($parameterKeyBirthday,$usersData[$vote->user_key])){
                    continue;
                }
                $birthday = Carbon::parse($usersData[$vote->user_key][$parameterKeyBirthday]);
                $now = Carbon::now();
                $age = $birthday->diffInYears($now);
                if($age > 65){
                    $birthdayKey = '+65';
                }elseif ($age > 15 && $age <66){
                    $birthdayKey = '15-65';
                }elseif ($age > 0 && $age < 16){
                    $birthdayKey = '0-14';
                }else{
                    continue;
                }
                switch ($vote->source)
                {
                    case 'in_person':
                        if(array_key_exists(mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown]),$inPersonVotes)){
                            $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$birthdayKey] += 1;
                        }else{
                            $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])]['0-14'] = 0;
                            $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])]['15-65'] = 0;
                            $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])]['+65'] = 0;
                            $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$birthdayKey] = 1;
                        }

                        break;
                    default:
                        if(array_key_exists(mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown]),$webVotes)){
                            $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$birthdayKey] += 1;
                        }else{
                            $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])]['0-14'] = 0;
                            $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])]['15-65'] = 0;
                            $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])]['+65'] = 0;
                            $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$birthdayKey] = 1;
                        }
                        break;
                }
            }
            $data = ['in_person_votes' => $inPersonVotes,'web_votes' => $webVotes];

            return response()->json(["data" => $data], 200);
        }catch (Exception $e){
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }

    }


    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/statisticsByGender",
     *  summary="Show vote statistics by gender",
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
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Parameter(
     *      name="X-ENTITY-KEY",
     *      in="header",
     *      description="Entity Key",
     *      required=true,
     *      type="string"
     *  ),
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
     *      description="Show vote statistics by age",
     *      @SWG\Schema(ref="#/definitions/votesByParameterTownReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */


    /** Get vote statistics by Gender, channel and town
     * @param Request $request
     * @param $eventKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function voteStatisticsByGender(Request $request, $eventKey){

        try {

            // Get data from Event
            $response = ONE::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }
            $inPersonVotes = $webVotes = $allVotes = [];
            //Get all votes
            $response = ONE::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }


            if (empty($response->json()->data->votes)) {
                $data = ['in_person_votes' => $inPersonVotes,'web_votes' => $webVotes];
                return response()->json(["data" => $data], 200);
            }
            $votes = $response->json()->data->votes;
            $votesKeyByUserKey = collect($votes)->keyBy('user_key')->toArray();
            $usersKeys = array_keys($votesKeyByUserKey);

            $entityKey = $request->header('X-ENTITY-KEY');
            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');
            $authToken = $request->header('X-AUTH-TOKEN');
            $usersInfo = new UsersInfo($authToken,$entityKey,$languageCode, $languageCodeDefault);

            $usersData = $usersInfo->getUsersParameters($usersKeys);
            $parameterGender = $usersInfo->getParameterKey('GENDER');
            $parameterKeyTown = $usersInfo->getParameterKey('TOWN');

            $parameterKeyGender = isset($parameterGender['parameter_key'])? $parameterGender['parameter_key'] : null;
            $parameterOptions = isset($parameterGender['parameter_options'])? $parameterGender['parameter_options'] : null;


            if(!$parameterKeyGender || !$parameterKeyTown || empty($parameterOptions)){
                $data = ['in_person_votes' => $inPersonVotes,'web_votes' => $webVotes];
                return response()->json(["data" => $data], 200);
            }

            foreach ($votes as $vote){
                if(!array_key_exists($vote->user_key,$usersData)){
                    continue;
                }
                if(!array_key_exists($parameterKeyTown,$usersData[$vote->user_key]) || !array_key_exists($parameterKeyGender,$usersData[$vote->user_key])){
                    continue;
                }
                $userGenderKey = $usersData[$vote->user_key][$parameterKeyGender];
                if(!array_key_exists($userGenderKey,$parameterOptions)){
                    continue;
                }
                $userGender = isset($parameterOptions[$userGenderKey]->name) ? $parameterOptions[$userGenderKey]->name : null;
                if(!$userGender){
                    continue;
                }
                switch ($vote->source)
                {
                    case 'in_person':
                        if(array_key_exists(mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown]),$inPersonVotes)){
                            $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$userGender] += 1;
                        }else{
                            foreach ($parameterOptions as $option){
                                if(isset($option->name)) {
                                    $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$option->name] = 0;
                                }
                            }
                            $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$userGender] = 1;
                        }
                        break;
                    default:
                        if(array_key_exists(mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown]),$webVotes)){
                            $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$userGender] += 1;
                        }else{
                            foreach ($parameterOptions as $option){
                                if(isset($option->name)) {
                                    $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$option->name] = 0;
                                }
                            }
                            $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$userGender] = 1;
                        }
                        break;
                }
            }
            $data = ['in_person_votes' => $inPersonVotes,'web_votes' => $webVotes];

            return response()->json(["data" => $data], 200);
        }catch (Exception $e){
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }

    }

    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/statisticsByProfession",
     *  summary="Show vote statistics by profession",
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
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Parameter(
     *      name="X-ENTITY-KEY",
     *      in="header",
     *      description="Entity Key",
     *      required=true,
     *      type="string"
     *  ),
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
     *      description="Show vote statistics by profession",
     *      @SWG\Schema(ref="#/definitions/votesByParameterTownReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */

    /** Get vote statistics by Profession, channel and town
     * @param Request $request
     * @param $eventKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function voteStatisticsByProfession(Request $request, $eventKey){

        try {

            // Get data from Event
            $response = ONE::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }
            $inPersonVotes = $webVotes = $allVotes = [];
            //Get all votes
            $response = ONE::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }


            if (empty($response->json()->data->votes)) {
                $data = ['in_person_votes' => $inPersonVotes,'web_votes' => $webVotes];
                return response()->json(["data" => $data], 200);
            }
            $votes = $response->json()->data->votes;
            $votesKeyByUserKey = collect($votes)->keyBy('user_key')->toArray();
            $usersKeys = array_keys($votesKeyByUserKey);

            $entityKey = $request->header('X-ENTITY-KEY');
            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');
            $authToken = $request->header('X-AUTH-TOKEN');
            $usersInfo = new UsersInfo($authToken,$entityKey,$languageCode, $languageCodeDefault);

            $usersData = $usersInfo->getUsersParameters($usersKeys);
            $parameterProfession = $usersInfo->getParameterKey('PROFESSION');
            $parameterKeyTown = $usersInfo->getParameterKey('TOWN');

            $parameterKeyProfession = isset($parameterProfession['parameter_key'])? $parameterProfession['parameter_key'] : null;
            $parameterOptions = isset($parameterProfession['parameter_options'])? $parameterProfession['parameter_options'] : null;


            if(!$parameterKeyProfession || !$parameterKeyTown || empty($parameterOptions)){
                $data = ['in_person_votes' => $inPersonVotes,'web_votes' => $webVotes];
                return response()->json(["data" => $data], 200);
            }

            foreach ($votes as $vote){
                if(!array_key_exists($vote->user_key,$usersData)){
                    continue;
                }
                if(!array_key_exists($parameterKeyTown,$usersData[$vote->user_key]) || !array_key_exists($parameterKeyProfession,$usersData[$vote->user_key])){
                    continue;
                }
                $userProfessionKey = $usersData[$vote->user_key][$parameterKeyProfession];
                if(!array_key_exists($userProfessionKey,$parameterOptions)){
                    continue;
                }
                $userProfession = isset($parameterOptions[$userProfessionKey]->name) ? $parameterOptions[$userProfessionKey]->name : null;
                if(!$userProfession){
                    continue;
                }
                switch ($vote->source)
                {
                    case 'in_person':
                        if(array_key_exists(mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown]),$inPersonVotes)){
                            $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$userProfession] += 1;
                        }else{
                            foreach ($parameterOptions as $option){
                                if(isset($option->name)) {
                                    $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$option->name] = 0;
                                }
                            }
                            $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$userProfession] = 1;
                        }
                        break;
                    default:
                        if(array_key_exists(mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown]),$webVotes)){
                            $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$userProfession] += 1;
                        }else{
                            foreach ($parameterOptions as $option){
                                if(isset($option->name)) {
                                    $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$option->name] = 0;
                                }
                            }
                            $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$userProfession] = 1;
                        }
                        break;
                }
            }
            $data = ['in_person_votes' => $inPersonVotes,'web_votes' => $webVotes];

            return response()->json(["data" => $data], 200);
        }catch (Exception $e){
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }

    }


    /**
     * @SWG\Get(
     *  path="/voteEvent/{event_key}/statisticsByEducation",
     *  summary="Show vote statistics by education",
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
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Parameter(
     *      name="X-ENTITY-KEY",
     *      in="header",
     *      description="Entity Key",
     *      required=true,
     *      type="string"
     *  ),
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
     *      description="Show vote statistics by education",
     *      @SWG\Schema(ref="#/definitions/votesByParameterTownReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */


    /** Get vote statistics by Educational qualifications, channel and town
     * @param Request $request
     * @param $eventKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function voteStatisticsByEducation(Request $request, $eventKey){

        try {

            // Get data from Event
            $response = ONE::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }
            $inPersonVotes = $webVotes = $allVotes = [];
            //Get all votes
            $response = ONE::get([
                    'component' => 'vote',
                    'api' => 'event',
                    'api_attribute' => $eventKey,
                    'method' => 'votes'
                ]
            );

            if ($response->statusCode() != 200) {
                throw new Exception();
            }


            if (empty($response->json()->data->votes)) {
                $data = ['in_person_votes' => $inPersonVotes,'web_votes' => $webVotes];
                return response()->json(["data" => $data], 200);
            }
            $votes = $response->json()->data->votes;
            $votesKeyByUserKey = collect($votes)->keyBy('user_key')->toArray();
            $usersKeys = array_keys($votesKeyByUserKey);

            $entityKey = $request->header('X-ENTITY-KEY');
            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');
            $authToken = $request->header('X-AUTH-TOKEN');
            $usersInfo = new UsersInfo($authToken,$entityKey,$languageCode, $languageCodeDefault);

            $usersData = $usersInfo->getUsersParameters($usersKeys);
            $parameterEducation = $usersInfo->getParameterKey('EDUCATIONALQUALIFICATIONS');
            $parameterKeyTown = $usersInfo->getParameterKey('TOWN');

            $parameterKeyEducation = isset($parameterEducation['parameter_key'])? $parameterEducation['parameter_key'] : null;
            $parameterOptions = isset($parameterEducation['parameter_options'])? $parameterEducation['parameter_options'] : null;


            if(!$parameterKeyEducation || !$parameterKeyTown || empty($parameterOptions)){
                $data = ['in_person_votes' => $inPersonVotes,'web_votes' => $webVotes];
                return response()->json(["data" => $data], 200);
            }

            foreach ($votes as $vote){
                if(!array_key_exists($vote->user_key,$usersData)){
                    continue;
                }
                if(!array_key_exists($parameterKeyTown,$usersData[$vote->user_key]) || !array_key_exists($parameterKeyEducation,$usersData[$vote->user_key])){
                    continue;
                }
                $userEducationKey = $usersData[$vote->user_key][$parameterKeyEducation];
                if(!array_key_exists($userEducationKey,$parameterOptions)){
                    continue;
                }
                $userEducation = isset($parameterOptions[$userEducationKey]->name) ? $parameterOptions[$userEducationKey]->name : null;
                if(!$userEducation){
                    continue;
                }
                switch ($vote->source)
                {
                    case 'in_person':
                        if(array_key_exists(mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown]),$inPersonVotes)){
                            $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$userEducation] += 1;
                        }else{
                            foreach ($parameterOptions as $option){
                                if(isset($option->name)) {
                                    $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$option->name] = 0;
                                }
                            }
                            $inPersonVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$userEducation] = 1;
                        }
                        break;
                    default:
                        if(array_key_exists(mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown]),$webVotes)){
                            $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$userEducation] += 1;
                        }else{
                            foreach ($parameterOptions as $option){
                                if(isset($option->name)) {
                                    $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$option->name] = 0;
                                }
                            }
                            $webVotes[mb_strtoupper($usersData[$vote->user_key][$parameterKeyTown])][$userEducation] = 1;
                        }
                        break;
                }
            }
            $data = ['in_person_votes' => $inPersonVotes,'web_votes' => $webVotes];

            return response()->json(["data" => $data], 200);
        }catch (Exception $e){
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }

    }


    /**
     * @SWG\Post(
     *  path="/voteEvent/{event_key}/statisticsByTop",
     *  summary="Show vote statistics by Top topic",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *
     *
     *  @SWG\Parameter(
     *      name="top",
     *      in="body",
     *      description="Top to show",
     *      required=true,
     *      @SWG\Schema(ref="#/definitions/topStatistics")
     *  ),
     *
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show vote statistics by Top topic",
     *      @SWG\Schema(ref="#/definitions/voteByTopReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */

    /** Get vote statistics by top topics
     * @param Request $request
     * @param $eventKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function voteStatisticsByTop(Request $request, $eventKey)
    {

        try {
            $top = $request->json('top');
            if(empty($top) || $top < 1){
                $top = 10;
            }

            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');

            $eventInfo = new EventInfo($eventKey,$languageCode,$languageCodeDefault);

            $event = $eventInfo->getEvent();

            $negativeVote = $eventInfo->verifyNegativeVoteExists($event);
            $allVotes = $eventInfo->getEventVotes()->votes;

            /* ------ Check valid votes with existent topics ------ */
            $topicVotesVerification = $eventInfo->verifyValidTopicsAndVotes($allVotes);
            $topicsInfo = collect($topicVotesVerification['topic_details'])->pluck('title','topic_key');
            $votes = $topicVotesVerification['votes_filtered'];
            $topVotes = ['negative_vote' => $negativeVote, 'total' => [],'balance' => [], 'positive' => [], 'negative' => []];

            if ($votes->isEmpty()) {
                return response()->json(["data" => $topVotes], 200);
            }
            $votesSummary = $eventInfo->getTopVotesSum($votes,$top);

            $topicKeysCountTotal = $votesSummary['topic_keys_total'];
            $topicKeysCountBalance = $votesSummary['topic_keys_balance'];
            $topicKeysCountPositive = $votesSummary['topic_keys_positive'];
            $topicKeysCountNegative = $votesSummary['topic_keys_negative'];

            /* ------ Get top votes by types ------ */
            $topVotes['total'] = $eventInfo->getTopVotesDetails($topicKeysCountTotal,$topicsInfo);
            $topVotes['balance'] = $eventInfo->getTopVotesDetails($topicKeysCountBalance,$topicsInfo);
            $topVotes['positive'] = $eventInfo->getTopVotesDetails($topicKeysCountPositive,$topicsInfo);
            $topVotes['negative'] = $eventInfo->getTopVotesDetails($topicKeysCountNegative,$topicsInfo);

            return response()->json(["data" => $topVotes], 200);
        }catch (Exception $e){
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }

    }


    /**
     * @SWG\Post(
     *  path="/voteEvent/{event_key}/statisticsTopByDate",
     *  summary="Show vote statistics by Top topic and Date",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *
     *  @SWG\Parameter(
     *      name="top",
     *      in="body",
     *      description="Top to show",
     *      required=true,
     *      @SWG\Schema(ref="#/definitions/topStatistics")
     *  ),
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Parameter(
     *      name="X-MODULE-TOKEN",
     *      in="header",
     *      description="Module Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show vote statistics by Top topic and Date",
     *      @SWG\Schema(ref="#/definitions/voteByTopDateReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */


    /** Get vote statistics by top topics
     * @param Request $request
     * @param $eventKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function voteStatisticsTopByDate(Request $request, $eventKey)
    {

        try {
            $top = $request->json('top');
            if(empty($top) || $top < 1){
                $top = 3;
            }

            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');

            $eventInfo = new EventInfo($eventKey,$languageCode,$languageCodeDefault);

            $event = $eventInfo->getEvent();
            $negativeVote = $eventInfo->verifyNegativeVoteExists($event);

            $startDate = $event->start_date;
            $endDate = $event->end_date;

            /* ------ Generate date range between start and end date of vote event ------ */
            $dateRange = $this->generateDateRange(Carbon::parse($startDate),Carbon::parse($endDate));

            $topVotes = ['negative_vote' => $negativeVote,'total' => [],'balance' => [], 'positive' => [], 'negative' => []];
            $allVotes = $eventInfo->getEventVotes()->votes;

            /* ------ Check valid votes with existent topics ------ */
            $topicVotesVerification = $eventInfo->verifyValidTopicsAndVotes($allVotes);
            $topicsInfo = collect($topicVotesVerification['topic_details'])->pluck('title','topic_key');
            $votes = $topicVotesVerification['votes_filtered'];

            if ($votes->isEmpty()) {
                return response()->json(["data" => $topVotes], 200);
            }
            $votesSummary = $eventInfo->getTopVotesSum($votes,$top);

            $votesTotalSum = $votesSummary['topic_keys_total']->keys()->toArray();
            $votesBalanceSum = $votesSummary['topic_keys_balance']->keys()->toArray();
            $votesPositiveSum = $votesSummary['topic_keys_positive']->keys()->toArray();
            $votesNegativeSum = $votesSummary['topic_keys_negative']->keys()->toArray();

            /* ------ Get votes that has the topics keys ------ */
            $topicKeysCountTotal = $eventInfo->getVotesInArrayKeys($votes,$votesTotalSum);
            $topicKeysCountBalance = $eventInfo->getVotesInArrayKeys($votes,$votesBalanceSum);
            $topicKeysCountPositive = $eventInfo->getVotesInArrayKeys($votes,$votesPositiveSum);
            $topicKeysCountNegative = $eventInfo->getVotesInArrayKeys($votes,$votesNegativeSum);

            /* ------ Get top votes by date and types ------ */
            $topVotes['total'] = $eventInfo->getTopVotesByDate($topicKeysCountTotal,$dateRange,$topicsInfo,true);
            $topVotes['balance'] = $eventInfo->getTopVotesByDate($topicKeysCountBalance,$dateRange,$topicsInfo);
            $topVotes['positive'] = $eventInfo->getTopVotesByDate($topicKeysCountPositive,$dateRange,$topicsInfo);
            $topVotes['negative'] = $eventInfo->getTopVotesByDate($topicKeysCountNegative,$dateRange,$topicsInfo);

            return response()->json(["data" => $topVotes], 200);
        }catch (Exception $e){
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }

    }


    /**
     * @SWG\Post(
     *  path="/voteEvent/{event_key}/statisticsByParameter",
     *  summary="Show vote statistics by parameter",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Vote Statistics"},
     *
     *
     *  @SWG\Parameter(
     *      name="Parameter Key",
     *      in="body",
     *      description="Parameter to show statistics",
     *      required=true,
     *      @SWG\Schema(ref="#/definitions/parameterStatistics")
     *  ),
     *
     *  @SWG\Parameter(
     *      name="event_key",
     *      in="path",
     *      description="Event Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Parameter(
     *      name="X-AUTH-TOKEN",
     *      in="header",
     *      description="User Auth Token",
     *      required=true,
     *      type="string"
     *  ),
     *
     *  @SWG\Parameter(
     *      name="X-ENTITY-KEY",
     *      in="header",
     *      description="Entity Key",
     *      required=true,
     *      type="string"
     *  ),
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
     *  @SWG\Response(
     *      response="200",
     *      description="Show vote statistics by Top topic and Date",
     *      @SWG\Schema(ref="#/definitions/voteByParameterReply")
     *  ),
     *
     *  @SWG\Response(
     *      response="400",
     *      description="Error trying to retrieve data",
     *      @SWG\Schema(ref="#/definitions/analyticsErrorDefault")
     *  )
     * )
     */


    /** Get vote statistics by parameter
     * @param Request $request
     * @param $eventKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function voteStatisticsByParameter(Request $request, $eventKey)
    {

        try {
            $parameterKey = $request->json('parameter_key');
            if(empty($parameterKey)){
                throw new Exception('parameter_key_required');
            }


            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');

            $eventInfo = new EventInfo($eventKey,$languageCode,$languageCodeDefault);
            $event = $eventInfo->getEvent();
            $negativeVote = $eventInfo->verifyNegativeVoteExists($event);

            $votesByParameter = ['negative_vote' => $negativeVote,'total' => [],'balance' => [], 'positive' => [], 'negative' => []];

            $data = [];
            $data['statistics_by_topic'] = [];
            $data['statistics_by_parameter'] = $votesByParameter;
            $data['count_by_parameter'] = [];
            $data['first_by_parameter'] = [];
            $data['second_by_parameter'] = [];
            $data['parameters_options'] = [];


            $allVotes = $eventInfo->getEventVotes()->votes;
            /* ------- Get array with topics details and a collection of votes ------- */
            $topicVotesVerification = $eventInfo->verifyValidTopicsAndVotes($allVotes);
            $votes = $topicVotesVerification['votes_filtered'];
            $topics = $topicVotesVerification['topic_details'];

            /* ------ Data Headers to send in request's ------ */
            $usersKeys = $votes->keyBy('user_key')->keys()->toArray();
            $entityKey = $request->header('X-ENTITY-KEY');
            $authToken = $request->header('X-AUTH-TOKEN');

            /* ------ User Information with Parameters ------ */
            $usersInfo = new UsersInfo($authToken,$entityKey,$languageCode, $languageCodeDefault);
            $usersData = $usersInfo->getUsersParameters($usersKeys);

            /* ------- Get Parameter Options ------- */
            $parameterAndOptions = $usersInfo->getParameterAndOptions($parameterKey);
            $parameter = $parameterAndOptions['user_parameter'];

            $secondParameterKey = $request->json('second_parameter_key');
            $thirdParameterKey = $request->json('third_parameter_key');

            /** ----------- If parameter is Birthday define intervals ----------- */
            if($parameter->parameter_type->code == 'birthday'){
                $ageValue = $request->json('age_value');
                if(empty($ageValue)){
                    $ageValue = '90';
                }
                $data = $this->getVoteStatisticsByBirthday($usersInfo,$usersData,$parameterKey,$secondParameterKey,$thirdParameterKey,$votes,$topics,$ageValue);
                return response()->json(["data" => $data], 200);

            }

            $parameterOptions = $parameterAndOptions['user_parameter_options'];
            if( $parameterOptions->isEmpty()){
                return response()->json(["data" => $data], 200);
            }

            /* ------ Map the parameter options name with 0 count ------ */
            $parameterOptionsValues = $parameterOptions->mapWithKeys(function ($parameter) {
                return [$parameter->name => 0];
            })->toArray();




            //TODO................

            /** ------------------ Get Data from Commuters - for empaville ------------------ */
            if($parameter->parameter_type->code == 'neighborhood'){
                /* ------ Filter User with Parameter and map it with "user key => parameter key" ------ */
                $usersWithParameter = collect($usersData)->filter(function($user) use($parameterKey,$parameterOptions){
                    if(array_key_exists($parameterKey,$user) && $parameterOptions->has($user[$parameterKey])) {
                        $neighborhoodName = $parameterOptions->get($user[$parameterKey])->name ?? '';
                        if($neighborhoodName == 'Commuter'){
                            return true;
                        }
                    }
                    return false;
                })->map(function($parameter) use ($parameterKey){
                    return $parameter[$parameterKey];
                });


                if(!$usersWithParameter->isEmpty()){

                    /* ------ Filter votes by user's with parameter ------ */
                    $votesFiltered = $votes->filter(function($vote) use($usersWithParameter){
                        return $usersWithParameter->has($vote->user_key);
                    })->toArray();

                    $commutersData = $this->getCommutersStatistics($topics,$votesFiltered);
                    $data['commuters_statistics'] = $commutersData;
                }

            }

            /* ------ Filter User with Parameter and map it with "user key => parameter key" ------ */
            $usersWithParameter = collect($usersData)->filter(function($user) use($parameterKey,$parameterOptions){
                if(array_key_exists($parameterKey,$user) && $parameterOptions->has($user[$parameterKey])) {
                    return true;
                }
                return false;
            })->map(function($parameter) use ($parameterKey){
                return $parameter[$parameterKey];
            });


            if($usersWithParameter->isEmpty()){
                return response()->json(["data" => $data], 200);
            }

            /** Verify second parameter type and get population by second parameter and parameter requested */
            if(!empty($secondParameterKey)){

                $secondParameterAndOptionsData = $usersInfo->getParameterAndOptions($secondParameterKey);
                $secondParameterOptions = $secondParameterAndOptionsData['user_parameter_options'];

                /* ------ Filter User with Parameter and map it with "user key => parameter key" ------ */
                $usersWithTwoParameters = collect($usersData)->filter(function($user) use($parameterKey,$secondParameterKey,$parameterOptions,$secondParameterOptions){
                    if(array_key_exists($parameterKey,$user) && $parameterOptions->has($user[$parameterKey]) && array_key_exists($secondParameterKey,$user) && $secondParameterOptions->has($user[$secondParameterKey])) {
                        return true;
                    }
                    return false;
                })->map(function($parameter) use ($parameterKey,$secondParameterKey){
                    return ['parameter_key' => $parameter[$parameterKey],'second_parameter_key' => $parameter[$secondParameterKey]];
                });

                /* ------ Filter votes by user's with parameter ------ */
                $votesFiltered = $votes->filter(function($vote) use($usersWithTwoParameters){
                    return $usersWithTwoParameters->has($vote->user_key);
                })->toArray();

                /* ------ Map the parameter options name with 0 count ------ */
                $secondParameterOptionsValues = $secondParameterOptions->mapWithKeys(function ($secondParameter) use($parameterOptionsValues) {
                        return [$secondParameter->name => $parameterOptionsValues];
                })->toArray();


                /** Get Vote Population By parameter*/
                $votePopulationByTwoParameters = $secondParameterOptionsValues;
                $votesDistinctUsers = collect($votesFiltered)->keyBy('user_key')->toArray();
                foreach ($votesDistinctUsers as $vote){
                    if($usersWithTwoParameters->has($vote->user_key) && isset($usersWithTwoParameters->get($vote->user_key)['parameter_key']) && $parameterOptions->has($usersWithTwoParameters->get($vote->user_key)['parameter_key'])
                        && isset($usersWithTwoParameters->get($vote->user_key)['second_parameter_key']) && $secondParameterOptions->has($usersWithTwoParameters->get($vote->user_key)['second_parameter_key']) ) {
                        $optionName = $parameterOptions->get($usersWithTwoParameters->get($vote->user_key)['parameter_key'])->name ?? null;

                        $secondOptionName = $secondParameterOptions->get($usersWithTwoParameters->get($vote->user_key)['second_parameter_key'])->name ?? null;
                        if(empty($optionName) || empty($secondOptionName)){
                            continue;
                        }

                        $votePopulationByTwoParameters[$secondOptionName][$optionName] += 1;
                    }
                }
                $data['vote_population_two_parameters'] = $votePopulationByTwoParameters ?? [];

            }



            /* ------ Filter votes by user's with parameter ------ */
            $votesFiltered = $votes->filter(function($vote) use($usersWithParameter){
                return $usersWithParameter->has($vote->user_key);
            })->toArray();


            /** Get Vote Population By parameter*/
            $votePopulation = $parameterOptionsValues;
            $votesDistinctUsers = collect($votesFiltered)->keyBy('user_key')->toArray();
            foreach ($votesDistinctUsers as $vote){
                if($usersWithParameter->has($vote->user_key) && $parameterOptions->has($usersWithParameter->get($vote->user_key))) {

                        $optionName = $parameterOptions->get($usersWithParameter->get($vote->user_key))->name ?? null;
                    if(empty($optionName)){
                        continue;
                    }
                    $votePopulation[$optionName] += 1;
                }
            }


            $votesByParameter = ['negative_vote' => $negativeVote,'total' => $parameterOptionsValues,'balance' => $parameterOptionsValues, 'positive' => $parameterOptionsValues, 'negative' => $parameterOptionsValues];


            $parametersOptions = [];
            /* ------ Count votes by user option and value ------ */
            foreach ($votesFiltered as $vote){
                if($usersWithParameter->has($vote->user_key) && $parameterOptions->has($usersWithParameter->get($vote->user_key))){
                        $optionName = $parameterOptions->get($usersWithParameter->get($vote->user_key))->name ?? null;
                    if(empty($optionName)){
                        continue;
                    }

                    if (!in_array($optionName, $parametersOptions)) {
                        $parametersOptions[] = $optionName;
                    }

                    if($vote->value > 0){
                        $votesByParameter['positive'][$optionName] += $vote->value;

                    }else{
                        $votesByParameter['negative'][$optionName] += $vote->value;
                    }
                    $votesByParameter['total'][$optionName] += abs($vote->value);
                    $votesByParameter['balance'][$optionName] += $vote->value;
                }
            }


            /** Votes by Topic and parameter */

            $votesByTopicAndParameter = $this->getVotesByTopicAndParameter($topics,$votesFiltered,$usersWithParameter,$parameterOptions);

            /** Count votes by parameter, first and second votes */
            $countByParameter = $this->countVotesByParameter($votesFiltered,$usersWithParameter,$parameterOptions);

            $votesByTopicAndParameter = collect($votesByTopicAndParameter)->sortByDesc('total');

            $data['statistics_by_topic'] = $votesByTopicAndParameter;
            $data['statistics_by_parameter'] = $votesByParameter;
            $data['count_by_parameter'] = $countByParameter['count_by_parameter'] ?? [];
            $data['first_by_parameter'] = $countByParameter['first_by_parameter'] ?? [];
            $data['second_by_parameter'] = $countByParameter['second_by_parameter'] ?? [];
            $data['parameters_options'] = $parametersOptions ?? [];
            $data['vote_population'] = $votePopulation ?? [];

            return response()->json(["data" => $data], 200);
        }catch (Exception $e){
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }

    }



    public function voteStatisticsLastDay(Request $request, $eventKey)
    {

        try {
            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');

            $eventInfo = new EventInfo($eventKey,$languageCode,$languageCodeDefault);

            $event = $eventInfo->getEvent();
            $negativeVote = $eventInfo->verifyNegativeVoteExists($event);

            $today = $now = Carbon::now()->toDateString();

            $hoursVote = ['positive' => [] ,'negative' => [], 'total' => [],'balance' =>[]];

            $allVotes = $eventInfo->getEventVotes()->votes;
            $topicVotesVerification = $eventInfo->verifyValidTopicsAndVotes($allVotes);
            $votes = $topicVotesVerification['votes_filtered'];

            if ($votes->isEmpty()) {
                return response()->json(["data" => $hoursVote], 200);
            }
            $votes = $votes->filter(function($vote) use($today){
                $voteDate = Carbon::parse($vote->created_at)->toDateString();
                return $voteDate == $today;
            });

            if ($votes->isEmpty()) {
                return response()->json(["data" => $hoursVote], 200);
            }


            $keys = ['00h','01h','02h','03h','04h','05h','06h','07h','08h','09h','10h','11h',
                '12h','13h','14h','15h','16h','17h','18h','19h','20h','21h','22h','23h'];
            $hoursVote['positive'] = array_fill_keys($keys, 0);
            $hoursVote['negative'] = array_fill_keys($keys, 0);
            $hoursVote['balance']  = array_fill_keys($keys, 0);
            $hoursVote['total']= array_fill_keys($keys, 0);



            foreach ($votes as $vote){
                $date = Carbon::parse($vote->created_at);

                if($vote->value > 0){
                    $hoursVote['positive'][str_pad($date->hour, 2, 0, STR_PAD_LEFT).'h'] += $vote->value;
                }else{
                    $hoursVote['negative'][str_pad($date->hour, 2, 0, STR_PAD_LEFT).'h'] += abs($vote->value);
                }
                $hoursVote['balance'][str_pad($date->hour, 2, 0, STR_PAD_LEFT).'h'] += $vote->value;
                $hoursVote['total'][str_pad($date->hour, 2, 0, STR_PAD_LEFT).'h'] += abs($vote->value);
            }

            return response()->json(["data" => $hoursVote ], 200);
        }catch (Exception $e){
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }

    }


    /** Get Commuters Votes by Empaville geo area
     * @param $topics
     * @param $votes
     * @return array
     */
    private function getCommutersStatistics($topics, $votes)
    {
        //$votesByCommuters['geo_area'] = [];
        $votesByCommuters['geo_area']['Uptown'] = ['positive' => 0,'negative' => 0,'total' => 0, 'balance' => 0];
        $votesByCommuters['geo_area']['Midtown'] = ['positive' => 0,'negative' => 0,'total' => 0, 'balance' => 0];
        $votesByCommuters['geo_area']['Downtown'] = ['positive' => 0,'negative' => 0,'total' => 0, 'balance' => 0];
        foreach ($topics as $topic) {
            $geoArea = null;

            foreach ($topic->parameters as $value) {
                if ($value->code === 'image_map') {
                    $geoArea = ONE::verifyEmpavilleGeoArea($value->pivot->value);
                    if(empty($votesByCommuters['geo_area'][$geoArea])){
                        $votesByCommuters['geo_area'][$geoArea] = ['positive' => 0,'negative' => 0,'total' => 0, 'balance' => 0];
                    }
                }
            }
            if(empty($geoArea)){
                continue;
            }
            foreach ($votes as $vote) {
                if($vote->vote_key == $topic->topic_key){

                    if($vote->value > 0){
                        $votesByCommuters['geo_area'][$geoArea]['positive'] += $vote->value;

                    }else{
                        $votesByCommuters['geo_area'][$geoArea]['negative'] += abs($vote->value);
                    }
                    $votesByCommuters['geo_area'][$geoArea]['total'] += abs($vote->value);
                    $votesByCommuters['geo_area'][$geoArea]['balance'] += $vote->value;
                }
            }
        }
        return $votesByCommuters['geo_area'];

    }






    /** Get votes by topic with parameters associated
     * @param $topics
     * @param $votes
     * @param $usersWithParameter
     * @param $parameterOptions
     * @return array
     */
    private function getVotesByTopicAndParameter($topics, $votes, $usersWithParameter, $parameterOptions)
    {
        $votesByTopicAndParameter = [];
        foreach ($topics as $topic) {
            $dataTemp['title'] = $topic->title;
            $dataTemp['budget'] = 0;
            $dataTemp['category'] = "";
            $dataTemp['geo_area'] = "";
            $dataTemp['parameter_options'] = [];
            $dataTemp['total'] = 0;


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

            foreach ($votes as $vote) {
                if($vote->vote_key == $topic->topic_key && $usersWithParameter->has($vote->user_key) && $parameterOptions->has($usersWithParameter->get($vote->user_key))){

                        $optionName = $parameterOptions->get($usersWithParameter->get($vote->user_key))->name ?? null;
                    if(empty($optionName)){
                        continue;
                    }
                    if (empty($dataTemp['parameter_options'][$optionName])) {
                        $dataTemp['parameter_options'][$optionName]['positive'] = 0;
                        $dataTemp['parameter_options'][$optionName]['negative'] = 0;
                        $dataTemp['parameter_options'][$optionName]['balance'] = 0;
                        $dataTemp['parameter_options'][$optionName]['total'] = 0;
                    }

                    if($vote->value > 0){
                        $dataTemp['parameter_options'][$optionName]['positive'] += $vote->value;

                    }else{
                        $dataTemp['parameter_options'][$optionName]['negative'] += abs($vote->value);
                    }

                    $dataTemp['parameter_options'][$optionName]['total']  += abs($vote->value);
                    $dataTemp['parameter_options'][$optionName]['balance']  += $vote->value;


                    $dataTemp['total'] += $vote->value;
                }
            }
            $votesByTopicAndParameter[] = $dataTemp;
        }

        return $votesByTopicAndParameter;


    }




    /** Get votes by topic with parameters associated
     * @param $topics
     * @param $votes
     * @param $usersWithParameter
     * @return array
     */
    private function getVotesByTopicAndBirthday($topics, $votes, $usersWithParameter)
    {
        $votesByTopicAndParameter = [];
        foreach ($topics as $topic) {
            $dataTemp['title'] = $topic->title;
            $dataTemp['budget'] = 0;
            $dataTemp['category'] = "";
            $dataTemp['geo_area'] = "";
            $dataTemp['parameter_options'] = [];
            $dataTemp['total'] = 0;


            foreach ($topic->parameters as $value) {
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
                if($vote->vote_key == $topic->topic_key && $usersWithParameter->has($vote->user_key)){
                    $age = Carbon::parse($usersWithParameter->get($vote->user_key))->diffInYears(Carbon::now());
                    if ($age < 20) {
                        $ageInterval = '0-19';
                    } elseif ($age < 30) {
                        $ageInterval = '20-29';
                    } elseif ($age < 40) {
                        $ageInterval = '30-39';
                    } elseif ($age < 50) {
                        $ageInterval = '40-49';
                    } elseif ($age < 60) {
                        $ageInterval = '50-59';
                    } elseif ($age < 70) {
                        $ageInterval = '60-69';
                    } else {
                        $ageInterval = '70+';
                    }


                    if (empty($dataTemp['parameter_options'][$ageInterval])) {
                        $dataTemp['parameter_options'][$ageInterval]['positive'] = 0;
                        $dataTemp['parameter_options'][$ageInterval]['negative'] = 0;
                        $dataTemp['parameter_options'][$ageInterval]['balance'] = 0;
                        $dataTemp['parameter_options'][$ageInterval]['total'] = 0;
                    }

                    if($vote->value > 0){
                        $dataTemp['parameter_options'][$ageInterval]['positive'] += $vote->value;

                    }else{
                        $dataTemp['parameter_options'][$ageInterval]['negative'] += abs($vote->value);
                    }

                    $dataTemp['parameter_options'][$ageInterval]['total']  += abs($vote->value);
                    $dataTemp['parameter_options'][$ageInterval]['balance']  += $vote->value;


                    $dataTemp['total'] += $vote->value;
                }
            }
            $votesByTopicAndParameter[] = $dataTemp;
        }

        return $votesByTopicAndParameter;


    }


    /** Get votes by topic with parameters associated
     * @param $topics
     * @param $votes
     * @param $usersWithParameter
     * @param $secondParameterOptions
     * @param $thirdParameterOptions
     * @return array
     */
    private function getVotesByTopicBirthdayAndTwoParams($topics, $votes, $usersWithParameter,$secondParameterOptions,$thirdParameterOptions)
    {
        $votesByTopicAndParameter = [];
        foreach ($topics as $topic) {
            $dataTemp['title'] = $topic->title;
            $dataTemp['budget'] = 0;
            $dataTemp['category'] = "";
            $dataTemp['geo_area'] = "";
            $dataTemp['parameter_options'] = [];
            $dataTemp['total'] = 0;


            foreach ($topic->parameters as $value) {
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
                if($vote->vote_key == $topic->topic_key && $usersWithParameter->has($vote->user_key)){

                    $age = $usersWithParameter->get($vote->user_key)['parameter_key'];

                        $secondParam = $secondParameterOptions->get($usersWithParameter->get($vote->user_key)['second_parameter_key'])->name ?? '';

                        $thirdParam = $thirdParameterOptions->get($usersWithParameter->get($vote->user_key)['third_parameter_key'])->name ?? '';
                    if (empty($dataTemp['parameter_options'][$secondParam][$thirdParam])) {
                        $dataTemp['parameter_options'][$secondParam][$thirdParam]['positive'] = 0;
                        $dataTemp['parameter_options'][$secondParam][$thirdParam]['negative'] = 0;
                        $dataTemp['parameter_options'][$secondParam][$thirdParam]['balance'] = 0;
                        $dataTemp['parameter_options'][$secondParam][$thirdParam]['total'] = 0;
                    }

                    if($vote->value > 0){
                        $dataTemp['parameter_options'][$secondParam][$thirdParam]['positive'] += $vote->value;

                    }else{
                        $dataTemp['parameter_options'][$secondParam][$thirdParam]['negative'] += abs($vote->value);
                    }

                    $dataTemp['parameter_options'][$secondParam][$thirdParam]['total']  += abs($vote->value);
                    $dataTemp['parameter_options'][$secondParam][$thirdParam]['balance']  += $vote->value;


                    $dataTemp['total'] += $vote->value;
                }
            }
            $votesByTopicAndParameter[] = $dataTemp;
        }

        return $votesByTopicAndParameter;


    }




    /** Count votes by source. Ex: Kiosk, Mobile, etc.
     * @param $votes
     * @param $usersWithParameter
     * @param $parameterOptions
     * @return array
     * @throws Exception
     */
    private function countVotesByParameter($votes,$usersWithParameter,$parameterOptions){
        $data = [];
        $usersVoted= [];
        $data['count_by_parameter'] = [];
        $data['first_by_parameter'] = [];
        $data['second_by_parameter'] = [];
        foreach ($votes as $vote) {
            if($usersWithParameter->has($vote->user_key) && $parameterOptions->has($usersWithParameter->get($vote->user_key))){

                    $optionName = $parameterOptions->get($usersWithParameter->get($vote->user_key))->name ?? null;
                if(empty($optionName)){
                    continue;
                }
                if($vote->value > 0){
                    if(empty($data['count_by_parameter'][$optionName]['positive'])){
                        $data['count_by_parameter'][$optionName]['positive'] = 0;
                    }
                    $data['count_by_parameter'][$optionName]['positive'] += 1;

                }else{
                    if(empty($data['count_by_parameter'][$optionName]['negative'])){
                        $data['count_by_parameter'][$optionName]['negative'] = 0;
                    }
                    $data['count_by_parameter'][$optionName]['negative'] += 1;
                }


                /** First Vote by Parameter*/
                if(!isset($usersVoted[$vote->user_key])) {
                    $usersVoted[$vote->user_key] = 1;

                    if($vote->value > 0){
                        if(empty($data['first_by_parameter'][$optionName]['positive'])){
                            $data['first_by_parameter'][$optionName]['positive'] = 0;
                        }
                        $data['first_by_parameter'][$optionName]['positive'] += 1;

                    }else{
                        if(empty($data['first_by_parameter'][$optionName]['negative'])){
                            $data['first_by_parameter'][$optionName]['negative'] = 0;
                        }
                        $data['first_by_parameter'][$optionName]['negative'] += 1;
                    }
                }

                /** Second Vote by Parameter*/
                if(isset($usersVoted[$vote->user_key]) && $usersVoted[$vote->user_key] == 2){
                    if($vote->value > 0){
                        if(empty($data['second_by_parameter'][$optionName]['positive'])){
                            $data['second_by_parameter'][$optionName]['positive'] = 0;
                        }
                        $data['second_by_parameter'][$optionName]['positive'] += 1;

                    }else{
                        if(empty($data['second_by_parameter'][$optionName]['negative'])){
                            $data['second_by_parameter'][$optionName]['negative'] = 0;
                        }
                        $data['second_by_parameter'][$optionName]['negative'] += 1;
                    }
                }
                $usersVoted[$vote->user_key] = $usersVoted[$vote->user_key] + 1;
            }
        }
        return $data;


    }

    /** Get Parameter Birthday statistics
     * @param $usersInfo
     * @param $usersData
     * @param $parameterKey
     * @param $secondParameterKey
     * @param $thirdParameterKey
     * @param $votes
     * @param $topics
     * @param $ageValue
     * @return \Illuminate\Http\JsonResponse
     */
    private function getVoteStatisticsByBirthday($usersInfo,$usersData, $parameterKey,$secondParameterKey,$thirdParameterKey, $votes, $topics,$ageValue)
    {

        $ageIntervals = ['0-19','20-29','30-39','40-49','50-59','60-69','70+'];
        $votesByParameter = [];
        $votesByParameter['negative_vote'] = array_fill_keys($ageIntervals, 0);
        $votesByParameter['positive'] = array_fill_keys($ageIntervals, 0);
        $votesByParameter['negative'] = array_fill_keys($ageIntervals, 0);
        $votesByParameter['balance']  = array_fill_keys($ageIntervals, 0);
        $votesByParameter['total']= array_fill_keys($ageIntervals, 0);


        /* ------ Filter User with Parameter and map it with "user key => parameter key" ------ */
        $usersWithParameter = collect($usersData)->filter(function($user) use($parameterKey){
            if(array_key_exists($parameterKey,$user)) {
                return true;
            }
            return false;
        })->map(function($parameter) use ($parameterKey){
            return $parameter[$parameterKey];
        });

        if($usersWithParameter->isEmpty()){
            return response()->json(["data" => $votesByParameter], 200);
        }



        /** Verify second parameter type and get population by second parameter and parameter requested and Age > 90 */
        if(!empty($secondParameterKey) && !empty($thirdParameterKey)){

            $ageOption = '+'.$ageValue;
            $secondParameterAndOptionsData = $usersInfo->getParameterAndOptions($secondParameterKey);
            $secondParameter = $secondParameterAndOptionsData['user_parameter'];
            $secondParameterOptions = $secondParameterAndOptionsData['user_parameter_options'];


            $thirdParameterAndOptionsData = $usersInfo->getParameterAndOptions($thirdParameterKey);
            $thirdParameter = $secondParameterAndOptionsData['user_parameter'];
            $thirdParameterOptions = $thirdParameterAndOptionsData['user_parameter_options'];


            /* ------ Filter User with Parameter and map it with "user key => parameter key" ------ */
            $usersWithTwoParameters = collect($usersData)->filter(function($user) use($ageValue,$parameterKey,$secondParameterKey,$secondParameterOptions,$thirdParameterKey,$thirdParameterOptions){
                if(array_key_exists($parameterKey,$user) && array_key_exists($secondParameterKey,$user) && $secondParameterOptions->has($user[$secondParameterKey])
                    && array_key_exists($thirdParameterKey,$user) && $thirdParameterOptions->has($user[$thirdParameterKey])) {
                    if(Carbon::parse($user[$parameterKey])->diffInYears(Carbon::now()) >= $ageValue){
                        return true;
                    }
                }
                return false;
            })->map(function($parameter) use ($ageValue,$parameterKey,$secondParameterKey,$thirdParameterKey){
                return ['parameter_key' => '+'.$ageValue,'second_parameter_key' => $parameter[$secondParameterKey],'third_parameter_key' => $parameter[$thirdParameterKey]];

            });

            /* ------ Filter votes by user's with parameter ------ */
            $votesFiltered = $votes->filter(function($vote) use($usersWithTwoParameters){
                return $usersWithTwoParameters->has($vote->user_key);
            })->toArray();


            /** Votes by Topic and parameter */

            $votesByTopicAndTwoParameters = $this->getVotesByTopicBirthdayAndTwoParams($topics,$votesFiltered,$usersWithTwoParameters,$secondParameterOptions,$thirdParameterOptions);

            $secondParameterOptions = $secondParameterOptions->pluck('name');
            $thirdParameterOptions = $thirdParameterOptions->pluck('name');
            $data['statistics_by_age_two_params'] = $votesByTopicAndTwoParameters;
            $data['statistics_by_age_two_params'] = $votesByTopicAndTwoParameters;
            $data['first_parameters_options'] = $ageOption ?? [];
            $data['second_parameters_options'] = $secondParameterOptions ?? [];
            $data['third_parameters_options'] = $thirdParameterOptions ?? [];

        }


        /* ------ Filter votes by user's with parameter ------ */
        $votes = $votes->filter(function($vote) use($usersWithParameter){
            return $usersWithParameter->has($vote->user_key);
        })->toArray();


        if(empty($votes)){
            return response()->json(["data" => $votesByParameter], 200);
        }

        /** Votes by Topic and parameter */

        $votesByTopicAndParameter = $this->getVotesByTopicAndBirthday($topics,$votes,$usersWithParameter);

        /** Get Vote Population By parameter*/
        $votePopulation = array_fill_keys($ageIntervals, 0);
        $votesDistinctUsers = collect($votes)->keyBy('user_key')->toArray();
        foreach ($votesDistinctUsers as $vote){
            if($usersWithParameter->has($vote->user_key)) {
                $age = Carbon::parse($usersWithParameter->get($vote->user_key))->diffInYears(Carbon::now());
                if ($age < 20) {
                    $ageInterval = '0-19';
                } elseif ($age < 30) {
                    $ageInterval = '20-29';
                } elseif ($age < 40) {
                    $ageInterval = '30-39';
                } elseif ($age < 50) {
                    $ageInterval = '40-49';
                } elseif ($age < 60) {
                    $ageInterval = '50-59';
                } elseif ($age < 70) {
                    $ageInterval = '60-69';
                } else {
                    $ageInterval = '70+';
                }
                $votePopulation[$ageInterval] += 1;
            }
        }



        /* ------ Count votes by user option and value ------ */
        foreach ($votes as $vote){
            if($usersWithParameter->has($vote->user_key)){
                $age = Carbon::parse($usersWithParameter->get($vote->user_key))->diffInYears(Carbon::now());
                if ($age < 20) {
                    $ageInterval = '0-19';
                } elseif ($age < 30) {
                    $ageInterval = '20-29';
                } elseif ($age < 40) {
                    $ageInterval = '30-39';
                } elseif ($age < 50) {
                    $ageInterval = '40-49';
                } elseif ($age < 60) {
                    $ageInterval = '50-59';
                } elseif ($age < 70) {
                    $ageInterval = '60-69';
                } else {
                    $ageInterval = '70+';
                }

                if($vote->value > 0){
                    $votesByParameter['positive'][$ageInterval] += $vote->value;

                }else{
                    $votesByParameter['negative'][$ageInterval] += $vote->value;
                }
                $votesByParameter['total'][$ageInterval] += abs($vote->value);
                $votesByParameter['balance'][$ageInterval] += $vote->value;
            }
        }

        /** Count votes by parameter, first and second votes */
        $countByParameter = $this->countVotesByParameterBirthday($votes,$usersWithParameter,$ageIntervals);

        $data['statistics_by_topic'] = $votesByTopicAndParameter;
        $data['statistics_by_parameter'] = $votesByParameter;
        $data['count_by_parameter'] = $countByParameter['count_by_parameter'] ?? [];
        $data['first_by_parameter'] = $countByParameter['first_by_parameter'] ?? [];
        $data['second_by_parameter'] = $countByParameter['second_by_parameter'] ?? [];
        $data['parameters_options'] = $ageIntervals ?? [];
        $data['vote_population'] = $votePopulation ?? [];

        return $data;

    }


    /** Count Votes By Parameter Birthday, first vote and second vote
     * @param $votes
     * @param $usersWithParameter
     * @param $ageIntervals
     * @return array
     */
    private function countVotesByParameterBirthday($votes, $usersWithParameter, $ageIntervals)
    {
        $data = [];
        $usersVoted= [];
        $data['count_by_parameter'] = array_fill_keys($ageIntervals, ['positive'=> 0,'negative' => 0]);
        $data['first_by_parameter'] = array_fill_keys($ageIntervals, ['positive'=> 0,'negative' => 0]);
        $data['second_by_parameter'] = array_fill_keys($ageIntervals, ['positive'=> 0,'negative' => 0]);

        foreach ($votes as $vote) {
            if($usersWithParameter->has($vote->user_key)){

                $age = Carbon::parse($usersWithParameter->get($vote->user_key))->diffInYears(Carbon::now());

                if ($age < 20) {
                    $ageInterval = '0-19';
                } elseif ($age < 30) {
                    $ageInterval = '20-29';
                } elseif ($age < 40) {
                    $ageInterval = '30-39';
                } elseif ($age < 50) {
                    $ageInterval = '40-49';
                } elseif ($age < 60) {
                    $ageInterval = '50-59';
                } elseif ($age < 70) {
                    $ageInterval = '60-69';
                } else {
                    $ageInterval = '70+';
                }

                if($vote->value > 0){
                    if(empty($data['count_by_parameter'][$ageInterval]['positive'])){
                        $data['count_by_parameter'][$ageInterval]['positive'] = 0;
                    }
                    $data['count_by_parameter'][$ageInterval]['positive'] += 1;

                }else{
                    if(empty($data['count_by_parameter'][$ageInterval]['negative'])){
                        $data['count_by_parameter'][$ageInterval]['negative'] = 0;
                    }
                    $data['count_by_parameter'][$ageInterval]['negative'] += 1;
                }

                /** First Vote by Parameter*/
                if(!isset($usersVoted[$vote->user_key])) {
                    $usersVoted[$vote->user_key] = 1;

                    if($vote->value > 0){
                        if(empty($data['first_by_parameter'][$ageInterval]['positive'])){
                            $data['first_by_parameter'][$ageInterval]['positive'] = 0;
                        }
                        $data['first_by_parameter'][$ageInterval]['positive'] += 1;

                    }else{
                        if(empty($data['first_by_parameter'][$ageInterval]['negative'])){
                            $data['first_by_parameter'][$ageInterval]['negative'] = 0;
                        }
                        $data['first_by_parameter'][$ageInterval]['negative'] += 1;
                    }
                }

                /** Second Vote by Parameter*/
                if(isset($usersVoted[$vote->user_key]) && $usersVoted[$vote->user_key] == 2){

                    if($vote->value > 0){
                        if(empty($data['second_by_parameter'][$ageInterval]['positive'])){
                            $data['second_by_parameter'][$ageInterval]['positive'] = 0;
                        }
                        $data['second_by_parameter'][$ageInterval]['positive'] += 1;

                    }else{
                        if(empty($data['second_by_parameter'][$ageInterval]['negative'])){
                            $data['second_by_parameter'][$ageInterval]['negative'] = 0;
                        }
                        $data['second_by_parameter'][$ageInterval]['negative'] += 1;
                    }
                }
                $usersVoted[$vote->user_key] = $usersVoted[$vote->user_key] + 1;
            }
        }
        return $data;

    }


    /** Get Top topics by min votes and return top topics
     * @param Request $request
     * @param $voteEventKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function topTopics(Request $request,$voteEventKey)
    {
        try{
            $top = $request->json('top_topics');
            $minVotes = $request->json('min_votes');
            if(empty($minVotes) || $minVotes < 1){
                $minVotes = 0;
            }
            if(empty($top) || $top < 1){
                $top = 10;
            }
            $languageCode = $request->header('LANG-CODE');
            $languageCodeDefault = $request->header('LANG-CODE-DEFAULT');
            $eventInfo = new EventInfo($voteEventKey,$languageCode,$languageCodeDefault);
            $allVotes = $eventInfo->getEventVotes()->votes;

            /** Check valid votes with existent topics ------ */
            $topicVotesVerification = $eventInfo->verifyValidTopicsAndVotes($allVotes);
            $topicsOwners = collect($topicVotesVerification['topic_details'])->pluck('created_by','topic_key');
            $topicsInfo = collect($topicVotesVerification['topic_details'])->pluck('title','topic_key');
            $votes = $topicVotesVerification['votes_filtered'];


            $topTopics = array();
            /** If there are votes, get their data*/
            if (!$votes->isEmpty()) {
                $votesSummary = $eventInfo->getTopVotesSum($votes, $top);
                $topicKeysCountPositive = $votesSummary['topic_keys_positive'];

                /** Filter Topics with min votes defined */
                $topTopics = $eventInfo->getVotesDetailsWithMinVotes($topicKeysCountPositive, $topicsInfo, $topicsOwners, $minVotes);
            }

            if ($minVotes==0 && count($topTopics)<$top && !empty($request->get("cbKey",""))) {
                try {
                    $response = ONE::get([
                            'component' => 'cb',
                            'api' => 'cb',
                            'api_attribute' => $request->get("cbKey", ""),
                            'method' => 'topicsWithBasicData'
                        ]
                    );

                    if ($response->statusCode() != 200) {
                        throw new Exception('error_getting_topics_data');
                    }

                    $allTopicsData = $response->json()->data;
                    $topTopics = collect($topTopics);
                    $lastPositionIndex = $topTopics->max("position") ?? 0;
                    
                    foreach ($allTopicsData as $topicData) {
                        if (!$topTopics->contains("topic_key",$topicData->topic_key)) {
                            $lastPositionIndex++;
                            $temp = array(
                                "position"      => $lastPositionIndex,
                                "topic_key"     => $topicData->topic_key,
                                "topic_name"    => $topicData->title,
                                "created_by"    => $topicData->created_by,
                                "total_votes"   => 0
                            );
                            $topTopics->push($temp);

                            if (count($topTopics)>=$top)
                                break;
                        }
                    }
                } catch (Exception $e) {

                }
            }

            return response()->json(["data" => $topTopics], 200);
        }catch(Exception $e){
            return response()->json(["data" => []], 200);
        }

    }


}
