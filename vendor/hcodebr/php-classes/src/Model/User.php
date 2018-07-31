<?php
/**
 * Created by PhpStorm.
 * User: icaro
 * Date: 31/07/2018
 * Time: 13:26
 */

namespace Hcode\Model;


use Hcode\DB\Sql;

class User extends Model {
	const SESSION = 'User';
	
	public static function login(string $login, string $password) {
		$sql = new Sql();
		
		$results = $sql->select('SELECT * FROM tb_users WHERE deslogin = :LOGIN', [
			'LOGIN' => $login
		]);
		
		if (count($results) === 0)
			self::fail();
		
		$data = $results[0];
		
		if (password_verify($password, $data['despassword']) === true) {
			$user = new User();
			
			$user->setData($data);
			
			$_SESSION[User::SESSION] = $user->getValues();
			
			return $user;
			
		} else {
			self::fail();
		}
	}
	
	public static function verifyLogin($inadmin = true) {
		if (
			!isset($_SESSION[User::SESSION])
			||
			!$_SESSION[User::SESSION]
			||
			!(int)$_SESSION[User::SESSION]['iduser']
			||
			(bool)$_SESSION[User::SESSION]['inadmin'] !== $inadmin
		) {
			header('Location: /admin/login');
			exit;
		}
	}
	
	public static function logout() {
		unset($_SESSION[User::SESSION]);
		//$_SESSION[User::SESSION] = null;
	}
	
	private static function fail() {
		throw new \Exception('Usuário inexistente ou senha inválida', 1);
	}
}