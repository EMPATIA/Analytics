<?php

namespace App\Generic;

use ONE;
use Carbon\Carbon;
use Exception;


class EventInfo
{


    private $eventKey;
    private $languageCode;
    private $languageCodeDefault;

    /**
     * EventInfo constructor.
     * @param $eventKey
     * @param $languageCode
     * @param $languageCodeDefault
     */
    public function __construct($eventKey,$languageCode,$languageCodeDefault)
    {
        $this->eventKey = $eventKey;
        $this->languageCode = $languageCode;
        $this->languageCodeDefault = $languageCodeDefault;

    }


    /** Get event details
     * @return mixed
     * @throws Exception
     */
    public function getEvent(){

        $response = ONE::get([
                'component' => 'vote',
                'api' => 'event',
                'api_attribute' => $this->eventKey
            ]
        );

        if($response->statusCode() != 200){
            throw new Exception('error_getting_vote_event');
        }
        return $response->json();
    }


    /** Get Event votes
     * @return mixed
     * @throws Exception
     */
    public function getEventVotes($viewSubmitted = null)
    {

        $response = ONE::get([
                'component' => 'vote',
                'api' => 'event',
                'api_attribute' => $this->eventKey,
                'method' => 'votes',
                'params' => ['submitted' => $viewSubmitted]
            ]
        );
//        !is_null($response->json()) ? dd("remote/DD",$response->json()) : die("remote/ECHO" .$response->content());
        if ($response->statusCode() != 200) {
            throw new Exception('error_getting_votes');
        }

        return $response->json()->data;

    }


    /** Get topics details
     * @param $topicKeys
     * @return mixed
     * @throws Exception
     */
    public function getTopicsDetails($topicKeys)
    {

        $response = ONE::post([
                'component' => 'cb',
                'api' => 'topic',
                'method' => 'getTopics',
                'params' => [
                    'topic_keys' => $topicKeys,
                    'no_parameters' => true
                ],
                'headers' => [
                    'LANG-CODE: '.$this->languageCode,
                    'LANG-CODE-DEFAULT: '.$this->languageCodeDefault,
                ]
            ]
        );
        if ($response->statusCode() != 200) {
            throw new Exception('error_getting_topics_details');
        }
        return $response->json()->data;
    }


    /** Verify if negative vote is active
     * @param $event
     * @return bool
     */
    public function verifyNegativeVoteExists($event)
    {

        $negativeVote = false;
        switch ($event->method->code){
            case 'likes':
                if(isset($event->configurations)){
                    $configurationsByCode = collect($event->configurations)->pluck('value','configuration_code');
                    if($configurationsByCode->has('allow_dislike') && $configurationsByCode->get('allow_dislike') == 1){
                        $negativeVote = true;
                    }
                }
                break;
            case 'negative_voting':
                $negativeVote = true;
                break;
        }
        return $negativeVote;

    }

    /** Get top voted with summed votes values
     * @param $votes
     * @param $top
     * @return array
     */
    public function getTopVotesSum($votes, $top)
    {
        $topicKeysCountTotal = collect($votes)->groupBy('vote_key')
            ->map(function($topicVote){
                $total = 0;
                foreach ($topicVote as $vote){
                    $total += abs($vote->value);
                }
                return $total;
            })
            ->sortByDesc(function($topic){
                return $topic;
            })
            ->take($top);
        $topicKeysCountBalance = collect($votes)->groupBy('vote_key')
            ->map(function($topicVote){
                return $topicVote->sum('value');
            })
            ->sortByDesc(function($topic){
                return $topic;
            })
            ->take($top);
        $topicKeysCountPositive = collect($votes)->where('value','>',0)
            ->groupBy('vote_key')
            ->map(function($topicVote){
                return $topicVote->sum('value');
            })
            ->sortByDesc(function($topic){
                return $topic;
            })
            ->take($top);
        $topicKeysCountNegative = collect($votes)->where('value','<',0)
            ->groupBy('vote_key')
            ->map(function($topicVote){
                return $topicVote->sum('value');
            })
            ->sortByDesc(function($topic){
                return $topic;
            })
            ->take($top);

        $topicKeysCount = ['topic_keys_total' => $topicKeysCountTotal,
                            'topic_keys_balance' => $topicKeysCountBalance,
                            'topic_keys_positive' => $topicKeysCountPositive,
                            'topic_keys_negative' => $topicKeysCountNegative
                            ];

        return $topicKeysCount;

    }

    /** Get Votes in arrays of keys
     * @param $votes
     * @param $top
     * @return \Illuminate\Support\Collection
     */
    public function getVotesInArrayKeys($votes, $top)
    {
        return collect($votes)->whereIn('vote_key',$top)->groupBy('vote_key');

    }


    /** Get top votes with details
     * @param $topicKeysCount
     * @param $topicsInfo
     * @return array
     */
    public function getTopVotesDetails($topicKeysCount, $topicsInfo)
    {
        $topVotes = [];
        $position = 1;
        foreach ($topicKeysCount as $key => $voteValue){
            $topVotes[] = ['position' => $position,'topic_name' => ($topicsInfo->get($key) ?? ''),'total_votes' => $voteValue];
            $position += 1;
        }
        return $topVotes;

    }


    /** Get top votes with details with min votes
     * @param $topicKeysCount
     * @param $topicsInfo
     * @param $topicsOwners
     * @param $minVotes
     * @return array
     */
    public function getVotesDetailsWithMinVotes($topicKeysCount, $topicsInfo,$topicsOwners,$minVotes)
    {
        $topVotes = [];
        $position = 1;
        foreach ($topicKeysCount as $key => $voteValue){
            if($voteValue >= $minVotes){
                $topVotes[] = [
                    'position'      => $position,
                    'topic_key'     => $key,
                    'topic_name'    => ($topicsInfo->get($key) ?? ''),
                    'created_by'    => ($topicsOwners->get($key) ?? ''),
                    'total_votes'   => $voteValue];
                $position += 1;
            }
        }
        return $topVotes;

    }


    /** Get top votes with details by date
     * @param $topicKeysCount
     * @param $dateRange
     * @param $topicsInfo
     * @param bool $total
     * @return array
     */
    public function getTopVotesByDate($topicKeysCount, $dateRange, $topicsInfo, $total = false)
    {
        $topVotes = [];
        foreach ($topicKeysCount as $key => $voteValue){
            $topicVotes = $dateRange;
            foreach ($voteValue as $vote){
                $date = Carbon::parse($vote->created_at);
                if(!array_key_exists($date->format('Y-m-d'),$dateRange)){
                    continue;
                }
                if($total){
                    $topicVotes[$date->format('Y-m-d')] += abs($vote->value);
                }else{
                    $topicVotes[$date->format('Y-m-d')] += $vote->value;
                }
            }

            $topVotes[] = ['topic_name' => ($topicsInfo->get($key) ?? ''),'votes' => $topicVotes];
        }
        return $topVotes;
    }


    /** Verify topics voted if exists
     * @param $allVotes
     * @return array - with topic details and votes filtered by topics in db
     */
    public function verifyValidTopicsAndVotes($allVotes)
    {
        $allVotesByKey = collect($allVotes)->keyBy('vote_key')->keys()->toArray();

        $topicDetails = $this->getTopicsDetails($allVotesByKey);
        $topicsKeys = collect($topicDetails)->keyBy('topic_key')->keys();
        $votesFiltered = collect($allVotes)->filter(function ($vote) use($topicsKeys){
            return $topicsKeys->contains($vote->vote_key);
        });
        $details = ['topic_details' => $topicDetails, 'votes_filtered' => $votesFiltered];
        return $details;

    }


}