<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/sliderloader.bundle.css',
	'js' => 'dist/sliderloader.bundle.js',
	'rel' => [
		'main.core',
		'calendar.sharing.deletedviewform',
	],
	'skip_core' => false,
];