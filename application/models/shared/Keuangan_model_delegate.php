<?php
defined('BASEPATH') OR exit('No direct script access allowed');

trait Keuangan_model_delegate
{
    protected $keuangan_shared;

    protected function load_keuangan_shared()
    {
        $this->load->model('shared/M_keuangan_shared', 'keuangan_shared');
        $ci = get_instance();
        $this->keuangan_shared = $ci->keuangan_shared;
    }

    public function __call($method, $arguments)
    {
        if (!$this->keuangan_shared || !method_exists($this->keuangan_shared, $method)) {
            throw new BadMethodCallException('Method keuangan tidak tersedia: ' . $method);
        }

        return call_user_func_array([$this->keuangan_shared, $method], $arguments);
    }
}
