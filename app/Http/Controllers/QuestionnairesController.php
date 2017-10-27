<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\One\One;

/**
 * @SWG\Tag(
 *   name="Questionnaire Statistics",
 *   description="Everything about Questionnaire Statistics",
 * )
 *
 *
 *  @SWG\Definition(
 *   definition="questionnaireStatisticsReply",
 *   type="object",
 *   allOf={
 *       @SWG\Schema(
 *
 *       @SWG\Property(property="form_key", format="string", type="string"),
 *       @SWG\Property(property="entity_key", format="string", type="string"),
 *       @SWG\Property(property="title", format="string", type="string"),
 *       @SWG\Property(property="description", format="string", type="string"),
 *       @SWG\Property(property="public", format="boolean", type="boolean"),
 *       @SWG\Property(property="created_by", format="string", type="string"),
 *       @SWG\Property(property="start_date", format="date", type="string"),
 *       @SWG\Property(property="end_date", format="date", type="string"),
 *       @SWG\Property(property="link", format="string", type="string"),
 *       @SWG\Property(property="file_id", format="integer", type="integer"),
 *       @SWG\Property(property="created_at", format="date", type="string"),
 *       @SWG\Property(property="updated_at", format="date", type="string"),
 *       @SWG\Property(
 *                 property="question_groups",
 *                 type="array",
 *                 @SWG\Items(
 *                      @SWG\Property(property="question_group_key", format="string", type="string"),
 *                      @SWG\Property(property="form_id", format="integer", type="integer"),
 *                      @SWG\Property(property="title", format="string", type="string"),
 *                      @SWG\Property(property="description", format="string", type="string"),
 *                      @SWG\Property(property="position", format="integer", type="integer"),
 *                      @SWG\Property(property="created_at", format="date", type="string"),
 *                      @SWG\Property(property="updated_at", format="date", type="string"),
 *                      @SWG\Property(
 *                          property="questions",
 *                          type="array",
 *                          @SWG\Items(
 *                                  @SWG\Property(property="id", format="integer", type="integer"),
 *                                  @SWG\Property(property="question_key", format="string", type="string"),
 *                                  @SWG\Property(property="question_group_id", format="integer", type="integer"),
 *                                  @SWG\Property(property="question_type_id", format="integer", type="integer"),
 *                                  @SWG\Property(property="question", format="integer", type="integer"),
 *                                  @SWG\Property(property="description", format="date", type="string"),
 *                                  @SWG\Property(property="mandatory", format="boolean", type="boolean"),
 *                                  @SWG\Property(property="position", format="integer", type="integer"),
 *                                  @SWG\Property(property="reuse_question_options", format="boolean", type="boolean"),
 *                                  @SWG\Property(property="created_at", format="date", type="string"),
 *                                  @SWG\Property(property="updated_at", format="date", type="string"),
 *                                  @SWG\Property(
 *                                      property="question_options",
 *                                      type="array",
 *                                      @SWG\Items(
 *                                              @SWG\Property(property="id", format="string", type="string"),
 *                                              @SWG\Property(property="question_option_key", format="integer", type="integer"),
 *                                              @SWG\Property(property="icon_id", format="string", type="string"),
 *                                              @SWG\Property(property="question_id", format="string", type="string"),
 *                                              @SWG\Property(property="label", format="integer", type="integer"),
 *                                              @SWG\Property(property="file_id", format="date", type="string"),
 *                                              @SWG\Property(property="file_code", format="date", type="string"),
 *                                              @SWG\Property(property="position", format="date", type="string"),
 *                                              @SWG\Property(property="created_at", format="date", type="string"),
 *                                              @SWG\Property(property="updated_at", format="date", type="string"),
 *                                              @SWG\Property(
 *                                                  property="icon",
 *                                                  type="object",
 *                                                  allOf={
 *                                                  @SWG\Schema(
 *                                                  @SWG\Property(property="id", format="integer", type="integer"),
 *                                                  @SWG\Property(property="icon_key", format="string", type="string"),
 *                                                  @SWG\Property(property="name", format="string", type="string"),
 *                                                  @SWG\Property(property="file_id", format="integer", type="integer"),
 *                                                  @SWG\Property(property="file_code", format="string", type="string"),
 *                                                  @SWG\Property(property="created_at", format="date", type="string"),
 *                                                  @SWG\Property(property="updated_at", format="date", type="string")
 *                                                  )
 *                                                  }
 *                                              ),
 *                                              @SWG\Property(
 *                                                  property="dependencies",
 *                                                  type="array",
 *                                                  @SWG\Items(
 *                                                      @SWG\Property(property="id", format="integer", type="integer"),
 *                                                      @SWG\Property(property="dependency_key", format="string", type="string"),
 *                                                      @SWG\Property(property="question_option_id", format="integer", type="integer"),
 *                                                      @SWG\Property(property="question_key", format="string", type="string"),
 *                                                      @SWG\Property(property="created_at", format="date", type="string"),
 *                                                      @SWG\Property(property="updated_at", format="date", type="string")
 *                                                  )
 *                                              ),
 *                                              @SWG\Property(property="total", format="integer", type="integer"),
 *
 *                                        )
 *                                    ),
 *                                    @SWG\Property(
 *                                          property="question_type",
 *                                          type="object",
 *                                          allOf={
 *                                              @SWG\Schema(
 *                                                  @SWG\Property(property="id", format="integer", type="integer"),
 *                                                  @SWG\Property(property="question_type_key", format="string", type="string"),
 *                                                  @SWG\Property(property="name", format="string", type="string"),
 *                                                  @SWG\Property(property="created_at", format="date", type="string"),
 *                                                  @SWG\Property(property="updated_at", format="date", type="string"),
 *                                              )
 *                                          }
 *                                    ),
 *                                  @SWG\Property(property="total_count", format="integer", type="integer")
 *
 *                             ),
 *                      ),
 *                  )
 *             ),
 *
 *          )
 *      }
 *  )
 *
 *
 *
 *
 */

