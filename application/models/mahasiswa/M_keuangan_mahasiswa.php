<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'models/shared/Keuangan_model_delegate.php';

class M_keuangan_mahasiswa extends CI_Model
{
	use Keuangan_model_delegate;

	public function __construct()
	{
		parent::__construct();
		$this->load_keuangan_shared();
	}
}
