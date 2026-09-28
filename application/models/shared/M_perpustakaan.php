<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_perpustakaan extends CI_Model
{
    protected $bureau = 'Perpustakaan';

    public function module_name()
    {
        return $this->bureau;
    }
}
