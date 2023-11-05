<?php declare(strict_types = 1);

namespace App\Domain\Device;

use App\Model\Database\Repository\AbstractRepository;

/**
 * @method Device|NULL find($id, ?int $lockMode = NULL, ?int $lockVersion = NULL)
 * @method Device|NULL findOneBy(array $criteria, array $orderBy = NULL)
 * @method Device[] findAll()
 * @method Device[] findBy(array $criteria, array $orderBy = NULL, ?int $limit = NULL, ?int $offset = NULL)
 * @extends AbstractRepository<Device>
 */
class DeviceRepository extends AbstractRepository
{

	public function findOneByEmail(string $email): ?Device
	{
		return $this->findOneBy(['email' => $email]);
	}

}
