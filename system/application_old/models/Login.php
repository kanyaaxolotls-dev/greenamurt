<?php

class Login extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        //$this->common_model->__session();

    }
 
    public function check_session()
    {
        $id = $this->session->admin_id;

        if (trim($id) == "" || trim($this->session->session) == "") {
            return FALSE;
        }
        else {

            return TRUE;
        }

        // $id = $this->session->admin_id;
        // if (!$this->session->tempdata('db_session')) {
        //     if ($this->session->staff !== TRUE) {
        //         $database_session = $this->db_model->select('session', 'admin', array('id' => $id));
        //     }
        //     else {
        //         $database_session = $this->db_model->select('session', 'staffs', array('id' => $id));
        //     }
        //     $this->session->set_tempdata('db_session', $database_session, 120);
        // }
        // else {
        //     $database_session = $this->session->tempdata('db_session');
        // }
        // if (trim($database_session) !== trim($this->session->session) || trim($id) == "" || trim($this->session->session) == "") {
            
        //     return FALSE;
        // }
        // else {

        //     return TRUE;
        // }
    }

    public function check_member()
    {
        $id = $this->session->user_id;
        if (trim($id) == "" || trim($this->session->session) == "") {
            return FALSE;
        }
        else {
            return TRUE;
        }

        // $id = $this->session->user_id;
        // if (!$this->session->tempdata('db_session')) {
        //     $database_session = $this->db_model->select('session', 'member', array('id' => $id));
        //     $this->session->set_tempdata('db_session', $database_session, 300);
        // }
        // else {
        //     $database_session = $this->session->tempdata('db_session');
        // }
        // if (trim($database_session) !== trim($this->session->session) || trim($id) == "" || trim($this->session->session) == "") {
        //     return FALSE;
        // }
        // else {
        //     return TRUE;
        // }
    }

}
