<?php
/**
 * @SWG\Definition(
 *   type="object",
 *   @SWG\Xml(name="Domain_Get")
 * )
 */
class Domain_Get{
     /**
     * @SWG\Property(type="string", example="youxuana", description="Valid Value:a-z, A-Z, 0-9, “_”,“-”,“.”,“@”; Notes:domainId(Unique Identifier)")
     */
     public $domainId;
     /**
     *	@SWG\Property(
     *		ref="#/definitions/Domain_Address",	
     *)
     */
     public $address;
      /**
     *	@SWG\Property(
     *		type="array",
     *		@SWG\Items(ref="#/definitions/Domain_User")	
     *)
     */
     public $users;
}
/**
* @SWG\Definition(
* 	type="object",
*	@SWG\Xml(name="Domain_Address")
*)
*/
class Domain_Address{
	 /**
     * @SWG\Property( example="北京", description="Valid Value:a-z, A-Z, 0-9, “_”,“-”,“.”,“@”; Notes:domainId(Unique Identifier)")
     * @var string
     */
     public $city;
}
/**
* @SWG\Definition(
*	type="object",
*	@SWG\Xml(name="Domain_User")			
*)
*/
class Domain_User{
	 /**
     * @SWG\Property(type="string", example="赵老师", description="Valid Value:a-z, A-Z, 0-9, “_”,“-”,“.”,“@”; Notes:domainId(Unique Identifier)")
     */
     public $name;
}
function model_get_domain_info($data){
	global $dbm;
	$tips = new stdclass();
	$sql = "SELECT * FROM taclgroupstable WHERE FCompanyAccountID = '".$data['domainId']."'";
	$res = $dbm->query($sql)->fetch(PDO::FETCH_ASSOC);
	if($res){
		$tips->code = 200;
		$tips->data = (object) $res;
	}else{
		$tips->code = 405;
		$tips->sub_code = 1101;
		$tips->message = "DomainId not exist";
		return $tips;
	}
	return $tips;
}


