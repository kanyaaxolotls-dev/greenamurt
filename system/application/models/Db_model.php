<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Db_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->common_model->__session();
    }
    public function get_userids_by_pan($pan_no) {
        $this->db->select('userid');
        $this->db->where('pan_no', $pan_no);
        $query   = $this->db->get('withdraw_request');
        $userids = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $userids[] = $row->userid;
            }
        }
        return $userids;
    }

    public function get_task_menu($name)
    {
        $this->db->select('tasks');
        $this->db->from('tbl_roles');
        $this->db->where('id', $name);
        $query  = $this->db->get();
        $result = $query->row();
    
        if (!$result) {
            return [];
        }
    
        $task_array = explode(",", $result->tasks);
        $this->db->select('*');
        $this->db->from('tbl_task_manager');
        $this->db->where_in('id', $task_array);
        $this->db->where('status', 1);
        $this->db->where('child_of', 0);
        $this->db->order_by('position');
        $parent_query = $this->db->get();
        $parent_menus = $parent_query->result();
        $menu_data    = [];
        foreach ($parent_menus as $menu) {
            $this->db->select('*');
            $this->db->from('tbl_task_manager');
            $this->db->where_in('id', $task_array);
            $this->db->where('status', 1);
            $this->db->where('child_of', $menu->id);
            $this->db->order_by('position');
            $child_query = $this->db->get();
            $child_menus = $child_query->result();
            $menu_data[] = [
                'parent' => $menu,
                'children' => $child_menus
            ];
        }
        return $menu_data;
    }

    /*
    public function select($data, $table, $where = "1=1")
    {
        $this->db->select($data)->from($table)->where($where)->order_by('id', 'DESC')->limit(1);
        $result = $this->db->get()->row();
        return $result->$data;
    }*/
    public function select($data, $table, $where = "1=1")
    {
        $this->db->select($data)->from($table)->where($where)->order_by('id', 'DESC')->limit(1);
        $query = $this->db->get();
        
        if ($query && $query->num_rows() > 0) 
        {
            $result = $query->row();
            return isset($result->$data) ? $result->$data : null;

        } 
        else 
        {
            return null; 
        }
    }

    public function select_multi($data, $table, $where = "1=1")
    {
        $this->db->select($data)->from($table)->where($where)->order_by('id', 'DESC')->limit(1);
        $query = $this->db->get();
        if ($query && is_object($query)) {
            return $query->row();
        }
        return null;
    }

    public function update($data, $table, $where = "1=1")
    {
        $this->db->where($where);
        $this->db->update($table, $data);
    }

    public function count_all($table, $where = "1=1")
    {
        $this->db->from($table);
        $this->db->where($where);
        return $this->db->count_all_results();

    }
    
    public function sum($data, $table, $where = "1=1")
    {
        $this->db->select_sum($data);
        $this->db->where($where);
        $this->db->from($table);
        $result = $this->db->get()->row();
        return $result->$data + 0;
    }
    /*public function sum($data, $table, $where = "1=1")
    {
        $this->db->select_sum($data);
    
        // Handle raw or array conditions
        if (is_array($where)) {
            $this->db->where($where);
        } else {
            $this->db->where($where, NULL, FALSE); // allow raw condition
        }
    
        $this->db->from($table);
    
        $query = $this->db->get();
    
        // If query failed, prevent "row() on boolean"
        if (!$query) {
            return 0;
        }
    
        $result = $query->row();
    
        // If no data found, avoid undefined error
        if (!$result || !isset($result->$data)) {
            return 0;
        }
    
        return $result->$data + 0;
    }*/

    
    public function get_total_count($userId, $maxLevels) {
        $allUsercodes = [$userId];
        $totalCount   = 0;

        for ($i = 1; $i <= $maxLevels; $i++) {
            $placeholders = implode(',', array_fill(0, count($allUsercodes), '?'));
            $sql   = "SELECT id FROM member WHERE position IN ($placeholders)";
            $query = $this->db->query($sql, $allUsercodes);
            
            if (!$query) {
                die('Error in executing the SQL statement: ' . $this->db->error());
            }
            
            $usercodes = [];
            foreach ($query->result_array() as $row) {
                $usercodes[] = $row['id'];
            }
            
            if (empty($usercodes)) {
                break;
            }
            
            $allUsercodes = array_merge($allUsercodes, $usercodes);
            $totalCount   = count($usercodes);
        }
        return $totalCount;
    }
    
    public function get_active_count($userId, $maxLevels) {
        $allUsercodes = [$userId];
        $totalCount   = 0;
    
        for ($i = 1; $i <= $maxLevels; $i++) {
            $placeholders = implode(',', array_fill(0, count($allUsercodes), '?'));
            $sql   = "SELECT id, topup FROM member WHERE position IN ($placeholders)";
            $query = $this->db->query($sql, $allUsercodes);
            
            if (!$query) {
                die('Error in executing the SQL statement: ' . $this->db->error());
            }
            
            $usercodes = [];
            foreach ($query->result_array() as $row) {
                $topupp = $this->db_model->sum('cost', 'product_sale',array('userid' => $row['id']));
                if ($topupp >= 1) {
                    $totalCount++;
                }
                $usercodes[] = $row['id'];
            }
            
            if (empty($usercodes)) {
                break;
            }
            
            $allUsercodes = $usercodes;  
        }
        return $totalCount;
    }
    
    function amount_inword(float $number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
            $digits = array('', 'hundred','thousand','lakh', 'crore');
            while( $i < $digits_length ) {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i += $divider == 10 ? 1 : 2;
                if ($number) {
                    $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                    $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
                } else $str[] = null;
            }
            $Rupees = implode('', array_reverse($str));
            $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
    }

    public function get_person_id_count($phone, $email, $name, $tax_no = '')
    {
        $phone  = trim($phone);
        $email  = trim($email);
        $name   = trim($name);
        $tax_no = trim($tax_no);

        $this->db->select('member.id');
        $this->db->from('member');
        $this->db->where('LOWER(TRIM(member.phone))', strtolower($phone));
        $this->db->where('LOWER(TRIM(member.email))', strtolower($email));
        $this->db->where('LOWER(TRIM(member.name))', strtolower($name));

        if (!empty($tax_no) && strtolower($tax_no) !== 'n/a') {
            $this->db->join('member_profile', 'member.id = member_profile.userid', 'left');
            $this->db->where('LOWER(TRIM(member_profile.tax_no))', strtolower($tax_no));
        }

        return $this->db->count_all_results();
    }
}

