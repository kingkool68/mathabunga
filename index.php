<?php
require_once 'vendor/autoload.php';
require_once 'class-mathabunga.php';
require_once 'class-twig.php';

$context = array(
	'problems' => Mathabunga::get_problems(
		array(
			'operations'         => array(
				'addition'    => array(
					'min' => 10,
					'max' => 1000,
				),
				'subtraction' => array(
					'min' => 1,
					'max' => 12,
				),
			),
			'number_of_problems' => 150,
			'problem_layout'     => 'horizontal',
		)
	),
	'layout'   => 'vertical',
);
Twig::out( 'index.twig', $context );