class QuestionnairesController extends Controller
{

    /**
     * @SWG\Get(
     *  path="/q/{form_key}/statistics",
     *  summary="Show questionnaire statistics",
     *  produces={"application/json"},
     *  consumes={"application/json"},
     *  tags={"Questionnaire Statistics"},
     *
     *  @SWG\Parameter(
     *      name="form_key",
     *      in="path",
     *      description="Form Key",
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
     *
     *  @SWG\Parameter(
     *      name="X-ENTITY-KEY",
     *      in="header",
     *      description="Entity Key",
     *      required=true,
     *      type="string"
     *  ),
     *
     *
     *
     *  @SWG\Response(
     *      response="200",
     *      description="Show questionnaire statistics",
     *      @SWG\Schema(ref="#/definitions/questionnaireStatisticsReply")
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
     * Request a specific data from Questionnaires and returns statistics data.
     *
     * @param Request $request
     * @param $formKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics(Request $request,$formKey)
    {
        try {
            $entityKey = $request->header('X-ENTITY-KEY');
            // Request answers
            $response = One::get([
                    'component' => 'q',
                    'api' => 'form',
                    'api_attribute' => $formKey,
                    'method' => 'getAnswers',
                    'headers' => [
                        'X-ENTITY-KEY: '.$entityKey,
                    ]
                ]
            );
            $answers = json_decode($response->content(), true);

            // Request questionnaire
            $response = One::get([
                    'component' => 'q',
                    'api' => 'form',
                    'api_attribute' => $formKey,
                    'method' => 'getQuestionnaire',
                    'headers' => [
                        'X-ENTITY-KEY: '.$entityKey,
                        'LANG-CODE: '.(!empty($request->header('LANG-CODE')) ? $request->header('LANG-CODE'): ""),
                        'LANG-CODE-DEFAULT: '.(!empty($request->header('LANG-CODE-DEFAULT')) ? $request->header('LANG-CODE-DEFAULT'): "")
                    ]
                ]
            );
            $questionnaire  = json_decode($response->content(), true);

            foreach($questionnaire['question_groups'] as &$qGroup){
                foreach($qGroup['questions'] as &$q){
                    // total count
                    $counter = 0;

                    foreach($answers["form_replies"] as &$formReply){
                       foreach($formReply["form_reply_answers"] as &$formReplyAnwser){
                             if($q["id"] == $formReplyAnwser["question_id"]){
                                 $formReplyAnwser["created_by"] = $formReply["created_by"];
                                 $q["form_replies"][] = $formReplyAnwser;
                                 $counter++;
                             }
                         }
                    }
                    $q["total_count"] = $counter;

                    foreach($q['question_options'] as &$qOption){
                        $counter = 0;
                        foreach($answers["form_replies"] as &$formReply){
                           foreach($formReply["form_reply_answers"] as &$formReplyAnwser){
                                 if($q["id"] == $formReplyAnwser["question_id"]
                                         && $formReplyAnwser["question_option_id"] == $qOption["id"] ){
                                     $counter++;
                                 }
                             }
                        }
                        $qOption["total"] = $counter;
                    }

                }
            }

            $questionnaire["form_replies"] = !empty($answers["form_replies"]) ? collect($answers["form_replies"])->keyBy('id')->toArray() : [];

            return response()->json($questionnaire, 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }
    
}
