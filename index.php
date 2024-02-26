<?php
require_once 'vendor/autoload.php';
require_once 'class-mathabunga.php';
require_once 'class-twig.php';

$problem_args = Mathabunga::get_problems_args_from_get_request();
$problems     = Mathabunga::get_problems( $problem_args );


$context = array(
	'pages'             => Mathabunga::render_pages(
		array(
			'problems' => $problems,
			'layout'   => $problem_args['problem_layout'],
		)
	),
	'worksheet_options' => Mathabunga::render_worksheet_options( $problem_args ),
);
Twig::out( 'index.twig', $context );
