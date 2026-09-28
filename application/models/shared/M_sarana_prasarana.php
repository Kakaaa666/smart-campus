<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_sarana_prasarana extends CI_Model
{
    protected $bureau = 'Sarana Prasarana';

    public function module_name()
    {
        return $this->bureau;
    }
}
