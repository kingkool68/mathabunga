<?php
require_once 'vendor/autoload.php';
require_once 'class-mathabunga.php';
require_once 'class-twig.php';

$problems = Mathabunga::get_problems(
	array(
		'operations'         => array(
			'addition'    => array(
				'min' => 1,
				'max' => 12,
			),
			'subtraction' => array(
				'min' => 1,
				'max' => 12,
			),
		),
		'number_of_problems' => 1200,
	)
);

$context = array(
	'pages' => Mathabunga::render_pages(
		array(
			'problems' => $problems,
			'layout'   => 'vertical',
		)
	),
);
Twig::out( 'index.twig', $context );
