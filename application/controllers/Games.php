<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Games extends CI_Controller
{ 

    public function __construct()
    { 
        parent::__construct(); 
        if ($this->login->check_member() == false) {
            redirect(site_url('site/login'));
        }  
    } 

    public function index()
    {      
        $data['title']      = 'Games dashboard';
        $data['breadcrumb'] = 'Games';
        $data['layout']     = 'games/game.php';
        $this->load->view('member/index', $data);
    }

}