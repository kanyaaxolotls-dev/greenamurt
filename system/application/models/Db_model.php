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

    public function check_and_update_wallet_schema()
    {
        // 1. Table: wallet
        if (!$this->db->table_exists('wallet')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `wallet` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `userid` VARCHAR(50) NOT NULL,
                `balance` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
                `pan_no` VARCHAR(50) NULL DEFAULT '',
                `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `idx_wallet_userid` (`userid`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        } else {
            $f_wallet = $this->db->list_fields('wallet');
            if (!in_array('updated_at', $f_wallet)) {
                $this->db->query("ALTER TABLE `wallet` ADD COLUMN `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
            }
            if (!in_array('pan_no', $f_wallet)) {
                $this->db->query("ALTER TABLE `wallet` ADD COLUMN `pan_no` VARCHAR(50) NULL DEFAULT '' AFTER `balance`");
            }
        }

        // 2. Table: product_wallet
        if (!$this->db->table_exists('product_wallet')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `product_wallet` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `userid` VARCHAR(50) NOT NULL,
                `balance` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
                `type` VARCHAR(50) NOT NULL DEFAULT 'product',
                PRIMARY KEY (`id`),
                KEY `idx_pwallet_userid` (`userid`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        // 3. Table: wallet_transaction
        if (!$this->db->table_exists('wallet_transaction')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `wallet_transaction` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `userid` VARCHAR(50) NOT NULL,
                `type` ENUM('Credit', 'Debit') NOT NULL DEFAULT 'Credit',
                `amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
                `ref_id` VARCHAR(100) NULL DEFAULT '',
                `other` TEXT NULL,
                `created_date` DATETIME NOT NULL,
                PRIMARY KEY (`id`),
                KEY `idx_wtrans_userid` (`userid`),
                KEY `idx_wtrans_ref` (`ref_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        // 4. Table: withdraw_request
        if (!$this->db->table_exists('withdraw_request')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `withdraw_request` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `userid` VARCHAR(50) NOT NULL,
                `amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
                `tax` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
                `admin_tax` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
                `tds_tax` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
                `net_paid` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
                `pan_no` VARCHAR(50) NULL DEFAULT '',
                `date` DATE NOT NULL,
                `paid_date` DATE NULL DEFAULT NULL,
                `withdraw_in` VARCHAR(50) NOT NULL DEFAULT 'Bank',
                `status` ENUM('Un-Paid', 'Pending', 'Hold', 'Paid', 'Rejected') NOT NULL DEFAULT 'Un-Paid',
                `tid` VARCHAR(100) NULL DEFAULT '',
                `hold_reason` TEXT NULL,
                `reject_reason` TEXT NULL,
                `processed_by` VARCHAR(100) NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `idx_wreq_userid` (`userid`),
                KEY `idx_wreq_status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        } else {
            $fields = $this->db->list_fields('withdraw_request');
            if (!in_array('admin_tax', $fields)) {
                $this->db->query("ALTER TABLE `withdraw_request` ADD COLUMN `admin_tax` DECIMAL(11,2) NOT NULL DEFAULT '0.00' AFTER `tax`");
            }
            if (!in_array('tds_tax', $fields)) {
                $this->db->query("ALTER TABLE `withdraw_request` ADD COLUMN `tds_tax` DECIMAL(11,2) NOT NULL DEFAULT '0.00' AFTER `admin_tax`");
            }
            if (!in_array('net_paid', $fields)) {
                $this->db->query("ALTER TABLE `withdraw_request` ADD COLUMN `net_paid` DECIMAL(11,2) NOT NULL DEFAULT '0.00' AFTER `tds_tax`");
            }
            if (!in_array('reject_reason', $fields)) {
                $this->db->query("ALTER TABLE `withdraw_request` ADD COLUMN `reject_reason` TEXT NULL AFTER `hold_reason`");
            }
            if (!in_array('processed_by', $fields)) {
                $this->db->query("ALTER TABLE `withdraw_request` ADD COLUMN `processed_by` VARCHAR(100) NULL AFTER `paid_date`");
            }
        }

        // 5. Table: deposite
        if (!$this->db->table_exists('deposite')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `deposite` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `userid` VARCHAR(50) NOT NULL,
                `amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
                `screenshot` VARCHAR(255) NULL DEFAULT '',
                `status` ENUM('pending', 'Approved', 'Rejected') NOT NULL DEFAULT 'pending',
                `date` DATE NOT NULL,
                PRIMARY KEY (`id`),
                KEY `idx_dep_userid` (`userid`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        // 6. Table: transfer_balance_records
        if (!$this->db->table_exists('transfer_balance_records')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `transfer_balance_records` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `transfer_from` VARCHAR(50) NOT NULL,
                `transfer_to` VARCHAR(50) NOT NULL,
                `amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
                `time` DATETIME NOT NULL,
                PRIMARY KEY (`id`),
                KEY `idx_tbr_from` (`transfer_from`),
                KEY `idx_tbr_to` (`transfer_to`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }
    }

    public function get_wallet_summary($userid)
    {
        $userid = trim($userid);
        if (empty($userid)) {
            return [
                'total_earned'       => 0.0,
                'wallet_balance'     => 0.0,
                'pending_withdrawal' => 0.0,
                'total_withdrawn'    => 0.0,
                'available_balance'  => 0.0,
            ];
        }

        // 1. Total Earned from earning table
        $tot_e_row = $this->db->select_sum('amount')->where('userid', $userid)->get('earning')->row();
        $total_earned = $tot_e_row ? floatval($tot_e_row->amount) : 0.0;

        // 2. Current Wallet Balance from wallet table
        $w_row = $this->db->select('balance')->where('userid', $userid)->get('wallet')->row();
        $wallet_balance = $w_row ? floatval($w_row->balance) : 0.0;

        // 3. Pending / Held withdrawals from withdraw_request
        $pen_w_row = $this->db->select_sum('amount')->where('userid', $userid)->where_in('status', ['Un-Paid', 'Pending', 'Hold'])->get('withdraw_request')->row();
        $pending_withdrawal = $pen_w_row ? floatval($pen_w_row->amount) : 0.0;

        // 4. Total Paid withdrawals
        $paid_w_row = $this->db->select_sum('amount')->where('userid', $userid)->where('status', 'Paid')->get('withdraw_request')->row();
        $total_withdrawn = $paid_w_row ? floatval($paid_w_row->amount) : 0.0;

        // Available balance is the current active wallet balance
        $available_balance = max(0.0, $wallet_balance);

        return [
            'total_earned'       => $total_earned,
            'wallet_balance'     => $wallet_balance,
            'pending_withdrawal' => $pending_withdrawal,
            'total_withdrawn'    => $total_withdrawn,
            'available_balance'  => $available_balance,
        ];
    }
}

