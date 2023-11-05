<?php declare(strict_types = 1);

namespace App\Domain\Device;

use App\Model\Database\Entity\AbstractEntity;
use App\Model\Database\Entity\TCreatedAt;
use App\Model\Database\Entity\TId;
use App\Model\Database\Entity\TUpdatedAt;
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

	public const DEVICE_MOWER = 'mower';
	public const DEVICE_CORRECTION_STATION = 'correction station';

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $name;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $serialNumber;

	/** @ORM\Column(type="string", length=255, nullable=TRUE, unique=false, columnDefinition="enum('mower','correction station')")
	 */
	private string $type;

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

	public function rename(string $name, string $surname): void
	{
		$this->name = $name;
		$this->serialNumber = $surname;
	}

	/**
	 * @return string
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType(string $type): void {
		$this->type = $type;
	}

}

enum DeviceType: string {

}
