<?php
/**
 * Created by PhpStorm.
 * User: icaro
 * Date: 31/07/2018
 * Time: 13:39
 */

namespace Hcode\Model;


class Model {
	private $values = [];
	
	public function __call($name, $arguments) {
		$method = substr($name, 0, 3);
		$fieldName = substr($name, 3, strlen($name));
		
		switch ($method) {
			case 'get':
				return $this->values[$fieldName] ?? null;
				break;
			
			case 'set':
				$this->values[$fieldName] = $arguments[0];
				break;
		}
	}
	
	public function setData(array $data = []) {
		foreach ($data as $key => $value) {
			$this->{'set' . $key}($value);
		}
	}
	
	public function getValues(): array {
		return $this->values;
	}
}