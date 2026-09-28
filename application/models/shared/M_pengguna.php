<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pengguna extends CI_Model
{
    protected $bureau = 'Rektorat';

    public function module_name()
    {
        return $this->bureau;
    }
}
