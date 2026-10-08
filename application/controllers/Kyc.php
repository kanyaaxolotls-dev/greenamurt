<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kyc extends CI_Controller
{ 
    
     public function kyc_doc()
    {
            $data['my_profile']   = $this->db_model->select_multi('*', 'member_profile', array('userid' => $this->session->user_id));
            $data['my']           = $this->db_model->select_multi('*', 'member', array('id' => $this->session->user_id));
            $this->db->select('*')->where(array('sponsor' => $this->session->user_id,));
            $data['my_team'] = $this->db->get('member')->result_array();
            $data['layout'] = 'kyc/upload_doc.php';
            $this->load->view('member/index', $data);
    }
    
    public function get_ifsc_details($ifsc = '')
    {
        if (!$ifsc) {
            echo json_encode(['status' => false, 'message' => 'IFSC code is required']);
            return;
        }
    
        $url = "https://ifsc.razorpay.com/" . $ifsc;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($http_code == 200) {
            echo $response; // JSON with bank name, branch, address, etc.
        } else {
            echo json_encode(['status' => false, 'message' => 'Invalid IFSC code']);
        }
    }

}