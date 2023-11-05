<?php declare(strict_types = 1);

namespace App\Domain\Device;

use App\Model\Database\Entity\AbstractEntity;
use App\Model\Database\Entity\TCreatedAt;
use App\Model\Database\Entity\TId;
use App\Model\Database\Entity\TUpdatedAt;
use App\Model\Exception\Logic\InvalidArgumentException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="DeviceRepository")
 * @ORM\Table(name="`device`")
 * @ORM\HasLifecycleCallbacks
 */
class Device extends AbstractEntity
{

	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	public const DEVICE_TYPE_MOWER = 'mower';

	public const DEVICE_TYPE_CORRECTION_STATION = 'correction station';

	public const DEVICE_TYPES = [self::DEVICE_TYPE_MOWER, self::DEVICE_TYPE_CORRECTION_STATION];

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $name;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $serialNumber;

	/** @ORM\Column(type="string", length=255, nullable=TRUE, unique=false, columnDefinition="enum('mower','correction station')")
	 */
	private string $deviceType;

	public function __construct(string $name, string $serialNumber)
	{
		$this->name = $name;
		$this->serialNumber = $serialNumber;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getSerialNumber(): string
	{
		return $this->serialNumber;
	}

	/**
	 * @return string
	 */
	public function getDeviceType(): string {
		return $this->deviceType;
	}

	/**
	 * @param string $deviceType
	 */
	public function setDeviceType(string $deviceType): void {
		if (!in_array($deviceType, self::DEVICE_TYPES, true)) {
			throw new InvalidArgumentException(sprintf('Unsupported deviceType "%s"', $deviceType));
		}

		$this->deviceType = $deviceType;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name): void {
		$this->name = $name;
	}

	/**
	 * @param string $serialNumber
	 */
	public function setSerialNumber(string $serialNumber): void {
		$this->serialNumber = $serialNumber;
	}

}
