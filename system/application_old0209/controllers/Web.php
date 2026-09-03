<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (config_item('select_temp') != 'Static') {
            redirect(site_url('home'));
        }
        
    }
    
	public function index()
	{
	    if (config_item('web_status') == 'Coming Soon') {
            $this->load->view('shop/coming-soon.php');
        }
        else
        {
            $this->load->view('shop/coming-soon.php');
        }
	}
	
}