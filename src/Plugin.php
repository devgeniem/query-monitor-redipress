<?php

namespace Hion\QMRediPress;

use Hion\QMRediPress\Collectors\RediPressCollector;
use Hion\QMRediPress\Outputters\RediPressOutputter;

/**
 * Plugin class.
 *
 * This class initializes the Query Monitor RediPress plugin and registers the necessary
 * hooks, collectors, and outputters.
 */
final class Plugin {

    /**
     * Holds the singleton.
     *
     * @var Plugin
     */
    private static $instance;

    /**
     * Get the instance.
     *
     * @return Plugin
     */
    public static function get_instance(): Plugin {
        return self::$instance;
    }

    /**
     * Initialize the plugin by creating the singleton.
     *
     * @return void
     */
    public static function init(): void {
        if ( empty( self::$instance ) ) {
            self::$instance = new self();
            self::$instance->hooks();
        }
    }

    /**
     * Add plugin hooks and filters.
     *
     * @return void
     */
    protected function hooks() {
        \add_filter(
            'qm/collectors',
            \Closure::fromCallable( [ $this, 'register_collectors' ] )
        );

        \add_filter(
            'qm/outputter/html',
            \Closure::fromCallable( [ $this, 'register_outputters' ] ),
            80
        );
    }

    /**
     * Register QM collectors.
     *
     * @param array $collectors Collectors.
     * @return array
     */
    protected function register_collectors( array $collectors ): array {
        if ( ! \is_admin() ) {
            $collectors[ RediPressCollector::NAME ] = new RediPressCollector();
        }

        return $collectors;
    }

    /**
     * Register QM outputters.
     *
     * @param array $output Output.
     * @return array
     */
    protected function register_outputters( array $output ): array {
        $collector = \QM_Collectors::get( RediPressCollector::NAME );

        if ( $collector ) {
            $output[ RediPressCollector::NAME ] = new RediPressOutputter( $collector );
        }

        return $output;
    }
}
