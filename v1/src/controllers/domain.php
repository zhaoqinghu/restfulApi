<?php
namespace controller;
class domain{
     /**
     * @SWG\Get(
     *     path="/domain/{domainId}",
     *     summary="Get a domain by domainId",
     *     tags={"domain"},
     *     description="Muliple tags can be provided with comma separated strings. Use tag1, tag2, tag3 for testing.",
     *     operationId="getDomainByDomainId",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="domainId",
     *         in="path",
     *         description="Tags to filter by",
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
     function get_info(){
          echo 'add';
     }
}
 

