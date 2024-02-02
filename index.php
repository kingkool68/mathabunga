<?php
require_once 'vendor/autoload.php';
require_once 'class-mathabunga.php';
require_once 'class-twig.php';

$context = array(
	'problems' => Mathabunga::get_problems(
		array(
			'number_of_problems' => 90,
			'problem_layout'     => 'vertical',
		)
	),
	'layout' => 'vertical',
);
Twig::out( 'index.twig', $context );
