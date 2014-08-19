<?php

/*
 * This file is part of KoolKode BPMN.
 *
 * (c) Martin Schröder <m.schroeder2007@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KoolKode\BPMN;

use KoolKode\BPMN\Delegate\DelegateExecutionInterface;
use KoolKode\BPMN\Task\TaskInterface;
use KoolKode\BPMN\Test\BusinessProcessTestCase;
use KoolKode\BPMN\Test\ServiceTaskHandler;

class ScriptTaskTest extends BusinessProcessTestCase
{
	public function provideNumbers()
	{
		return [
			[3, 5, 8],
			[3, -5, -2],
			[3.5, 5.5, 9]
		];
	}
	
	/**
	 * @dataProvider provideNumbers
	 */
	public function testCanAddNumbers($a, $b, $result)
	{
		$this->deployFile('ScriptTaskTest.bpmn');
		
		$process = $this->runtimeService->startProcessInstanceByKey('main', NULL, [
			'foo' => 'bar'
		]);
		
		$this->runtimeService->setExecutionVariable($process->getId(), 'expected', $result);
		$this->assertEquals([
			'foo' => 'bar',
			'expected' => $result
		], $this->runtimeService->getExecutionVariables($process->getId()));
		
		$task = $this->taskService->createTaskQuery()->findOne();
		$this->assertTrue($task instanceof TaskInterface);
		
		$this->taskService->complete($task->getId(), [
			'a' => $a,
			'b' => $b
		]);
		
		$this->assertEquals(0, $this->runtimeService->createExecutionQuery()->count());
	}
	
	/**
	 * @ServiceTaskHandler("ServiceTask_1", processKey = "main")
	 * 
	 * @param DelegateExecution $execution
	 */
	public function verifyNumbersAdded(DelegateExecutionInterface $execution)
	{
		$this->assertEquals($execution->getVariable('expected'), $execution->getVariable('result'));
	}
}
