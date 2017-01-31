<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       https://www.nevma.gr
 * @since      1.0.0
 *
 * @package    Wc_Added_To_Cart_Notification
 * @subpackage Wc_Added_To_Cart_Notification/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Wc_Added_To_Cart_Notification
 * @subpackage Wc_Added_To_Cart_Notification/includes
 * @author     Nevma <info@nevma.gr>
 */
class WCATCN_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. he priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. he priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;

	}

	/**
     * Retrieves a template part
     *
     * @since v1.0.0
     *
     * @param string $slug
     * @param string $name Optional. Default null
     *
     */
    public static function get_template( $slug, $name = null, $load = true ) {
        
        // Setup possible parts
        $templates = array();

        if ( isset( $name ) ) {

            $templates[] = $slug . '-' . $name . '.php';

        }
        $templates[] = $slug . '.php';

        // Return the part that is found
        return self::locate_template( $templates, $load, false);
    }

    /**
     * Retrieve the name of the highest priority template file that exists.
     *
     * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
     * inherit from a parent theme can just overload a file. If the template is
     * not found in either of those, it looks in the plugin directory last.
     *
     * @since v1.0.0
     *
     * @param string|array $template_names Template file(s) to search for, in order.
     * @param bool $load If true the template file will be loaded if it is found.
     * @param bool $require_once Whether to require_once or require. Default true.
     *                            Has no effect if $load is false.
     * @return string The template filename if one is located.
     */
    public static function locate_template( $template_names, $load = false, $require_once = true) {

        // No file found yet
        $located = false;

        // Try to find a template file
        foreach ( (array) $template_names as $template_name ) {

            // Continue if template is empty
            if ( empty( $template_name ) ) {

                continue;

            }

            // Trim off any slashes from the template name
            $template_name = ltrim( $template_name, '/' );

            // Check child theme first
            if ( file_exists( trailingslashit( get_stylesheet_directory() ) . 'wcatcn-templates/' . $template_name ) ) {

                $located = trailingslashit( get_stylesheet_directory() ) . 'wcatcn-templates/' . $template_name;

                break;

            // Check parent theme next
            } elseif ( file_exists( trailingslashit( get_template_directory() ) . 'wcatcn-templates/' . $template_name ) ) {

                $located = trailingslashit( get_template_directory() ) . 'wcatcn-templates/' . $template_name;

                break;

            // Check plugin last
            } elseif ( file_exists( trailingslashit( self::get_templates_dir() ) . $template_name ) ) {

                $located = trailingslashit( self::get_templates_dir() ) . $template_name;

                break;
            }
        }

        if ( ( true == $load ) && !empty( $located ) ) {

            load_template( $located, $require_once );

        }

        return $located;
    }

    public static function get_templates_dir() {

        $path = plugin_dir_path( __FILE__ );

        return $path . '../public/templates';
    }

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

	}

}
