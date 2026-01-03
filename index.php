<?php
require_once 'vendor/autoload.php';
require_once 'class-mathabunga.php';
require_once 'class-twig.php';

$problems  = array();
$operators = array( '+', '-', '*', '/' );
for ( $i = 0; $i < 12; $i++ ) {
	$operation  = $operators[ array_rand( $operators ) ];
	$n1         = rand( 1, 12 );
	$n2         = rand( 1, 12 );
	$problems[] = (object) array(
		'operation' => $operation,
		'n1'        => $n1,
		'n2'        => $n2,
	);
}
$context = array(
	'problems' => $problems,
);
Twig::out( 'index.twig', $context );
