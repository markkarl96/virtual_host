<?php

	$uLine = "\e[4m";
	$cyan = "\e[36m";
	$lCyan = "\e[96m";
	$green = "\e[32m";
	$lGreen = "\e[92m";
	$lBlue = "\e[94m"; // !
	$yellow = "\e[33m";
	$lYellow = "\e[93m";
	$lRed = "\e[91m";
	$default = "\e[39m";
	$norm = "\e[24m";

	$lang = null;
	
	function printString($value = '', $color = null): void
	{
		echo $color . $value . "\e[0m" . PHP_EOL;
	}

	// C:\Windows\System32\drivers\etc\hosts
	// 127.0.0.1 $value
	// 127.0.0.1 www.$value
	function getHostsData($value)
	{
		$data = <<<EOT

127.0.0.1 $value
127.0.0.1 www.$value
EOT;
		return $data;
	}
	
	// WINDOWS 8.1 +
	function getXamppHostData($value)
	{
		$xamppPath = realpath('./htdocs') ?? null;
		if (isset($xamppPath)) {
			$data = <<<EOT

<VirtualHost *:80>
    ServerAdmin webmaster@$value
    DocumentRoot "$xamppPath/$value"
    ServerName $value
    ServerAlias www.$value
    ErrorLog "logs/dummy-host.example.com-error.log"
    CustomLog "logs/dummy-host.example.com-access.log" common
</VirtualHost>
EOT;
		return $data;
		}
	}

	$getStart = <<<EOT
$lCyan
Plugin for adding a new virtual host
for a local XAMPP server$default
-------------------------------------
  ______           ___  ___    
 |   __  \         \  \/  /
 |  |__|  |         \  \ /
 |       /          /\  \
 |   __  \         /  \  \
 |  |__|  |  __   /  / \  \   __  
 |________/   /  /__/   \__\   /  \e[93m 0.2.0
$lCyan
Плагин добавления нового виртуального хоста
для локального сервера XAMPP$default
-------------------------------------

EOT;

	function setLang()
	{
		global $lang;
		$language = readline('Русский - 1 | English - 2 : ');
		$lang = $language ?? null;
	}

	echo $getStart;

	setLang();
	// echo $lang;

	function checkPathXampp()
	{
		global $lang;
		if (!isset($lang) || $lang > 2) {
			setLang();
		}
		if ($lang == 1) {
			if (!is_dir('apache/conf/extra')) {
				printString("\e[91mПереместите файл vh.php в папку XAMPP и запустите его оттуда");
				exit;
			} else {
				return true;
			}
			
		} elseif($lang == 2) {
			if (!is_dir('apache/conf/extra')) {
				printString("\e[91mMove the file vh.php to the XAMPP folder and run it there");
				exit;
			} else {
				return true;
			}
		} else {
			exit();
		}
	}

	function getXamppData()
	{
		if (checkPathXampp()) {
			$xVhost = 'apache/conf/extra/httpd-vhosts.conf';
			return $xVhost;
		}
	}

	function setNewHost($color, $xVhost, $color2, $color3)
	{
		global $lang;
		if ($lang == 1) {
			$host = readline('Введите название локального сайта, который создаёте: ');
			printString('------------------------');

			$newDir = readline('Создать папку для нового проекта? Да - 1 | Нет - 2 : ');
			printString('------------------------');

			if ($host) {
				if ($newDir == 1) {
					mkdir("htdocs/$host");
				}
				file_put_contents('C:\Windows\System32\drivers\etc\hosts', getHostsData($host), FILE_APPEND);
				file_put_contents($xVhost, getXamppHostData($host), FILE_APPEND);
				printString('Добавление данных в hosts...', $color3);
				sleep(1);
				printString('Добавление данных для локального сервера...', $color3);
				sleep(1);
				printString('Данные нового виртуального хоста добавлены', $color2);
			} else {
				printString('Название не указано...', $color);
				setNewHost($color, $xVhost, $color2, $color3);
			}

		} else {

			$host = readline('Enter the name of the local site: ');
			printString('------------------------');

			$newDir = readline('Create a folder for a new project? Yes - 1 | No - 2 : ');
			printString('------------------------');

			if ($host) {
				if ($newDir == 1) {
					mkdir("htdocs/$host");
				}
				file_put_contents('C:\Windows\System32\drivers\etc\hosts', getHostsData($host), FILE_APPEND);
				file_put_contents($xVhost, getXamppHostData($host), FILE_APPEND);
				printString('Adding data to the hosts file...', $color3);
				sleep(1);
				printString('Adding data for the local server...', $color3);
				sleep(1);
				printString('The data of the new virtual host has been added', $color2);
			} else {
				printString('The name is not specified...', $color);
				setNewHost($color, $xVhost, $color2, $color3);
			}
		}

	}

	$xamppVhost = getXamppData();

	setNewHost($lYellow, $xamppVhost, $lBlue, $lCyan);