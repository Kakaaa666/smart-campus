<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_penjaminan_mutu extends CI_Model
{
    protected $bureau = 'Penjaminan Mutu';

    public function module_name()
    {
        return $this->bureau;
    }
}
