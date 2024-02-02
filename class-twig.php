<?php
/**
 * Twig helpers
 */
class Twig {

	/**
	 * An instance of Twig
	 *
	 * @var Twig\Environment
	 */
	public static $twig;

	/**
	 * Get an instance of this class
	 */
	public static function get_instance() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new static();
			$instance->setup_twig();
		}
		return $instance;
	}

	public function setup_twig() {
		$open_basedir = ini_get( 'open_basedir' );
		// $paths        = array_merge( $this->get_template_locations(), array() );
		$paths     = $this->get_template_locations();
		$root_path = '/';
		if ( $open_basedir ) {
			$root_path = null;
		}
		$twig_loader = new \Twig\Loader\FilesystemLoader( $paths, $root_path );
		$debug       = false;
		$twig        = new \Twig\Environment(
			$twig_loader,
			array(
				'debug'      => $debug,
				'autoescape' => false,
			)
		);
		if ( $debug ) {
			$twig->addExtension( new \Twig\Extension\DebugExtension() );
		}
		static::$twig = $twig;
	}

	/**
	 * Get paths of directories Twig should look for template files
	 *
	 * @return array List of locations for Twig to look for templates
	 */
	public function get_template_locations() {
		$theme_locations = array();
		$theme_dirs      = array( '/views', '/twig' );
		$roots           = array( __DIR__ );
		$roots           = array_map( 'realpath', $roots );
		$roots           = array_unique( $roots );
		foreach ( $roots as $root ) {
			if ( ! is_dir( $root ) ) {
				continue;
			}
			$theme_locations[] = $root;
			foreach ( $theme_dirs as $dirname ) {
				$theme_location = realpath( $root . $dirname );
				if ( is_dir( $theme_location ) ) {
					$theme_locations[] = $theme_location;
				}
			}
		}
		return $theme_locations;
	}

	/**
	 * Given a list of file names find the first template
	 * that exists and can be used for rendering
	 *
	 * @param  array $filenames List of template file names.
	 * @return string|false      Name of first template found or false if no templates are found
	 */
	public static function choose_template( $filenames = array() ) {
		$loader = static::$twig->getLoader();
		foreach ( $filenames as $filename ) {
			if ( $loader->exists( $filename ) ) {
				return $filename;
			}
		}
		return false;
	}

	/**
	 * Get the full path of the chosen template file
	 *
	 * @param  array $filenames List of template file names.
	 * @return string            Path of the chosen template file
	 */
	public static function get_template_path( $filenames = array() ) {
		$template_file_name = static::choose_template( $filenames );
		if ( empty( $template_file_name ) ) {
			return '';
		}
		$loader = static::$twig->getLoader();
		return $loader->getSourceContext( $template_file_name )->getPath();
	}

	/**
	 * Render a template using the provided data
	 *
	 * @param  array $filenames List of template file names.
	 * @param  array $data      Data to use when rendering the template.
	 * @return string           Rendered template
	 */
	public static function render( $filenames = array(), $data = array() ) {
		if ( ! is_array( $filenames ) ) {
			$filenames = array( $filenames );
		}
		$template_file_name = static::choose_template( $filenames );
		return static::$twig->render( $template_file_name, $data );
	}

	/**
	 * Helper method to echo the rendered output
	 *
	 * @param  array $filenames List of template file names.
	 * @param  array $data      Data to use when rendering the template.
	 */
	public static function out( $filenames = array(), $data = array() ) {
		echo static::render( $filenames, $data );
	}
}
Twig::get_instance();
