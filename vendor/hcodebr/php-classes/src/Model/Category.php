<?php
/**
 * Created by PhpStorm.
 * User: icaro
 * Date: 31/07/2018
 * Time: 13:26
 */

namespace Hcode\Model;


use Hcode\DB\Sql;

class Category extends Model {
	
	public static function get(int $idcategory): Category {
		$sql = new Sql();
		$q = 'SELECT * FROM tb_categories WHERE idcategory = :idcategory';
		
		$results = $sql->select($q, [
			':idcategory' => $idcategory
		]);
		
		$category = new Category();
		$category->setData($results[0]);
		return $category;
	}
	
	public static function listAll() {
		$sql = new Sql();
		
		$q = 'SELECT * FROM tb_categories ORDER BY descategory';
		
		return $sql->select($q);
	}
	
	public static function updateFile() {
		$categories = self::listAll();
		
		$html = [];
		foreach ($categories as $category) {
			$html[] =
				'<li><a href="/categories/'
				. $category['idcategory']
				. '">'
				. $category['descategory']
				. '</a>'
				. '</li>'
				. "\n";
		}
		
		file_put_contents(
			$_SERVER['DOCUMENT_ROOT']
			. DIRECTORY_SEPARATOR
			. 'views'
			. DIRECTORY_SEPARATOR
			. 'categories-menu.html'
			, implode('', $html));
	}
	
	public function save() {
		$sql = new Sql();
		
		$q = 'CALL sp_categories_save(:idcategory, :descategory)';
		
		$results = $sql->select($q, [
			':idcategory' => $this->getidcategory(),
			':descategory' => $this->getdescategory()
		]);
		
		$this->setData($results[0]);
		self::updateFile();
	}
	
	public function delete() {
		$sql = new Sql();
		$sql->query('DELETE FROM tb_categories WHERE idcategory = :idcategory', [
			':idcategory' => $this->getidcategory()
		]);
		self::updateFile();
	}
}