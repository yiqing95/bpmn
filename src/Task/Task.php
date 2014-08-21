<?php

/*
 * This file is part of KoolKode BPMN.
*
* (c) Martin Schröder <m.schroeder2007@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace KoolKode\BPMN\Task;

use KoolKode\Util\UUID;

class Task implements TaskInterface, \JsonSerializable
{
	protected $id;
	protected $executionId;
	protected $name;
	protected $activityId;
	protected $created;
	protected $claimDate;
	protected $assignee;
	
	protected $documentation = '';
	
	public function __construct(UUID $id, UUID $executionId, $name, $activityId, \DateTime $created, \DateTime $claimDate = NULL, $assignee = NULL)
	{
		$this->id = $id;
		$this->executionId = $executionId;
		$this->name = (string)$name;
		$this->activityId = (string)$activityId;
		$this->created = clone $created;
		$this->claimDate = ($claimDate === NULL) ? NULL : clone $claimDate;
		$this->assignee = ($assignee === NULL) ? NULL : (string)$assignee;
	}
	
	public function jsonSerialize()
	{
		return [
			'id' => (string)$this->id,
			'executionId' => (string)$this->executionId,
			'name' => $this->name,
			'activityId' => $this->activityId,
			'assignee' => $this->assignee,
			'creationDate' => $this->created->format(\DateTime::ISO8601),
			'claimDate' => ($this->claimDate === NULL) ? NULL : $this->claimDate->format(\DateTime::ISO8601)
		];
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getExecutionId()
	{
		return $this->executionId;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getDocumentation()
	{
		return $this->documentation;
	}
	
	public function setDocumentation($documentation = NULL)
	{
		$this->documentation = trim($documentation);
	}
	
	public function getActivityId()
	{
		return $this->activityId;
	}
	
	public function getCreated()
	{
		return clone $this->created;
	}
	
	public function isClaimed()
	{
		return $this->claimDate !== NULL;
	}
	
	public function getClaimDate()
	{
		return ($this->claimDate === NULL) ? NULL : clone $this->claimDate;
	}
	
	public function getAssignee()
	{
		return $this->assignee;
	}
}
