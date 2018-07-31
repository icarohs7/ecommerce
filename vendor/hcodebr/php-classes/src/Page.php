<?php
/**
 * Created by PhpStorm.
 * User: icaro
 * Date: 24/07/2018
 * Time: 12:54
 */

namespace Hcode;

use Rain\Tpl;


class Page {
	private $tpl;
	private $options = [];
	private $defaults = [
		'header'=>true,
		'footer'=>true,
		'data' => []
	];
	
	
	public function __construct(array $opts = [], string $tpl_dir = '/views/') {
		
		$this->options = array_merge($this->defaults, $opts);
		
		$config = [
			"tpl_dir"   => $_SERVER['DOCUMENT_ROOT'] . $tpl_dir,
			"cache_dir" => $_SERVER['DOCUMENT_ROOT'] . '/views-cache/',
			"debug"     => false
		];
		
		Tpl::configure($config);
		
		$this->tpl = new Tpl();
		
		$this->setData($this->options['data']);
		
		if ($this->options['header'] === true) $this->tpl->draw('header');
	}
	
	public function setTpl(string $name, array $data = [], bool $returnHTML = false) {
		$this->setData($data);
		
		return $this->tpl->draw($name, $returnHTML);
	}
	
	public function __destruct() {
		if ($this->options['footer'] === true) $this->tpl->draw('footer');
	}
	
	private function setData(array $data = []) {
		foreach ($data as $key => $value) {
			$this->tpl->assign($key, $value);
		}
	}
}