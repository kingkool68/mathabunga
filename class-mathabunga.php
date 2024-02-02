<?php
/**
 * For generating math problems
 */
class Mathabunga {

	/**
	 * List of math operations allowed
	 *
	 * @var array
	 */
	public static $allowed_operations = array(
		'addition',
		'subtraction',
		'multiplication',
		'division',
	);

	/**
	 * Get an instance of this class
	 */
	public static function get_instance() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new static();
		}
		return $instance;
	}

	/**
	 * Get a list of randomly generated math problems
	 *
	 * @param  array $args Arguments to modify what type and how many math problems are returned
	 */
	public static function get_problems( $args = array() ) {
		$defaults = array(
			'operations'         => array(
				'addition',
				'subtraction',
				'multiplication',
				'division',
			),
			'number_of_problems' => 10,
		);
		$args     = array_merge( $defaults, $args );
		$output   = array();
		if ( empty( $args['operations'] ) ) {
			return $output;
		}
		while ( $args['number_of_problems'] > 0 ) {
			$operation = static::get_random_operation( $args['operations'] );
			$output[]  = call_user_func_array( $operation->callback, array() );
			--$args['number_of_problems'];
		}
		return $output;
	}

	/**
	 * Get a random math operation from a given list of math operations
	 *
	 * @param  array $operations Math operations to pick from
	 */
	public static function get_random_operation( $operations = array() ) {
		// Validate operations

		if ( empty( $operations ) ) {
			$operations = static::$allowed_operations;
		} else {
			// Validate given operations
			$operations = array_map( 'strtolower', $operations );
			$operations = array_unique( $operations );
			$operations = array_intersect( $operations, static::$allowed_operations );
			// Reset the array keys to be in numerical order again
			$operations = array_values( $operations );
		}

		$max_num_of_operations = count( $operations ) - 1;
		$operation_index       = rand( 0, $max_num_of_operations );
		$operation_name        = $operations[ $operation_index ];
		return (object) array(
			'name'     => $operation_name,
			'callback' => array( 'Mathabunga', 'get_' . $operation_name . '_problem' ),
		);
	}

	/**
	 * Generate an addition problem
	 *
	 * @param  array $args Arguments
	 */
	public static function get_addition_problem( $args = array() ) {
		$defaults = array(
			'min'     => 0,
			'max'     => 10,
			'numbers' => 2,
		);
		$args     = array_merge( $defaults, $args );

		$answer = 0;
		$digits = array();
		while ( $args['numbers'] > 0 ) {
			$randum_num = rand( $args['min'], $args['max'] );
			$digits[]   = $randum_num;
			$answer    += $randum_num;
			--$args['numbers'];
		}
		return (object) array(
			'digits'             => $digits,
			'operation'          => 'addition',
			'symbol'             => '+',
			'symbol_html_entity' => '&plus;',
			'answer'             => $answer,
		);
	}

	/**
	 * Generate a subtraction problem
	 *
	 * @param  array $args Arguments
	 */
	public static function get_subtraction_problem( $args = array() ) {
		$defaults = array(
			'min'     => 0,
			'max'     => 10,
			'numbers' => 2,
		);
		$args     = array_merge( $defaults, $args );

		$answer = 0;
		$digits = array();
		while ( $args['numbers'] > 0 ) {
			$digits[] = rand( $args['min'], $args['max'] );
			--$args['numbers'];
		}
		rsort( $digits );
		$answer = $digits[0] * 2;
		foreach ( $digits as $digit ) {
			$answer -= $digit;
		}
		return (object) array(
			'digits'             => $digits,
			'operation'          => 'subtraction',
			'symbol'             => '-',
			'symbol_html_entity' => '&minus;',
			'answer'             => $answer,
		);
	}

	/**
	 * Generate a multiplication problem
	 *
	 * @param  array $args Arguments
	 */
	public static function get_multiplication_problem( $args = array() ) {
		$defaults = array(
			'min'     => 0,
			'max'     => 12,
			'numbers' => 2,
		);
		$args     = array_merge( $defaults, $args );

		$answer = 1;
		$digits = array();
		while ( $args['numbers'] > 0 ) {
			$randum_num = rand( $args['min'], $args['max'] );
			$digits[]   = $randum_num;
			$answer    *= $randum_num;
			--$args['numbers'];
		}
		return (object) array(
			'digits'             => $digits,
			'operation'          => 'multiplication',
			'symbol'             => 'x',
			'symbol_html_entity' => '&times;',
			'answer'             => $answer,
		);
	}

	/**
	 * Generate a division problem
	 *
	 * @param  array $args Arguments
	 */
	public static function get_division_problem( $args = array() ) {
		$defaults = array(
			'min'            => 1,
			'max'            => 12,
			'numbers'        => 2,
			'show_remainder' => false,
		);
		$args     = array_merge( $defaults, $args );

		$digits = array();
		$answer = 1;
		while ( $args['numbers'] > 0 ) {
			$randum_num = rand( $args['min'], $args['max'] );
			$digits[]   = $randum_num;
			$answer    *= $randum_num;
			--$args['numbers'];
		}
		$digits[] = $answer;
		rsort( $digits );
		$answer = array_pop( $digits );

		if ( $args['show_remainder'] ) {
			$digits[0] += rand( 0, $digits[1] );
			$answer     = floor( $digits[0] / $digits[1] );
			$answer     = $answer . 'r' . ( $digits[0] % $digits[1] );
		}
		return (object) array(
			'digits'             => $digits,
			'operation'          => 'division',
			'symbol'             => '÷',
			'symbol_html_entity' => '&divide;',
			'answer'             => $answer,
		);
	}
}
Mathabunga::get_instance();
