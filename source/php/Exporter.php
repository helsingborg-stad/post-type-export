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
        // get post types, skip attachment
        $postTypes = get_post_types(array('public' => true));
        unset($postTypes['attachment']);

        $data = array();
        $data['postTypes'] = $postTypes;

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

        $postType = $_POST['post_type'];

        $query = new \WP_Query(array(
        	'post_type' => $postType,
        	'post_status' => 'publish',
        	'posts_per_page' => -1
    	));
        $posts = $query->posts;
        wp_reset_postdata();

        foreach ($posts as &$post) {
        	// Typecast object to array
        	$post = (array) $post;
        }

        //Create the CSV file and force download it
        $this->downloadSendHeaders($postType . '_' . date('Y-m-d') . '.csv');
        echo chr(239) . chr(187) . chr(191);
        echo $this->arrayToCsv($posts);
        die();
    }

    /**
     * Convert a multi-dimensional, associative array to CSV data
     * @param  array $data the array of data
     * @return string      CSV text
     */
    public function arrayToCsv($data)
    {
        // Don't create a file, attempt to use memory instead
        $fh = fopen('php://temp', 'rw');

        // write out the headers
        fputcsv($fh, array_keys(current($data)));

        // write out the data
        foreach ($data as $row) {
            fputcsv($fh, $row);
        }
        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);

        return $csv;
    }

    public function downloadSendHeaders($filename)
    {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
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
