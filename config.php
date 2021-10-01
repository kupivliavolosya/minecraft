<?php
if ( ! defined ( "INCLUDE_CHECK" ) ) die ("Не получилось посоны");

$config = array (
	'title' => 'Заголовок сайта',
	
	'servers' => array ( // Если у вас один сервер - ничего не добовляйте, только измените
		array (
			'name' => 'Имя сервера 1',
			'host' => '188.165.192.125',
			'port' => 25565,
			'groups' => array ( 1, 2 ),
			'cart' => array ( // Тип таблицы shopping cart 
				'table' => 'shoppingcart',
				'player' => 'player',
				'type' => 'type',
				'item' => 'item',
				'amount' => 'amount',
			),
		),
		array (
			'name' => 'Имя сервера 2',
			'host' => '5.135.160.165',
			'port' => 25565,
			'groups' => array ( 3 ),
			'cart' => array ( // Тип таблицы shopping cart 
				'table' => 'shoppingcart',
				'player' => 'player',
				'type' => 'type',
				'item' => 'item',
				'amount' => 'amount',
			),
		),
	),
	
	'record_file' => './record.txt', //Файл рекордов
	
	'db' => array (
		'host' => 'localhost', // Хост базы данных
		'user' => 'root', // Пользователь базы данных
		'name' => 'blog', // Имя базы данных
		'pass' => '', // Пароль базы данных
	),
	'unitpay' => array ( // настройки unitpay 
		'project_id' => '', //Ваш ID проекта в unitpay
		'key' => '', // Ваш ключ
	),
	'message' => array ( // советую не трогать
		'fail' => 'Ошибка',
		'success' => 'Успешно',
	),
	'domain' => 'http://public.site.com', // Ссылка на ваш сайт. Без слэша в конце
	'groups' => array ( // name - имя, price - цена, perm - имя группы в permissions, time - Срок действия. 0 - вечный
		1 => array (
			'name' => 'Вип',
			'price' => 100,
			'perm' => 'vip',
			'time' => 0,
		),
		2 => array (
			'name' => 'Вип2',
			'price' => 3100,
			'perm' => '3vip',
			'time' => 30,
		),
		3 => array (
			'name' => 'Вип3',
			'price' => 2100,
			'perm' => '1vip',
			'time' => 30,
		),
	),
);