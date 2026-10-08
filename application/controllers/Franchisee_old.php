<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Franchisee extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->fran_id == "") {
            redirect(site_url('site/franchisee'));
        }
        $this->load->library('pagination');
    }

    public function index()
    {
        $data['title']      = 'Dashboard';
        $data['breadcrumb'] = 'dashboard';
        $this->load->view('franchisee/index', $data);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('site_flash', '<div class="alert alert-info">You have been logged out !</div>');
        redirect(site_url('site/franchisee'));
    }

    // CORE MEMBER PARTS HERE NOW ############################################################ STARTS :


    public function sale_product()
    {
        $this->form_validation->set_rules('pname', 'Product', 'required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']  = 'Sale Product';
            $data['layout'] = 'product/sale.php';
            $this->load->view('franchisee/index', $data);
        }
        else {
            $user_id      = $this->input->post('userid');
            $no           = 0;
            $total_amount = 0;
            $array        = array();
            $array2       = array();
            foreach ($_POST['prod_name'] as $name) {
                if ($name !== "") {
                    $prodid       = htmlentities($name);
                    $qty          = $_POST['qty'][$no];
                    $dealer_price = $this->db_model->select('prod_price', 'product', array('id' => $prodid));
                    $data         = $this->db_model->select_multi('id, available_qty', 'franchisee_stock', array(
                        'franchisee_id' => $this->session->fran_id,
                        'product_id'    => $prodid,
                    ));
                    $total_amount += $dealer_price;
                    $array        += array($prodid => $_POST['qty'][$no]);
                    $array2       += array($prodid => $dealer_price);

                    if ($_POST['qty'][$no] > $data->available_qty) {
                        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Your Stock has lower unit available the entered.</div>');

                        redirect('franchisee/sale_product/');
                    }

                    $update_qty = $data->available_qty - $qty;
                    $update     = array(
                        'available_qty' => $update_qty,
                    );
                    $this->db->where('id', $data->id);
                    $this->db->update('franchisee_stock', $update);
                    $data = array(
                        'product_id'    => $prodid,
                        'userid'        => $user_id,
                        'cost'          => $dealer_price,
                        'franchisee_id' => $this->session->fran_id,
                        'date'          => date('Y-m-d'),
                        'deliver_date'  => date('Y-m-d'),
                        'status'        => 'Completed',
                        'qty'           => $qty,
                    );
                    $this->db->insert('product_sale', $data);
                    $this->load->model('earning');
                    $this->earning->repurchase($this->db->insert_id());
                }
                $no++;
            }
            $array  = serialize($array);
            $array2 = serialize($array2);
            $params = array(
                'stock_data'       => $array,
                'stock_data_price' => $array2,
                'fran_id'          => $this->session->fran_id,
                'date'             => date('Y-m-d'),
                'userid'           => $user_id,
            );
            $this->db->insert('franchisee_stock_sale_bill', $params);
            $invid = $this->db->insert_id();


            ################ INVOICE ENTRY ############################


            $fran_data    = $this->db_model->select_multi('name, business_name, address, state, phone', 'franchisee', array('id' => $this->session->fran_id));
            $invoice_name = "Purchase Bill";
            $user_id      = $this->session->fran_id;
            $invoice_date = date('Y-m-d');
            $user_type    = 'Member';
            $company_add  = config_item('company_address');
            $bill_add     = $fran_data->name . "<br/>" . $fran_data->business_name . "<br/>" . $fran_data->address . "<br/>" . $fran_data->state . "<br/>" . $fran_data->phone . "<br/>";
            $total_amt    = $total_amount;
            $paid_amt     = $total_amount;

            $no     = 0;
            $array  = array();
            $array2 = array();
            $array3 = array();
            foreach ($_POST['prod_name'] as $name) {
                if ($name !== "") {
                    $prodid       = $this->db_model->select_multi('id, prod_name, prod_price, gst', 'product', array('id' => $name));
                    $dealer_price = $prodid->prod_price;
                    $tax          = ($prodid->prod_price * $prodid->gst) / 100;

                    $item_name = $prodid->prod_name;
                    $price     = $dealer_price;
                    $tax       = $tax;
                    $qty       = $_POST['qty'][$no];

                    $array  += array($item_name => $price);
                    $array2 += array($item_name => $tax);
                    $array3 += array($item_name => $qty);
                }
                $no++;
            }

            $array  = serialize($array);
            $array2 = serialize($array2);
            $array3 = serialize($array3);
            $params = array(
                'invoice_name'     => $invoice_name,
                'userid'           => $user_id,
                'invoice_data'     => $array,
                'invoice_data_tax' => $array2,
                'invoice_data_qty' => $array3,
                'company_address'  => $company_add,
                'bill_to_address'  => $bill_add,
                'total_amt'        => $total_amt,
                'paid_amt'         => $paid_amt,
                'date'             => $invoice_date,
                'user_type'        => $user_type,
            );
            $this->db->insert('invoice', $params);


            ################ END ENTRY ################################


            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Sale made successfully.</div>');

             redirect('franchisee/invoice/' . $invid);

        }

    }

    public function invoice($id)
    {
        $data['result']     = $this->db_model->select_multi('fran_id, stock_data, stock_data_price, userid, date', 'franchisee_stock_sale_bill', array('id' => $id));
        $data['title']      = 'Invoice : #' . $id;
        $data['breadcrumb'] = 'Invoice';
        $data['layout']     = 'product/invoice.php';
        $this->load->view('franchisee/index', $data);
    }

    public function my_invoices()
    {
        $config['base_url']   = site_url('franchisee/my_invoices');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('invoice', array(
            'userid'    => $this->session->fran_id,
            'user_type' => 'Franchisee',
        ));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->from('invoice')->where(array(
                                              'userid'    => $this->session->fran_id,
                                              'user_type' => 'Franchisee',
                                          ))->order_by('id', 'DESC')->limit($config['per_page'], $page);
        $data['invoice']    = $this->db->get()->result();
        $data['title']      = 'My Invoices';
        $data['breadcrumb'] = 'My Invoices';
        $data['layout']     = 'invoice/my_invoices.php';
        $this->load->view('franchisee/index', $data);
    }

    public function invoice_view($id)
    {
        $data['result'] = $this->db_model->select_multi('*', 'invoice', array('id' => $id));
        $this->load->view('franchisee/invoice/print_invoice.php', $data);
    }


    public function print_fran_invoice($id)
    {
        $data['result'] = $this->db_model->select_multi('fran_id, stock_data, stock_data_price, userid, date', 'franchisee_stock_sale_bill', array('id' => $id));
        $this->load->view('franchisee/product/invoice_print', $data);
    }

    public function sale_history()
    {
        $this->load->library('pagination');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('product_sale', array('franchisee_id' => $this->session->fran_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);
        $this->db->select('product_id, userid, cost, qty, deliver_date');
        $this->db->where(array('franchisee_id' => $this->session->fran_id));
        $this->db->order_by('id', 'DESC');
        $this->db->limit($config['per_page'], $page);

        $data['data'] = $this->db->get('product_sale')->result();

        $data['title']  = 'Sale History';
        $data['layout'] = 'product/sale_history.php';
        $this->load->view('franchisee/index', $data);
    }

    public function profile()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone No', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['result']     = $this->db_model->select_multi('name, email, phone', 'franchisee', array('id' => $this->session->fran_id));
            $data['title']      = 'My Profile';
            $data['breadcrumb'] = 'Profile';
            $data['layout']     = 'misc/profile.php';
            $this->load->view('franchisee/base', $data);
        }
        else {

            $array = array(
                'name'  => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
            );
            if (trim($this->input->post('newpass')) !== "") {
                $array = $array + array("password" => $this->input->post('newpass'));
            }
            $this->db->where('id', $this->session->fran_id);
            $this->db->update('franchisee', $array);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Profile updated successfully.</div>');

            redirect('franchisee/profile/');
        }
    }

    public function unused_epin()
    {

        $config['base_url']   = site_url('franchisee/unused_epin');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('epin', array(
            'status'   => 'Un-used',
            'issue_to' => $this->session->fran_id,
        ));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, epin, amount, issue_to, generate_time, generate_time, type')->from('epin')->where(array(
                                                                                                                     'status'   => 'Un-used',
                                                                                                                     'issue_to' => $this->session->fran_id,
                                                                                                                 ))
                 ->limit($config['per_page'], $page);

        $data['epin'] = $this->db->get()->result_array();

        $data['title']      = 'Unused e-PINs';
        $data['breadcrumb'] = 'Un-used e-pin';
        $data['layout']     = 'epin/unused.php';
        $this->load->view('franchisee/index', $data);
    }

    public function used_epin()
    {

        $config['base_url']   = site_url('franchisee/used_epin');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('epin', array(
            'status'   => 'Used',
            'issue_to' => $this->session->fran_id,
        ));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, epin, amount, used_by, used_time, type')->from('epin')->where(array(
                                                                                                 'status'   => 'Used',
                                                                                                 'issue_to' => $this->session->fran_id,
                                                                                             ))
                 ->limit($config['per_page'], $page);

        $data['epin'] = $this->db->get()->result_array();

        $data['title']      = 'Used e-PINs';
        $data['breadcrumb'] = 'Used e-pin';
        $data['layout']     = 'epin/used.php';
        $this->load->view('franchisee/index', $data);
    }
    public function add_product()
    {
        $this->form_validation->set_rules('prod_name', 'Product Name', 'trim|required');
        $this->form_validation->set_rules('category', 'Product Category', 'trim|required');
        $this->form_validation->set_rules('prod_price', 'Product Price', 'trim|required');
        $this->form_validation->set_rules('purchase_price', 'Purchase Price', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Add Product';
            $data['breadcrumb'] = 'Add Product';
            $data['layout']     = 'product/add_product.php';
            $this->db->select('id, cat_name')->order_by('cat_name', 'ASC');
            $data['parents'] = $this->db->get('product_categories')->result_array();
            $this->load->view('franchisee/index', $data);
        } else {
            $prod_name        = $this->input->post('prod_name');
            $category         = $this->input->post('category');
            $prod_price       = $this->common_model->filter($this->input->post('prod_price'), 'float');
            $dealer_price     = $this->common_model->filter($this->input->post('dealer_price'), 'float');
            $purchase_price   = $this->common_model->filter($this->input->post('purchase_price'), 'float');
                
            $prod_desc        = $this->input->post('prod_desc');
            $pv               = $this->input->post('pv');
            $qty              = $this->input->post('qty');
            $gst              = $this->common_model->filter($this->input->post('gst'), 'float');
            $image            = 'default.jpg';
            $show_on_reg_form = $this->input->post('join_form');
            if ($show_on_reg_form !== "Yes") {
                $show_on_reg_form = "No";
            }
            $direct_income   = $this->common_model->filter($this->input->post('direct_income'), 'float');
            $level_income    = $this->input->post('level_income');
            $matching_income = $this->common_model->filter($this->input->post('matching_income'), 'float');
            $capping         = $this->common_model->filter($this->input->post('capping'), 'float');
            $roi             = $this->common_model->filter($this->input->post('roi'), 'float');
            $roi_frequency   = $this->input->post('roi_frequency');
            $roi_limit       = $this->input->post('roi_limit');

            if (trim($_FILES['img']['name'] !== "")) { 

                $this->load->library('upload');

                if (!$this->upload->do_upload('img')) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Image not uploaded. Also select category.<br/>' . $this->upload->display_errors() . '</div>');
                    redirect('franchisee/add_product');
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
            $datap = array(
                'prod_name'       => $prod_name,
                'category'        => $category,
                'prod_price'      => $prod_price,
                'dealer_price'    => $dealer_price,
                'purchase_price'  => $purchase_price,
                'prod_desc'       => $prod_desc,
                'pv'              => $pv,
                'qty'             => $qty,
                'gst'             => $gst,
                'image'           => $image,
                'show_on_regform' => $show_on_reg_form,
                'direct_income'   => $direct_income,
                'level_income'    => $level_income,
                'matching_income' => $matching_income,
                'capping'         => $capping,
                'roi'             => $roi,
                'roi_frequency'   => $roi_frequency,
                'roi_limit'       => $roi_limit,
                'status'          => 'Pending',
                'publish_by'      => $this->session->name,
            );
            //var_dump($data);die();

            $this->db->insert('product', $datap);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Product Added successfully.</div>');
            redirect('franchisee/manage_products');
        }
    }
       public function manage_products()
    {
        $data['title']      = 'Manage Products';
        $data['breadcrumb'] = 'Manage Products';
        $data['layout']     = 'product/manage_products.php';
        $this->db->select('id, cat_name')->order_by('cat_name', 'ASC');
        $data['parents'] = $this->db->get('product_categories')->result_array();
        $this->db->select('id, prod_name, prod_price, gst, image, qty, sold_qty, show_on_regform')
                 ->order_by('prod_name', 'ASC')->where('publish_by',$this->session->name);
        $data['prod'] = $this->db->get('product')->result_array();
        // var_dump($data);die();
        $this->load->view('franchisee/index', $data);

    }
      public function view_prod($id)
    {
    

        $data['title']      = 'Product Detail';
        $data['breadcrumb'] = 'Manage Products';
        $data['layout']     = 'product/view_product.php';
       // $data['data']       = $product;
      //  var_dump($data_prod);die();
        $this->load->view('franchisee/index',$data);
    }
      public function edit($id)
    {
        $this->form_validation->set_rules('prod_name', 'Product Name', 'trim|required');
        $this->form_validation->set_rules('category', 'Product Category', 'trim|required');
        $this->form_validation->set_rules('prod_price', 'Product Price', 'trim|required');
        $this->form_validation->set_rules('purchase_price', 'Purchase Price', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $product_data_edit      = $this->db_model->select_multi('*', 'product', array('id' => $id . $this->input->post('id')));
          /*  var_dump( $product_data_edit);die();*/
            $data_edit['title']      = 'Edit Product';
            $data_edit['breadcrumb'] = 'Manage Products';
            $data_edit['layout']     = 'product/edit_product.php';
            $data_edit['data']       = $product_data_edit;
            $data_edit['parents']    = $this->db->get('product_categories')->result_array();
           //   var_dump($data_edit);die();
            $this->load->view('franchisee/index', $data_edit);
        } else {
            $prod_name        = $this->input->post('prod_name');
            $category         = $this->input->post('category');
            $prod_price       = $this->common_model->filter($this->input->post('prod_price'), 'float');
            $dealer_price     = $this->common_model->filter($this->input->post('dealer_price'), 'float');
            $purchase_price   = $this->common_model->filter($this->input->post('purchase_price'), 'float');

            $prod_desc        = $this->input->post('prod_desc');
            $pv               = $this->input->post('pv');
            $qty              = $this->input->post('qty');
            $status           = $this->input->post('status');
            $gst              = $this->common_model->filter($this->input->post('gst'), 'float');
            $image            = $old_img = $this->input->post('image');
            $show_on_reg_form = $this->input->post('join_form');
            if ($show_on_reg_form !== "Yes") {
                $show_on_reg_form = "No";
            }
            $direct_income   = $this->common_model->filter($this->input->post('direct_income'), 'float');
            $level_income    = $this->input->post('level_income');
            $matching_income = $this->common_model->filter($this->input->post('matching_income'), 'float');
            $capping         = $this->common_model->filter($this->input->post('capping'), 'float');
            $roi             = $this->common_model->filter($this->input->post('roi'), 'float');
            $roi_frequency   = $this->input->post('roi_frequency');
            $roi_limit       = $this->input->post('roi_limit');

            if (trim($_FILES['img']['name'] !== "")) {

                $this->load->library('upload');

                if (!$this->upload->do_upload('img')) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Image not uploaded. Also select category.<br/>' . $this->upload->display_errors() . '</div>');
                    redirect('franchisee/edit_product/' . $id . $this->input->post('id'));
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
                    if (trim($image_data['file_name']) !== "") {
                        unlink(FCPATH . '/uploads/' . $old_img);
                    }
                }
            }
            $data_p = array(
                'prod_name'       => $prod_name,
                'category'        => $category,
                'prod_price'      => $prod_price,
                'dealer_price'    => $dealer_price,
                'purchase_price'  => $purchase_price,
                'prod_desc'       => $prod_desc,
                'pv'              => $pv,
                'qty'             => $qty,
                'gst'             => $gst,
                'image'           => $image,
                'show_on_regform' => $show_on_reg_form,
                'direct_income'   => $direct_income,
                'level_income'    => $level_income,
                'matching_income' => $matching_income,
                'capping'         => $capping,
                'roi'             => $roi,
                'roi_frequency'   => $roi_frequency,
                'roi_limit'       => $roi_limit,
                'status'          => $status,
            );

            $this->db->where('id', $this->input->post('id'));
            $this->db->update('product', $data_p);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Product Updated successfully.</div>');
            redirect('franchisee/manage_products');
        }
    }
       public function remove($id)
    {
        $count = $this->db_model->count_all('product_sale', array(
            'product_id' => $id,
            'status'     => 'Processing',
        ));
        if ($count > 0) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Product Cannot be deleted as there are ' . $count . ' Un-Delivered Orders.</div>');
            redirect('franchisee/manage_products');
        } else {
            $img = $this->db_model->select('image', 'product', array('id' => $id));
            $this->db->where('id', $id);
            $this->db->delete('product');
            unlink(FCPATH . '/uploads/' . $img);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Product Deleted successfully.</div>');
            redirect('franchisee/manage_products');
        }
    }
        public function manage_cat()
    {
        $this->form_validation->set_rules('cat_name', 'Category Name', 'trim|required');

        if ($this->form_validation->run() !== FALSE) {
            $data = array( 
                'cat_name'    => $this->input->post('cat_name'),
                'parent_cat'  => $this->input->post('parent_cat'),
                'description' => $this->input->post('description'),
                'publish_by'  => $this->session->name,

            );
            $this->db->insert('product_categories', $data);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Category Created Successfully.</div>');
            redirect('franchisee/manage_cat');
        } else {
            $config['base_url']   = site_url('franchisee/manage_cat');
            $config['per_page']   = 50;
            $config['total_rows'] = $this->db_model->count_all('product_categories');
            $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $this->pagination->initialize($config);

            $this->db->select('id, cat_name, parent_cat, description')->from('product_categories')
                     ->order_by('cat_name', 'DESC')->limit($config['per_page'], $page);

            $data['cat'] = $this->db->get()->result_array();
            $this->db->select('id, cat_name');
            $data['parents'] = $this->db->get('product_categories')->result_array();

            $data['title']      = 'Manage Product Categories';
            $data['breadcrumb'] = 'Product Categories';
            $data['layout']     = 'product/categories.php';
            $this->load->view('franchisee/index', $data);

        }
    }

    public function category()
    {
        $type = $this->uri->segment(3);
        $id   = $this->uri->segment(4);

        switch ($type) {
            case $type == "edit":
                redirect('franchisee/category_edit/' . $id);
                break;
            case $type == "remove":
                $this->db->where('id', $id);
                $this->db->delete('product_categories');
                $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>Category deleted successfully.</div>");
                redirect('franchisee/manage_cat');

        }

    }

    public function category_edit()
    {
        $this->form_validation->set_rules('cat_name', 'Category Name', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Edit Category';
            $data['breadcrumb'] = 'Edit Category';
            $data['layout']     = 'product/edit_category.php';
            $data['data']       = $this->db_model->select_multi('id, cat_name, parent_cat, description', 'product_categories', array('id' => $this->uri->segment(3)));
            $this->db->select('id, cat_name');
            $data['parents'] = $this->db->get('product_categories')->result_array();
            $this->load->view('franchisee/index', $data);
        } else {
            $this->db->where('id', $this->input->post('id'));
            $data = array(
                'cat_name'    => $this->input->post('cat_name'),
                'parent_cat'  => $this->input->post('parent_cat'),
                'description' => $this->input->post('description'),
            );
            $this->db->update('product_categories', $data);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Category Updated Successfully.</div>');
            redirect('franchisee/manage_cat');
        }

    }
        public function sale_details()
    {
        // $this->form_validation->set_rules('cat_name', 'Category Name', 'trim|required');
        // if ($this->form_validation->run() == FALSE) {
        //     $data['title']      = 'Sale Details';
        //     $data['breadcrumb'] = 'Sale Details';
            $data['layout']     = 'product/sale_details.php';
            // $data['data']       = $this->db_model->select_multi('id, name);
            $this->db->select('id, prod_name')->order_by('prod_name', 'ASC');
            $data['parents'] = $this->db->get('product')->result_array();
            $this->load->view('franchisee/index', $data);
        // } else {
            // $this->db->where('id', $this->input->post('id'));
            // $data = array(
            //     'cat_name'    => $this->input->post('cat_name'),
            //     'parent_cat'  => $this->input->post('parent_cat'),
            //     'description' => $this->input->post('description'),
            // );
            // $this->db->update('product_categories', $data);
            // $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Category Updated Successfully.</div>');
            // redirect('franchisee/manage_cat');
        // }

    }
        public function add_quotation_temp()
    {
        $data['id'] = $this->input->post('id');
        $data['product'] = $this->input->post('product');
        $data['price'] = $this->input->post('price');
        $data['quantity'] = $this->input->post('quantity');
        $data['gst_per'] = $this->input->post('gst_per');
        $data['payable_amount'] = $this->input->post('payable_amount');


        $this->db->insert('temp',$data);
       
        $data['record'] = $this->db->get_where("temp", array('id' => $data['id']))->result_array();
         // var_dump($data['id']);die();
        // $data['layout']     = 'product/temp.php';
        $this->load->view('franchisee/product/temp', $data);
    }
    public function delete_quotation_temp()
    {
        $id = $this->input->post('id');
        $temp = $this->db->get_where("temp", array('id' => $id))->result_array();
        
        $this->db->where('id',$id);
        $this->db->delete("temp");    

        $data['record'] = $this->db->get_where("temp", array('id' => $temp[0]['id']))->result_array();
        $this->load->view('franchisee/product/temp', $data);               
    } 
        public function getServiceAmount()
    {
        $product = $this->input->post('product');
        $gst = $this->input->post('gst');
        $data['result'] = $this->db->get_where("product", array('id' => $product))->result_array();    
        echo $data['result'][0]['prod_price'];
    }
    
    public function getServiceGST()
    {
        $product = $this->input->post('product');
        $gst = $this->input->post('gst');
        $data['result'] = $this->db->get_where("product", array('id' => $product))->result_array();        
        
            echo $data['result'][0]['gst'];
            
    }   
    
    public function getAMCAmount()
    {
        $quantity = $this->input->post('quantity');
        $product = $this->input->post('product');
        $data['result'] = $this->db->get_where("product", array('id' => $product))->result_array();        
        echo $data['result'][0]['amc_amount'] * $quantity;
    }
        public function add_quotation()
    {
        $id = $this->input->post('id');
        $record = $this->db->get_where("temp", array('id' => $id))->result_array();
        $o_id = rand(1000000, 9999999);
        $u_id = rand(1000000, 9999999);
        $cname = $this->input->post('cname');
        $m_id = $this->input->post('userid');
        //var_dump($m_id);die();
         
            if ($cname == 'customer') 
            {
                 $name = $this->input->post('name');
                    $email = $this->input->post('email');
                    $phone = $this->input->post('phone');
                    $address1 = $this->input->post('address1');
                    $address2 = $this->input->post('address2');
                    
               $dataa = array(
                    
                    'id' => $u_id,
                    'name' => $name,
                    'username' => time(),
                    'email' => $email,
                    'phone' => $phone,
                    'join_time'=> date('Y-m-d'),
                    'address' => $address1 . "<br/>" . $address2,
                    'f_type' => $cname,
                
                  );
               
                    $this->db->insert('member', $dataa);
    

                    foreach($record as $val)
                    {
                       // $data['id'] = $val['id'];
                        $data['product_id'] = $val['product'];
                        $data['cost'] = $val['price'];
                        $data['qty'] = $val['quantity'];
                        $data['franchisee_id'] = $this->session->name;
                        $data['orderid'] = $o_id;
                        $data['userid'] = $u_id;
                        $data['date'] = date('Y-m-d');

                        $this->db->insert('product_sale',$data);
                        
                        $this->db->where('id',$val['id']);
                        $this->db->delete("temp"); 
                    }
            }
            else
            {  
              
                    foreach($record as $val)
                    {
                       /// $data['id'] = $val['id'];
                        $data['product_id'] = $val['product'];
                        $data['cost'] = $val['price'];
                        $data['qty'] = $val['quantity'];
                        $data['franchisee_id'] = $this->session->name;
                        $data['orderid'] = $o_id;
                        $data['userid'] = $m_id;
                        $data['date'] = date('Y-m-d');
//var_dump($data);die();
                        $this->db->insert('product_sale',$data);
                        
                        $this->db->where('id',$val['id']);
                        $this->db->delete("temp"); 
                    }

            }
     

        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Order placed Successfully.</div>');
            redirect('franchisee/sale_details');
         
    }
    
  


}