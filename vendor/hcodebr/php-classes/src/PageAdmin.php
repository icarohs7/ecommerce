<?php
/**
 * Created by PhpStorm.
 * User: icaro
 * Date: 24/07/2018
 * Time: 18:26
 */

namespace Hcode;


class PageAdmin extends Page {
	public function __construct(array $opts = [], string $tpl_dir = '/views/admin/') {
		parent::__construct($opts, $tpl_dir);
	}
}