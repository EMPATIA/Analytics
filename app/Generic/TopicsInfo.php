<?php

namespace App\Generic;
use ONE;
use Exception;

class TopicsInfo
{

    private $entityKey;
    private $languageCode;
    private $languageCodeDefault;
    private $authToken;

    /**
     * TopicsInfo constructor.
     * @param $authToken
     * @param $entityKey
     * @param $languageCode
     * @param $languageCodeDefault
     */
    public function __construct($authToken,$entityKey,$languageCode,$languageCodeDefault)
    {
        $this->entityKey = $entityKey;
        $this->languageCode = $languageCode;
        $this->languageCodeDefault = $languageCodeDefault;
        $this->authToken = $authToken;
    }



    /** Get Topics
     * @param $usersKeys
     * @return mixed
     * @throws Exception
     */
    public function getTopics($cbKey)
    {
        $response = ONE::get([
            'component' => 'orchestrator',
            'api' => 'topic',
            'method'    => 'listWithFirst',
            'attribute' => $cbKey,
            'headers' => [
                'X-ENTITY-KEY: '.$this->entityKey,
                'X-AUTH-TOKEN: '.$this->authToken
            ]
        ]);

        if($response->statusCode() != 200){
            throw new Exception('error_in_get_topics');
        }

        return $response->json();
    }


    /** Get topics parameters
     * @param $cbKey
     * @return array
     */
    public function getTopicsParameters($cbKey, $topicKeys = [])
    {
        $topics = $this->getTopics($cbKey);
        $topicsParameters = [];
        foreach (!empty($topics->data) ? $topics->data :[]  as $topic){
            if(in_array($topic->topic_key,$topicKeys)) {
                $topicsParameters [$topic->topic_key] = [];
                if (!empty($topic->_cached_data)) {
                    $cachedData = json_decode($topic->_cached_data);
                    foreach ($cachedData->parameters as $param) {
                        if(!empty($param->id)){
                            $topicsParameters[$topic->topic_key][$param->id] = !empty($param->pivot->value) ? $param->pivot->value : $param->value;
                        }
                    }
                } else if (!empty($topic->parameters)) {
                    foreach ($topic->parameters as $param) {
                        $topicsParameters[$topic->topic_key][$param->id] = !empty($param->pivot->value) ? $param->pivot->value : $param->value;
                    }
                }
            }
        }
        return $topicsParameters;
    }


    /** Get entity users parameter by key
     * @param $parameterUserType
     * @return mixed
     * @throws Exception
     */
    private function getParameterTopicType($parameterTopicType) {
        $response = ONE::get([
            'component' => 'orchestrator',
            'api'       => 'parameters',
            'attribute' => $parameterTopicType,
            'headers' => [
                'LANG-CODE: '.$this->languageCode,
                'LANG-CODE-DEFAULT: '.$this->languageCodeDefault,
                'X-ENTITY-KEY: '.$this->entityKey
            ]
        ]);
        if($response->statusCode() != 200){
            //throw new Exception($response->content() ." ".$parameterTopicType);
            throw new Exception('error_in_topic_parameter');
        }
        return $response->json();
    }

    /** Get entity users parameter by key
     * @param $parameterUserType
     * @return mixed
     * @throws Exception
     */
    private function getParameterOptions($parameterTopicType) {
        $response = ONE::get([
            'component' => 'orchestrator',
            'api' => 'parameters',
            'api_attribute' => $parameterTopicType,
            'method' => 'options',
            'headers' => [
                'LANG-CODE: '.$this->languageCode,
                'LANG-CODE-DEFAULT: '.$this->languageCodeDefault,
                'X-ENTITY-KEY: '.$this->entityKey
            ]
        ]);
        if($response->statusCode() != 200){
            throw new Exception($response->content() ." ".$parameterTopicType);
            // throw new Exception('error_in_topic_parameter');
        }
        return $response->json();
    }

    public function getParameterAndOptions($parameterId)
    {
        $parameters = $this->getParameterTopicType($parameterId);
        // $parameterOptions = collect($usersParameters->parameter_user_options)->keyBy('parameter_user_option_key');
        // $parameterOptions = collect($usersParameters->parameter_user_options)->keyBy('id');
        $parameterOptions = $this->getParameterOptions($parameterId);

        // dd($parameterOptions->options);
        if(!empty($parameterOptions->options))
            $parameterOptions = collect($parameterOptions->options)->keyBy('id');

        $data = [];
        $data['topic_parameter'] = $parameters;
        $data['topic_parameter_options'] = $parameterOptions;
        return $data;

    }
}