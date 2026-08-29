
<?php
/**
 * @ PHP 5.6
 * @ Decoder version : 1.0.0.4
 * @ Release on : 24.03.2018
 * @ Website    : http://EasyToYou.eu
 *
 * @ Zend guard decoder PHP 5.6
 **/

defined("BASEPATH") or exit("No direct script access allowed");
class Earningsetting_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->common_model->__session();
	}
	
	function getAllEarningSettings() {

		return $this->db->get('earning_settings')->result();
   
    }
	
	function getSingleRecord($id) {

		return $this->db_model->select_multi('*', 'earning_settings', array('id' => $id));
   
    }
	
	public function insertEarningSettings($data) {

        if ($this->db->insert("earning_settings", $data)) {

            return $this->db->insert_id();

        } else {

            return 0;

        }

    }
	
	  public function update($data, $where) {

        if ($this->db->update("earning_settings", $data, $where)) {

            return 1;

        } else {

            return 0;

        }

    }

	 public function delete($where) {

        $id = $where['id'];

        if ($this->db->delete("earning_settings", $where)) {

           // $this->db->delete("earning_settings", array("id" => $id));

            return 1;

        } else {

            return 0;

        }

    }

	
}

defined('BASEPATH') || true;

?>
