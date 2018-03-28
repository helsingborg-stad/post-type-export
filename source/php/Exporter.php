<?php

namespace PostTypeExport;

use Philo\Blade\Blade;

class Exporter
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'admin_menu'), 10);
        add_action('admin_post_export_csv', array($this, 'exportAsCSV'));
    }

    public function admin_menu()
    {
  		add_menu_page(
            __('Export post type', 'post-type-export'),
            __('Export post type', 'post-type-export'),
            'publish_pages',
            'export_post_type',
            array(
                $this,
                'exportForm'
            ),
            'dashicons-migrate',
            '99'
        );
    }

    public function exportForm()
    {
        $data = array();
        $data['postTypes'] = get_post_types(array('public' => true, '_builtin' => false));

        echo $this->blade('form', $data);
    }

    public function exportAsCSV()
    {
        // Check nonce
        if (!isset($_POST['export-post-type'])
            || !wp_verify_nonce($_POST['export-post-type'], 'export')
            || empty($_POST['post_type'])) {
            return;
        }

        $query = new \WP_Query(array('post_type' => $_POST['post_type']));
        $posts = $query->posts;
    }

    /**
     * Return markup from a Blade template
     * @param  string $view View name
     * @param  array  $data View data
     * @return string       The markup
     */
    public function blade($view, $data = array())
    {
        if (!file_exists(POSTTYPEEXPORT_CACHE_DIR)) {
            mkdir(POSTTYPEEXPORT_CACHE_DIR, 0777, true);
        }

        $blade = new Blade(POSTTYPEEXPORT_VIEW_PATH, POSTTYPEEXPORT_CACHE_DIR);
        return $blade->view()->make($view, $data)->render();
    }
}
