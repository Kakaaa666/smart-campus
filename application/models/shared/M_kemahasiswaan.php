<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kemahasiswaan extends CI_Model
{
    protected $bureau = 'Kemahasiswaan';

    public function module_name()
    {
        return $this->bureau;
    }
}
