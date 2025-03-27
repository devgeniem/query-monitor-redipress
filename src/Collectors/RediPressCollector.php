<?php

namespace Hion\QMRediPress\Collectors;

use Hion\QMRediPress\Storages\RediPressStorage;

/**
 * RediPress collector.
 */
class RediPressCollector extends \QM_DataCollector {
    /**
     * Collector name.
     *
     * @var string
     */
    public const NAME = 'redipress';

    /**
     * Collector ID.
     *
     * @var string
     */
    public $id = 'redipress';

    /**
     * RediPress timer.
     *
     * @var ?\QM_Timer
     */
    private ?\QM_Timer $redipress_timer = null;

    /**
     * Collector data.
     *
     * @var array
     */
    private array $redipress_queries = [];

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();

        $this->hooks();
    }

    /**
     * Collector hooks.
     *
     * @return void
     */
    public function hooks(): void {
        \add_action(
            'redipress/before_search',
            \Closure::fromCallable( [ $this, 'before_search' ] ),
            10,
            0
        );

        \add_action(
            'redipress/debug_query',
            \Closure::fromCallable( [ $this, 'debug_query' ] ),
            10,
            3
        );
    }

    /**
     * Before RediPress search.
     *
     * @return void
     */
    protected function before_search(): void {
        $this->redipress_timer = new \QM_Timer();
        $this->redipress_timer->start();
    }

    /**
     * Collect data from RediPress queries.
     *
     * @param object $query Query object.
     * @param array  $results Query results.
     * @param string $type Query type, posts or users.
     * @return void
     */
    protected function debug_query( $query, $results, $type ): void {
        // Stop the timer.
        if ( $this->redipress_timer ) {
            $this->redipress_timer->stop();
        }

        $data = [
            'params'  => array_filter( $query->query_vars ),
            'results' => count( $results ) - 1,
            'timing'  => $this->redipress_timer ? $this->redipress_timer->get_time() : 0,
        ];

        switch ( $type ) {
            case 'posts':
                $data['query'] = $query->redisearch_query;
                break;
			case 'users':
                $data['query'] = $query->request;
                break;
        }

        $this->redipress_queries[] = $data;

        // Reset timer.
        $this->redipress_timer = null;
    }

    /**
     * Collector name for outputs.
     *
     * @return string
     */
    public function name(): string {
        return \__( 'RediPress', 'query-monitor-redipress' );
    }

    /**
     * Collector data storage.
     *
     * @return \QM_Data
     */
    public function get_storage(): \QM_Data {
        return new RediPressStorage();
    }

    /**
     * Process collector.
     *
     * @return void
     */
    public function process(): void {
        if ( \did_action( 'qm/cease' ) ) {
            return;
        }

        $this->data->redipress_queries = $this->redipress_queries;
    }
}
