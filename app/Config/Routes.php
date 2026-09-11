<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('/produk', 'Produk::index');
$routes->get('/produk/detail/(:num)', 'Produk::detail/$1');
$routes->post('/produk/beli/(:num)', 'Marketplace::beli/$1', ['filter' => 'auth']);
$routes->group('', ['filter' => 'auth'], static function ($routes) {
	$routes->get('profil', 'Profile::index');
	$routes->get('profil/(:num)', 'Profile::index/$1');
	$routes->post('profil/bio', 'Profile::updateBio');
	$routes->post('profil/(:num)/follow', 'Profile::follow/$1');
	$routes->post('profil/(:num)/unfollow', 'Profile::unfollow/$1');
	$routes->get('chat', 'Chat::index');
	$routes->get('chat/(:num)', 'Chat::index/$1');
	$routes->get('chat/(:num)/messages', 'Chat::messages/$1');
	$routes->post('chat/(:num)/send', 'Chat::send/$1');
});
$routes->group('', ['filter' => 'auth'], static function ($routes) {
	$routes->get('saldo', 'Marketplace::saldo');
	$routes->post('saldo/topup', 'Marketplace::topup');
	$routes->get('seller', 'Seller::index');
	$routes->get('seller/tambah', 'Seller::tambah');
	$routes->post('seller/simpan', 'Seller::simpan');
	$routes->get('seller/edit/(:num)', 'Seller::edit/$1');
	$routes->post('seller/update/(:num)', 'Seller::update/$1');
	$routes->post('seller/hapus/(:num)', 'Seller::hapus/$1');
});
$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
	$routes->get('/', 'Admin::index');
	$routes->get('users', 'Admin::users');
	$routes->post('users/access/(:num)', 'Admin::updateUserAccess/$1');
	$routes->get('tambah', 'Admin::tambah');
	$routes->post('simpan', 'Admin::simpan');
	$routes->get('edit/(:num)', 'Admin::edit/$1');
	$routes->post('update/(:num)', 'Admin::update/$1');
	$routes->post('hapus/(:num)', 'Admin::hapus/$1');
});
$routes->get('/register', 'Auth::register');
$routes->post('/register/process', 'Auth::registerProcess');
$routes->get('/login', 'Auth::login');
$routes->post('/login/process', 'Auth::loginProcess');
$routes->get('/logout', 'Auth::logout');
