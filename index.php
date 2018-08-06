<?php
session_start();
require_once('vendor/autoload.php');
require_once('src/AdminRenderer.php');
require_once('src/UserRenderer.php');

use Hcode\Model\Category;
use Hcode\Model\User;
use Hcode\Page;
use Hcode\PageAdmin;
use Icarohs7\NXUtils;
use Slim\Slim;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function () { UserRenderer::renderIndex(); });

$app->get('/admin', function () { AdminRenderer::renderIndex(); });

$app->get('/admin/login', function () { AdminRenderer::renderLogin(); });

$app->post('/admin/login', function () {
	User::login($_POST['login'], $_POST['password']);
	NXUtils::redirect('/admin');
});

$app->get('/admin/logout', function () {
	User::logout();
	NXUtils::redirect('/admin/login');
});

$app->get('/admin/users', function () { AdminRenderer::renderUsers(); });

$app->get('/admin/users/create', function () { AdminRenderer::renderCreateUser(); });

$app->get('/admin/users/:iduser/delete', function ($iduser) {
	User::verifyLogin();
	
	$user = User::get($iduser);
	$user->delete();
	
	NXUtils::redirect('/admin/users');
});

$app->get('/admin/users/:iduser', function ($iduser) { AdminRenderer::renderSingleUser($iduser); });

$app->post('/admin/users/create', function () {
	User::verifyLogin();
	
	$user = new User();
	$_POST['inadmin'] = $_POST['inadmin'] ?? 0;
	$user->setData($_POST);
	$user->save();
	
	NXUtils::redirect('/admin/users');
});

$app->post('/admin/users/:iduser', function ($iduser) {
	User::verifyLogin();
	
	$user = User::get($iduser);
	$user->setData($_POST);
	$user->update();
	
	NXUtils::redirect('/admin/user');
});

$app->get('/admin/forgot', function () { AdminRenderer::renderForgotPassword(); });

$app->post('/admin/forgot', function () {
	$user = User::getForgot($_POST['email']);
	NXUtils::redirect('/admin/forgot/sent');
});

$app->get('/admin/forgot/sent', function () { AdminRenderer::renderForgotPasswordEmailSent(); });

$app->get('/admin/forgot/reset', function () { AdminRenderer::renderPasswordReset(); });

$app->post('/admin/forgot/reset', function () {
	$forgot = User::validForgotDecrypt($_POST['code']);
	
	User::setForgotUsed($forgot['idrecovery']);
	$user = User::get($forgot['iduser']);
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT, [
		'cost' => 12
	]);
	$user->setPassword($password);
	
	$page = new PageAdmin([
		'header' => false,
		'footer' => false
	]);
	
	$page->setTpl('forgot-reset-success');
});

$app->get('/admin/categories', function () {
	User::verifyLogin();
	$categories = Category::listAll();
	
	$page = new PageAdmin();
	$page->setTpl('categories', [
		'categories' => $categories
	]);
});

$app->get('/admin/categories/create', function () {
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl('categories-create');
});

$app->post('/admin/categories/create', function () {
	User::verifyLogin();
	$category = new Category();
	$category->setData($_POST);
	$category->save();
	NXUtils::redirect('/admin/categories');
});

$app->get('/admin/categories/:idcategory/delete', function ($idcategory) {
	User::verifyLogin();
	$category = Category::get($idcategory);
	$category->delete();
	NXUtils::redirect('/admin/categories');
});

$app->get('/admin/categories/:idcategory', function ($idcategory) {
	User::verifyLogin();
	$category = Category::get($idcategory);
	
	$page = new PageAdmin();
	$page->setTpl('categories-update', [
		'category' => $category->getValues()
	]);
});

$app->post('/admin/categories/:idcategory', function ($idcategory) {
	User::verifyLogin();
	$category = Category::get($idcategory);
	$category->setData($_POST);
	$category->save();
	NXUtils::redirect('/admin/categories');
});

$app->get('/categories/:idcategory', function ($idcategory) {
	$category = Category::get($idcategory);
	
	$page = new Page();
	$page->setTpl('category', [
		'category' => $category->getValues(),
		'products' => []
	]);
});

$app->run();