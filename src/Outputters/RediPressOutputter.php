<?php

namespace Hion\QMRediPress\Outputters;

/**
 * Outputter for RediPress collector.
 */
class RediPressOutputter extends \QM_Output_Html {
    /**
     * Constructor.
     *
     * @param \QM_Collector $collector Collector.
     */
    public function __construct( \QM_Collector $collector ) {
        parent::__construct( $collector );

        $this->hooks();
    }

	/**
     * Hooks.
     *
     * @return void
     */
    protected function hooks(): void {
        \add_filter( 'qm/output/panel_menus', \Closure::fromCallable( [ $this, 'panel_menu' ] ) );
        \add_filter( 'qm/output/menus', \Closure::fromCallable( [ $this, 'panel_menu' ] ), 500 );
    }

    /**
     * Create menu.
     *
     * @param array $menu Menu items.
     * @return array
     */
    protected function panel_menu( array $menu ): array {
        $menu[ $this->collector->id() ] = $this->menu( [
			'title' => $this->name(),
        ] );

        return $menu;
    }

    /**
     * Outputter menu name.
     *
	 * @return string
	 */
    public function name(): string {
		return \__( 'RediPress', 'query-monitor-redipress' );
	}

    /**
     * Output collector data.
     *
     * @return void
     */
    public function output(): void {
        $data = $this->collector->get_data();

        if ( is_null( $data ) || empty( $data->redipress_queries ) ) {
            return;
        }

		$this->before_tabular_output();

		echo '<thead>';
		echo '<tr>';
		echo '<th scope="col">' . \esc_html( '#' ) . '</th>';
		echo '<th scope="col">' . \esc_html__( 'Query', 'query-monitor-redipress' ) . '</th>';
		echo '<th scope="col">' . \esc_html__( 'Results', 'query-monitor-redipress' ) . '</th>';
		echo '<th scope="col">' . \esc_html__( 'Time', 'query-monitor-redipress' ) . '</th>';
		echo '<th scope="col">' . \esc_html__( 'Params', 'query-monitor-redipress' ) . '</th>';
		echo '</tr>';
		echo '</thead>';

		echo '<tbody>';
		foreach ( $data->redipress_queries as $i => $query ) {
            $params = \QM_Util::json_format( $query['params'] );
			echo '<tr>';
            echo '<td class="qm-num">' . \esc_html( $i + 1 ) . '</td>';
            echo '<td><pre class="qm-pre-wrap"><code style="word-break:normal !important;">' . \esc_html( $query['query'] ) . '</code></pre></td>';
            echo '<td class="qm-num">' . \esc_html( number_format_i18n( $query['results'] ) ) . '</td>';
            echo '<td class="qm-num">' . \esc_html( number_format_i18n( $query['timing'], 4 ) ) . '</td>';
            echo '<td class="qm-ltr qm-has-toggle qm-row-sql">';
            echo \wp_kses_post( self::build_toggler() );
            echo '<div class="qm-inverse-toggled"><pre class="qm-pre-wrap"><code>';
			echo \esc_html( substr( $params, 0, 150 ) ) . '&nbsp;&hellip;';
			echo '</code></pre></div>';
			echo '<div class="qm-toggled"><pre class="qm-pre-wrap"><code>';
			echo \esc_html( $params );
			echo '</code></pre></div>';
            echo '</td>';
			echo '</tr>';
		}
		echo '</tbody>';

		$this->after_tabular_output();
    }
}
