<?php
/**
 * @SWG\Definition(
 *   type="object",
 *   @SWG\Xml(name="Domain_Get")
 * )
 */
class Domain_Get{
     /**
     * @SWG\Property( example="aculearn", description="Valid Value:a-z, A-Z, 0-9, “_”,“-”,“.”,“@”; Notes:domainId(Unique Identifier)")
     * @var string
     */
     public $domainId;
}


