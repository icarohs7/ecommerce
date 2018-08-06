<?php

use Hcode\Page;

/**
 * Created by PhpStorm.
 * User: icaro
 * Date: 05/08/2018
 * Time: 21:56
 */
class UserRenderer {
	public static function renderIndex() {
		$page = new Page();
		$page->setTpl('index');
	}
}