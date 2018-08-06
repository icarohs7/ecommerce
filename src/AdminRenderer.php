<?php

use Hcode\Model\User;
use Hcode\PageAdmin;

/**
 * Created by PhpStorm.
 * User: icaro
 * Date: 05/08/2018
 * Time: 21:55
 */
class AdminRenderer {
	public static function renderIndex() {
		User::verifyLogin();
		$page = new PageAdmin();
		$page->setTpl('index');
	}
	
	public static function renderLogin() {
		$page = new PageAdmin([
			'header' => false,
			'footer' => false
		]);
		
		$page->setTpl('login');
	}
	
	public static function renderSingleUser(int $iduser) {
		User::verifyLogin();
		
		$user = User::get($iduser);
		
		$page = new PageAdmin();
		$page->setTpl('users-update', [
			'user' => $user->getValues()
		]);
	}
	
	public static function renderUsers() {
		User::verifyLogin();
		
		$users = User::listAll();
		
		$page = new PageAdmin();
		$page->setTpl('users', [
			'users' => $users
		]);
	}
	
	public static function renderCreateUser() {
		User::verifyLogin();
		$page = new PageAdmin();
		$page->setTpl('users-create');
	}
	
	public static function renderForgotPassword() {
		$page = new PageAdmin([
			'header' => false,
			'footer' => false
		]);
		
		$page->setTpl('forgot');
	}
	
	public static function renderForgotPasswordEmailSent() {
		$page = new PageAdmin([
			'header' => false,
			'footer' => false
		]);
		
		$page->setTpl('forgot-sent');
	}
	
	public static function renderPasswordReset() {
		$user = User::validForgotDecrypt($_GET['code']);
		
		$page = new PageAdmin([
			'header' => false,
			'footer' => false
		]);
		
		$page->setTpl('forgot-reset', [
			'name' => $user['desperson'],
			'code' => $_GET['code']
		]);
	}
}