<?php declare(strict_types = 1);

namespace App\Domain\Api\Response;

use App\Domain\Device\Device;
use DateTimeInterface;

final class DeviceResDto
{

	public int $id;

	public string $name;

	public string $serialNumber;

	public ?DateTimeInterface $updatedAt = null;

	public ?DateTimeInterface $createdAt = null;

	public static function from(Device $device): self
	{
		$self = new self();
		$self->id = $device->getId();
		$self->name = $device->getName();
		$self->serialNumber = $device->getSerialNumber();
		$self->updatedAt = $device->getUpdatedAt();
		$self->createdAt = $device->getCreatedAt();

		return $self;
	}

}
