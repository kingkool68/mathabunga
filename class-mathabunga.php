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
	 * Default arguments for addition problems
	 *
	 * @var array
	 */
	public static $default_addition_args = array(
		'min'     => 0,
		'max'     => 10,
		'numbers' => 2,
	);

	/**
	 * Default arguments for subtraction problems
	 *
	 * @var array
	 */
	public static $default_subtraction_args = array(
		'min'     => 0,
		'max'     => 10,
		'numbers' => 2,
	);

	/**
	 * Default arguments for multiplication problems
	 *
	 * @var array
	 */
	public static $default_multiplication_args = array(
		'min'     => 0,
		'max'     => 10,
		'numbers' => 2,
	);

	/**
	 * Default arguments for division problems
	 *
	 * @var array
	 */
	public static $default_division_args = array(
		'min'            => 0,
		'max'            => 10,
		'numbers'        => 2,
		'show_remainder' => false,
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
	 * Render pages of math problems
	 *
	 * @param  array $args Arguments to modify what is rendered
	 */
	public static function render_pages( $args = array() ) {
		$defaults = array(
			'problems'              => array(),
			'layout'                => 'vertical',
			'max_problems_per_page' => null,
		);
		$args     = array_merge( $defaults, $args );
		if ( empty( $args['max_problems_per_page'] ) ) {
			$args['max_problems_per_page'] = 90;
			if ( $args['layout'] === 'horiztonal' ) {
				$args['max_problems_per_page'] = 120;
			}
		}
		$output = array();
		$chunks = array_chunk( $args['problems'], $args['max_problems_per_page'] );
		foreach ( $chunks as $problems ) {
			$output[] = static::render_page(
				array(
					'problems' => $problems,
					'layout'   => $args['layout'],
				)
			);
		}

		return $output;
	}

	/**
	 * Render a single page of math problems
	 *
	 * @param  array $args Arguments to modify what is rendered
	 */
	public static function render_page( $args = array() ) {
		$defaults = array(
			'problems' => array(),
			'layout'   => 'vertical',
		);
		$context  = array_merge( $defaults, $args );
		foreach ( $context['problems'] as $problem ) {
			$context['rendered_problems'][] = static::render_problem( (array) $problem );
		}
		return Twig::render( 'page-of-problems.twig', $context );
	}

	/**
	 * Render a single math problem
	 *
	 * @param  array $args Arguments to modify what is rendered
	 */
	public static function render_problem( $args = array() ) {
		$defaults = array(
			'digits'             => array(),
			'operation'          => '',
			'symbol'             => '',
			'symbol_html_entity' => '',
			'answer'             => 0,
		);
		$context  = array_merge( $defaults, $args );
		return Twig::render( 'problem.twig', $context );
	}

	/**
	 * Render worksheet options form
	 *
	 * @param  array $args Arguments to modify what is rendered
	 */
	public static function render_worksheet_options( $args = array() ) {
		$context = array_merge( static::get_problems_args_from_get_request(), $args );
		return Twig::render( 'worksheet-options.twig', $context );
	}

	/**
	 * Get a list of randomly generated math problems
	 *
	 * @param  array $args Arguments to modify what type and how many math problems are returned
	 */
	public static function get_problems( $args = array() ) {
		$defaults = array(
			'operations'         => array(
				'addition'       => array(),
				'subtraction'    => array(),
				'multiplication' => array(),
				'division'       => array(),
			),
			'number_of_problems' => 10,
		);
		$args     = array_merge( $defaults, $args );
		$output   = array();
		if ( empty( $args['operations'] ) ) {
			return $output;
		}
		$operations = array_keys( $args['operations'] );
		while ( $args['number_of_problems'] > 0 ) {
			$operation      = static::get_random_operation( $operations );
			$operation_args = $args['operations'][ $operation->name ];
			$operation_args = array( 'args' => $operation_args );
			$output[]       = call_user_func_array( $operation->callback, $operation_args );
			--$args['number_of_problems'];
		}
		return $output;
	}

	/**
	 * Get arguments for static::get_problems() based on values passed via GET request
	 */
	public static function get_problems_args_from_get_request() {
		$addition_args = static::$default_addition_args;
		if ( ! empty( $_GET['addition'] ) ) {
			$get_args = $_GET['addition'];
			if ( ! empty( $get_args['min'] ) ) {
				$addition_args['min'] = intval( $get_args['min'] );
			}
			if ( ! empty( $get_args['max'] ) ) {
				$addition_args['max'] = intval( $get_args['max'] );
			}
			if ( ! empty( $get_args['numbers'] ) ) {
				$addition_args['numbers'] = intval( $get_args['numbers'] );
			}
		}

		$subtraction_args = static::$default_subtraction_args;
		if ( ! empty( $_GET['subtraction'] ) ) {
			$get_args = $_GET['subtraction'];
			if ( ! empty( $get_args['min'] ) ) {
				$subtraction_args['min'] = intval( $get_args['min'] );
			}
			if ( ! empty( $get_args['max'] ) ) {
				$subtraction_args['max'] = intval( $get_args['max'] );
			}
			if ( ! empty( $get_args['numbers'] ) ) {
				$subtraction_args['numbers'] = intval( $get_args['numbers'] );
			}
		}

		$number_of_problems = 90;
		if ( ! empty( $_GET['number-of-problems'] ) ) {
			$number_of_problems = abs( intval( $_GET['number-of-problems'] ) );
		}

		$problem_layout = 'vertical';
		if ( ! empty( $_GET['problem-layout'] ) && $_GET['problem-layout'] === 'horizontal' ) {
			$problem_layout = 'horizontal';
		}

		$args = array(
			'number_of_problems' => $number_of_problems,
			'problem_layout'     => $problem_layout,
			'operations'         => array(
				'addition'    => $addition_args,
				'subtraction' => $subtraction_args,
				// 'multiplication' => array(),
				// 'division'       => array(),
			),
		);
		return $args;
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
		$args = array_merge( static::$default_addition_args, $args );

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
		$args = array_merge( static::$default_subtraction_args, $args );

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
		$args = array_merge( static::$default_multiplication_args, $args );

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
		$args = array_merge( static::$default_division_args, $args );

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
