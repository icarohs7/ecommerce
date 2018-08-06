<?php
/**
 * Created by PhpStorm.
 * User: icaro
 * Date: 31/07/2018
 * Time: 17:51
 */

namespace Icarohs7;

abstract class NXUtils {
	public static function redirect(string $location, bool $endScriptAfterRedirection = true): void {
		header("Location: $location");
		if ($endScriptAfterRedirection) {
			exit;
		}
	}
}