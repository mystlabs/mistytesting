<?php

namespace MistyTesting;

abstract class UnitTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->initTest();
		$this->before();
	}

	public function tearDown()
	{
		$this->after();
		$this->clearTest();
	}

	public function initTest(){}
	public function before(){}
	public function after(){}
	public function clearTest(){}
}