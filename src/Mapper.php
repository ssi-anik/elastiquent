<?php namespace Anik\Elastiquent;

abstract class Mapper
{
	protected $index;

	protected function getIndex () {
		return $this->index;
	}
}