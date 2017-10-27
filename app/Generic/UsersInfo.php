<?php

namespace App\Generic;
use ONE;
use Exception;

class UsersInfo
{

    private $entityKey;
    private  $languageCode;
    private $languageCodeDefault;
    private $authToken;

    /**
     * UsersInfo constructor.
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


    /** Get entity users parameters
     * @return mixed
     * @throws Exception
     */
    private function getEntityRegisterParameters(){

        $response = ONE::get([
            'component'     => 'orchestrator',
            'api'           => 'parameterUserType',
            'method'        => 'list',
            'headers' => [
                'LANG-CODE: '.$this->languageCode,
                'LANG-CODE-DEFAULT: '.$this->languageCodeDefault,
                'X-ENTITY-KEY: '.$this->entityKey
            ]
        ]);

        if($response->statusCode() != 200){
            throw new Exception('error_in_entity_users_parameters');
        }
        return $response->json()->data;
    }


    /** Get entity users parameter by key
     * @param $parameterUserType
     * @return mixed
     * @throws Exception
     */
    private function getParameterUserType($parameterUserType) {

        $response = ONE::get([
            'component' => 'orchestrator',
            'api'       => 'parameterUserType',
            'attribute' => $parameterUserType,
            'headers' => [
                'LANG-CODE: '.$this->languageCode,
                'LANG-CODE-DEFAULT: '.$this->languageCodeDefault,
                'X-ENTITY-KEY: '.$this->entityKey
            ]
        ]);
        if($response->statusCode() != 200){
            throw new Exception('error_in_entity_users_parameter');
        }
        return $response->json();
    }



    /** Get Users
     * @param $usersKeys
     * @return mixed
     * @throws Exception
     */
    public function getUsers($usersKeys)
    {
        $response = ONE::post([
            'component' => 'auth',
            'api'       => 'auth',
            'method'    => 'listUser',
            'params'    => [
                'userList' => $usersKeys
            ],
            'headers' => [
                'X-ENTITY-KEY: '.$this->entityKey,
                'X-AUTH-TOKEN: '.$this->authToken
            ]
        ]);
        if($response->statusCode() != 200){
            throw new Exception('error_in_get_users');
        }
        return $response->json();
    }



    /** Get users parameters
     * @param $usersKeys
     * @return array
     */
    public function getUsersParameters($usersKeys)
    {
        $users = $this->getUsers($usersKeys);
        $usersParameters = [];
        foreach ($users as $user){
            $usersParameters [$user->user_key] = [];
            foreach ($user->user_parameters as $param){
                $usersParameters [$user->user_key][$param->parameter_user_key] = $param->value;
            }
        }
        return $usersParameters;
    }


    /** Get Parameter key
     * @param $parameterType - must be in english and no spaces between words
     * @return null $parameterKey
     */
    public function getParameterKey($parameterType){
        $parameterTypeEN = null;
        $parameterTypePT = null;
        $parameterKey = null;
        $parameterKeyAndOptions = null;
        switch ($parameterType){
            case 'TOWN':
                $parameterTypeEN = $parameterType;
                $parameterTypePT = 'FREGUESIA';
                break;
            case 'BIRTHDAY':
                $parameterTypeEN = $parameterType;
                $parameterTypePT = 'DATADENASCIMENTO';
                break;
            case 'GENDER':
                $parameterTypeEN = $parameterType;
                $parameterTypePT = 'GÉNERO';
                $parameterKeyAndOptions = [];
                break;
            case 'PROFESSION':
                $parameterTypeEN = $parameterType;
                $parameterTypePT = 'PROFISSÃO';
                $parameterKeyAndOptions = [];
                break;
            case 'EDUCATIONALQUALIFICATIONS':
                $parameterTypeEN = $parameterType;
                $parameterTypePT = 'HABILITAÇÕESLITERÁRIAS';
                $parameterKeyAndOptions = [];
                break;
        }

        if(!$parameterTypeEN || !$parameterTypePT){
            return $parameterKey;
        }

        $usersParameters = $this->getEntityRegisterParameters();
        foreach ($usersParameters as $parameter){
            if((mb_strtoupper(preg_replace('/\s+/', '', $parameter->name)) == $parameterTypeEN) || (mb_strtoupper(preg_replace('/\s+/', '', $parameter->name)) == $parameterTypePT))
            {
                if(is_array($parameterKeyAndOptions)){
                    $paramOptions = collect($parameter->parameter_user_options)->keyBy('parameter_user_option_key')->toArray();
                    $parameterKeyAndOptions = ['parameter_key' => $parameter->parameter_user_type_key,'parameter_options' => $paramOptions];
                    break;
                }else {
                    $parameterKey = $parameter->parameter_user_type_key;
                    break;
                }
            }
        }
        return ($parameterKey ? $parameterKey : $parameterKeyAndOptions);
    }

    public function getParameterAndOptions($parameterKey)
    {
        $usersParameters = $this->getParameterUserType($parameterKey);
        $parameterOptions = collect($usersParameters->parameter_user_options)->keyBy('parameter_user_option_key');

        $data = [];
        $data['user_parameter'] = $usersParameters;
        $data['user_parameter_options'] = $parameterOptions;
        return $data;

    }

}