<?php
/**
* @SWG\Get(
*     tags={"domains"},
*     path="/domains/{domainId}",
*     summary="Get a domain by domainId",
*     description="Muliple tags can be provided with comma separated strings. Use tag1, tag2, tag3 for testing.",
*     operationId="getDomainByDomainId",
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="domainId",
*         in="path",
*         description="Get a domain by domainId",
*         required=true,
*         type="string",
*         collectionFormat="multi"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*         @SWG\Schema(ref="#/definitions/Domain_Get"),
*     ),
*     @SWG\Response(
*         response="400",
*         description="Invalid tag value",
*     ),
* )
*/
function get_domain_info($request,$response,$args){
     $res = model_get_domain_info($args);
     if($res->code == 200){
          return $response->withJson($res->data,$res->code);
     }else{
          $tips = new stdclass();
          $tips->code = $res->sub_code;
          $tips->message = $res->message;
          return $response->withJson($tips,$res->code);
     }
    
}
/**
* @SWG\Post(
*    tags={"domains"},
*    path="/domains",
*    summary="Post a domain summary",
*    description="Post a domain description",
*    operationId="postADomain",
*    produces={"application/json"},
*    @SWG\Parameter(
*         name="domainAvatar",
*         in="body",
*         description="Post a domain avatar",
*         required=true,
*         @SWG\Schema(ref="#/definitions/Domain_Get"),
*    ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*         @SWG\Schema(ref="#/definitions/Domain_Get"),
*     ),
*     @SWG\Response(
*         response="400",
*         description="Invalid tag value",
*     ),
*)
*/
function domain_add($req,$res){
     var_dump($req->getParsedBody());die;
}