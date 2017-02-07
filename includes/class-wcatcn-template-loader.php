<?php

/**
 * Handle templates for the public-facing side.
 *
 * @link       https://www.nevma.gr
 * @since      1.0.1
 *
 * @package    Wc_Added_To_Cart_Notification
 * @subpackage Wc_Added_To_Cart_Notification/includes
 */

/**
 * Handle templates for the public-facing side.
 *
 * Load templates of public-facing components, allowing template overriding
 * by parent and child themes.
 *
 * @package    Wc_Added_To_Cart_Notification
 * @subpackage Wc_Added_To_Cart_Notification/includes
 * @author     Nevma <info@nevma.gr>
 */
class WCATCN_Template_Loader {

	/**
     * Retrieves a template part
     *
     * @since v1.1.0
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
     * @since v1.1.0
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

    /**
     * Return the path of the directory for the plugin's public templates.
     *
     * @since 1.1.0
     * @return [type] [description]
     */
    public static function get_templates_dir() {

        $path = plugin_dir_path( __FILE__ );

        return $path . '../public/templates';
    }

}
