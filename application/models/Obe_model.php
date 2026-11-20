<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'models/M_obe.php';

/**
 * Obe_model Class
 * Model pendukung Outcome-Based Education (OBE)
 * Mewarisi fungsionalitas komprehensif dari M_obe
 */
class Obe_model extends M_obe {

    public function __construct()
    {
        parent::__construct();
    }
}
