<?php declare(strict_types = 1);

namespace App\Module\PubV1;

use Apitte\Core\Annotation\Controller as Apitte;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\OpenApi\ISchemaBuilder;
use Psr\Http\Message\ResponseInterface;

/**
 * @Apitte\Path("/openapi")
 * @Apitte\Tag("OpenApi")
 */
class OpenApiController extends BasePubV1Controller
{

	private ISchemaBuilder $schemaBuilder;

	public function __construct(ISchemaBuilder $schemaBuilder)
	{
		$this->schemaBuilder = $schemaBuilder;
	}

	/**
	 * @Apitte\OpenApi("
	 *   summary: Get OpenAPI definition.
	 * ")
	 * @Apitte\Path("/meta")
	 * @Apitte\Method("GET")
	 */
	public function meta(ApiRequest $request, ApiResponse $response): ResponseInterface
	{
		$schema = $this->schemaBuilder->build()->toArray();

		$schema['components'] = ['securitySchemes'=>['AccessToken'=>['type'=>'apiKey','in'=>'query','name'=>'_access_token']]];
		$schema['security'] = [['AccessToken' => []]];

		return $response
			->withAddedHeader('Access-Control-Allow-Origin', '*')
			->writeJsonBody(
				$schema
			);
	}

}
