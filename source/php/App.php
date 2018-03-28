<?php

namespace PostTypeExport;

class App
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueueStyles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));

        new Exporter();
    }

    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueStyles()
    {

    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {

    }
}
