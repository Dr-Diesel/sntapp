<?php declare(strict_types = 1);

namespace App\Module\V1;

use Apitte\Core\Annotation\Controller as Apitte;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Exception\Api\ServerErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Domain\Api\Facade\DevicesFacade;
use App\Domain\Api\Request\CreateDeviceReqDto;
use App\Domain\Api\Response\DeviceResDto;
use App\Domain\Api\Response\UserResDto;
use App\Model\Exception\Logic\InvalidArgumentException;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;
use App\Model\Utils\Caster;
use Doctrine\DBAL\Exception\DriverException;
use Nette\Http\IResponse;

/**
 * @Apitte\Path("/devices")
 * @Apitte\Tag("Devices")
 */
class DevicesController extends BaseV1Controller
{

	private DevicesFacade $devicesFacade;

	public function __construct(DevicesFacade $devicesFacade)
	{
		$this->devicesFacade = $devicesFacade;
	}

	/**
	 * @Apitte\OpenApi("
	 *   summary: List devices.
	 * ")
	 * @Apitte\Path("/")
	 * @Apitte\Method("GET")
	 * @Apitte\RequestParameters({
	 * 		@Apitte\RequestParameter(name="limit", type="int", in="query", required=false, description="Data limit"),
	 * 		@Apitte\RequestParameter(name="offset", type="int", in="query", required=false, description="Data offset")
	 * })
	 * @return UserResDto[]
	 */
	public function index(ApiRequest $request): array
	{
		return $this->devicesFacade->findAll(
			Caster::toInt($request->getParameter('limit', 10)),
			Caster::toInt($request->getParameter('offset', 0))
		);
	}

	/**
	 * @Apitte\OpenApi("
	 *   summary: Get device by id.
	 * ")
	 * @Apitte\Path("/{id}")
	 * @Apitte\Method("GET")
	 * @Apitte\RequestParameters({
	 *      @Apitte\RequestParameter(name="id", in="path", type="int", description="Device ID")
	 * })
	 */
	public function byId(ApiRequest $request): DeviceResDto
	{
		try {
			return $this->devicesFacade->findOne(Caster::toInt($request->getParameter('id')));
		} catch (EntityNotFoundException $e) {
			throw ClientErrorException::create()
				->withMessage('Device not found')
				->withCode(IResponse::S404_NotFound);
		}
	}

	/**
	 * @Apitte\OpenApi("
	 *   summary: Create new device.
	 * ")
	 * @Apitte\Path("/")
	 * @Apitte\Method("PUT")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\CreateDeviceReqDto")
	 */
	public function create(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/** @var CreateDeviceReqDto $dto */
		$dto = $request->getParsedBody();

		try {
			$this->devicesFacade->validateDto($dto);
		} catch (InvalidArgumentException $e) {
			throw ClientErrorException::create()
				->withMessage('Invalid request: '.$e->getMessage())
				->withCode(IResponse::S400_BadRequest);
		}

		try {
			$this->devicesFacade->create($dto);

			return $response->withStatus(IResponse::S201_Created)
				->withHeader('Content-Type', 'application/json');

		} catch (InvalidArgumentException $e) {
			throw ClientErrorException::create()
				->withMessage('Invalid request: '.$e->getMessage())
				->withCode(IResponse::S400_BadRequest);
		} catch (DriverException $e) {
			throw ServerErrorException::create()
				->withMessage('Cannot create device ['.$e->getCode().']')
				->withPrevious($e);
		}
	}

	/**
	 * @Apitte\OpenApi("
	 *   summary: Update device.
	 * ")
	 * @Apitte\Path("/{id}")
	 * @Apitte\Method("POST")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\CreateDeviceReqDto")
	 */
	public function update(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		$id = Caster::toInt($request->getParameter('id'));

		/** @var CreateDeviceReqDto $dto */
		$dto = $request->getParsedBody();


		try {
			$this->devicesFacade->validateDto($dto);
		} catch (InvalidArgumentException $e) {
			throw ClientErrorException::create()
				->withMessage('Invalid request: '.$e->getMessage())
				->withCode(IResponse::S400_BadRequest);
		}

		try {
			$this->devicesFacade->update($id, $dto);
			return $response->withStatus(IResponse::S200_OK)
				->withHeader('Content-Type', 'application/json');
		} catch (EntityNotFoundException $e) {
			throw ClientErrorException::create()
				->withMessage('Device not found')
				->withCode(IResponse::S404_NotFound);
		} catch (InvalidArgumentException $e) {
			throw ClientErrorException::create()
				->withMessage('Invalid request: '.$e->getMessage())
				->withCode(IResponse::S400_BadRequest);
		} catch (DriverException $e) {
			throw ServerErrorException::create()
				->withMessage('Cannot update device ['.$e->getCode().']')
				->withPrevious($e);
		}
	}

	/**
	 * @Apitte\OpenApi("
	 *   summary: Delete device.
	 * ")
	 * @Apitte\Path("/{id}")
	 * @Apitte\Method("DELETE")
	 */
	public function delete(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		$id = Caster::toInt($request->getParameter('id'));

		try {
			$this->devicesFacade->delete($id);
			return $response->withStatus(IResponse::S200_OK)
				->withHeader('Content-Type', 'application/json');
		} catch (EntityNotFoundException $e) {
			throw ClientErrorException::create()
				->withMessage('Device not found')
				->withCode(IResponse::S404_NotFound);
		}
	}

}
