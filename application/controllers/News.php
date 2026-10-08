<?php
/***************************************************************************************************
 * Copyright (c) 2019., AIT
 * This project is developed and maintained by AIT.
 * Nobody is permitted to modify the source or any part of the project without permission.
 
 **************************************************************************************************/

defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller
{
    /**
     * Check Valid Login or display login page.
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->login->check_session() == FALSE) {
            redirect(site_url('site/admin'));
        }
        $this->load->library('pagination');
    }


    function add_news()
    {
        $this->form_validation->set_rules('news_title', 'Title', 'required');
        $this->form_validation->set_rules('news_details', 'Description', 'required');
        //$this->form_validation->set_rules('news_pic', 'Pic', 'required');
        
        
        if ($this->form_validation->run()) {

            //  if (trim($_FILES['news_pic']['name'] !== "")) {

            //             $this->load->library('upload');

            //             if (!$this->upload->do_upload('news_pic')) {
            //                 $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Image not uploaded. Also select category.<br/>' . $this->upload->display_errors() . '</div>');
            //                 redirect('news/add_news');
            //             } else {
            //                 $image_data               = $this->upload->data();
            //                 $config['image_library']  = 'gd2';
            //                 $config['source_image']   = $image_data['full_path']; //get original image
            //                 $config['maintain_ratio'] = TRUE;
            //                 $config['width']          = 600;
            //                 $config['height']         = 500;
            //                 $this->load->library('image_lib', $config);
            //                 $this->image_lib->resize();
            //                 $image = $image_data['file_name'];
            //             }
            //         }
                      $params = array(
                        'news_title'  => $this->input->post('news_title'),
                        'news_details'=> $this->input->post('news_details'),
                        //'news_pic'    => $image,
                        'news_type'   => $this->input->post('news_type'),             

                    );
                   


            $this->db->insert('news', $params);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">News Added Successfully.</div>');
            redirect('news/list_news');
        }
        else {
            $this->db->select('id, news_title')->from('news');
            $data['data']       = $this->db->get()->result();
            $data['title']      = 'Add New News';
            $data['breadcrumb'] = 'Add New News';
            $data['layout']     = 'news/add_news.php';
            $this->load->view('admin/index', $data);

        }
    }

    public function list_news()
    {
        $this->db->from('news');
        $data['data']       = $this->db->get()->result_array();
        $data['title']      = 'All News';
        $data['breadcrumb'] = 'All News';
        $data['layout']     = 'news/list_news.php';
        $this->load->view('admin/index', $data);
    }

    function edit($id)
    {
        $data['news'] = $this->db->get_where('news', array('id' => $id))->row_array();

        if (isset($data['news']['id'])) {
            $this->form_validation->set_rules('news_title', 'Title', 'required');
            $this->form_validation->set_rules('news_details', 'Description', 'required');
            //$this->form_validation->set_rules('news_pic', 'Pic', 'required');
            //$this->form_validation->set_rules('news_for', 'news_for');

        

            if ($this->form_validation->run()) {
                if (trim($_FILES['news_pic']['name'] !== "")) {

                        $this->load->library('upload');

                        if (!$this->upload->do_upload('news_pic')) {
                            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Image not uploaded. Also select category.<br/>' . $this->upload->display_errors() . '</div>');
                            redirect('news/add_news');
                        } else {
                            $image_data               = $this->upload->data();
                            $config['image_library']  = 'gd2';
                            $config['source_image']   = $image_data['full_path']; //get original image
                            $config['maintain_ratio'] = TRUE;
                            $config['width']          = 600;
                            $config['height']         = 500;
                            $this->load->library('image_lib', $config);
                            $this->image_lib->resize();
                            $image = $image_data['file_name'];
                        }
                    }

                $params = array(
                    'news_title'  => $this->input->post('news_title'),
                    'news_details'    => $this->input->post('news_details'),
                    'news_pic'        => $image,
                    'news_for'        => $this->input->post('news_for'),
    
                );
                $this->db->where('id', $id);
                $this->db->update('news', $params);
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">/News Updated Successfully.</div>');
                redirect('news/list_news');
            }
            else {
                $this->db->select('id, news_title')->from('news');
                $data['data']       = $this->db->get()->result();
                $data['news_title']      = 'Edit news';
                $data['breadcrumb'] = 'Edit news';
                $data['layout']     = 'news/edit_news.php';
                $this->load->view('admin/index', $data);
            }
        }
    }

     public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('news');
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">News Deleted Successfully.</div>');
        redirect('news/list_news');
    }






}

?>
