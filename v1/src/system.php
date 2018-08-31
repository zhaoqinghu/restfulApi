<?php
/**
 * @SWG\Swagger(
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Youxuana",
 *		   description="This is description",
 *		   termsOfService="http://swagger.io/terms/",
 *         @SWG\License(
 *				name="License",
 *				url="http://www.apache.org/licenses/LICENSE-2.0.html",
 *				),
 *         @SWG\Contact(
 *				name="Youxuana",
 *         		url="http://www.youxuana.com",			
 *         		email="123@youxuana.com"			
 *			)
 *     ),
 *		@SWG\ExternalDocumentation(
 *			description="External Documentation",
 *			url="http://youxuana.com",
 *		),
 *     host="localhost/test/slim",
 *     basePath="/v1",
 *     schemes={"http"},
 *     consumes={"application/json"},
 *     produces={"application/json"},
 * ),
 */
/**
 *     @SWG\Definition(
 *         definition="Error",
 *         required={"code", "message"},
 *         @SWG\Property(
 *             property="code",
 *             type="integer",
 *             format="int32"
 *         ),
 *         @SWG\Property(
 *             property="message",
 *             type="string"
 *         )
 *     ),
 *     @SWG\Definition(definition="Pets",
 *         type="array",
 *         @SWG\Items(ref="#/definitions/Pet")
 *     )
 */