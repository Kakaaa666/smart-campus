<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pengajuan extends CI_Model
{
    protected $bureau = 'Akademik';

    public function module_name()
    {
        return $this->bureau;
    }
}
