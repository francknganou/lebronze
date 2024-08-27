<?php

namespace App\Controllers;

use Database\DBConnection;

abstract class Controller {

    protected $db;
    protected $params;

    public function __construct(DBConnection $db)
    {
        $this->db = $db;
    }
    /**
     * @param string $path The path to the view, relative to the views directory
     * @param array $params An array of parameters to pass to the view
     *
     * @return void
     **/
    protected function view (string $path, array $params = null)
    {
        ob_start();
        $path = str_replace('.', DIRECTORY_SEPARATOR, $path);
        require VIEWS . $path . '.php';
        $content = ob_get_clean();
        require VIEWS . 'layout.php';
    }

    protected function getDB()
    {
        return $this->db;
    }
}