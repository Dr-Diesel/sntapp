<?php declare(strict_types = 1);

namespace App\Domain\Api\Facade;

use App\Domain\Api\Request\CreateDeviceReqDto;
use App\Domain\Api\Response\DeviceResDto;
use App\Domain\Device\Device;
use App\Model\Database\EntityManagerDecorator;
use App\Model\Exception\Runtime\Database\EntityNotFoundException;

final class DevicesFacade
{

	public function __construct(private EntityManagerDecorator $em)
	{
	}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 * @return DeviceResDto[]
	 */
	public function findBy(array $criteria = [], array $orderBy = ['id' => 'ASC'], int $limit = 10, int $offset = 0): array
	{
		$entities = $this->em->getRepository(Device::class)->findBy($criteria, $orderBy, $limit, $offset);
		$result = [];

		foreach ($entities as $entity) {
			$result[] = DeviceResDto::from($entity);
		}

		return $result;
	}

	/**
	 * @return DeviceResDto[]
	 */
	public function findAll(int $limit = 10, int $offset = 0): array
	{
		return $this->findBy([], ['id' => 'ASC'], $limit, $offset);
	}

	/**
	 * @param mixed[] $criteria
	 * @param string[] $orderBy
	 */
	public function findOneBy(array $criteria, ?array $orderBy = null): DeviceResDto
	{
		$entity = $this->em->getRepository(Device::class)->findOneBy($criteria, $orderBy);

		if ($entity === null) {
			throw new EntityNotFoundException();
		}

		return DeviceResDto::from($entity);
	}

	public function findOne(int $id): DeviceResDto
	{
		return $this->findOneBy(['id' => $id]);
	}

	public function create(CreateDeviceReqDto $dto): Device
	{
		$device = new Device(
			$dto->name,
			$dto->serialNumber
		);

		$device->setCreatedAt();

		$this->em->persist($device);
		$this->em->flush($device);

		return $device;
	}

}
