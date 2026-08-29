<?php

class Admin_model extends CI_Model {

    public function get_role_menu($role_name) {
        // Get role ID first
        $role = $this->db->get_where('admin_roles', ['name' => $role_name])->row();
        if (!$role) return [];
        
        // Get all allowed menu IDs for this role
        $this->db->select('menu_id');
        $allowed_menu_ids = $this->db->get_where('role_permissions', ['role_id' => $role->id])->result_array();
        $menu_ids = array_column($allowed_menu_ids, 'menu_id');
        if (empty($menu_ids)) return [];
        
        // Get parent menus
        $this->db->select('*');
        $this->db->from('admin_menu');
        $this->db->where_in('id', $menu_ids);
        $this->db->where('parent_id', 0);
        $this->db->where('status', 1);
        $this->db->order_by('position', 'ASC');
        $parents = $this->db->get()->result();
        
        $menu_data = [];
        
        foreach ($parents as $parent) {
            // Get child menus for this parent
            $this->db->select('*');
            $this->db->from('admin_menu');
            $this->db->where_in('id', $menu_ids);
            $this->db->where('parent_id', $parent->id);
            $this->db->where('status', 1);
            $this->db->order_by('position', 'ASC');
            $children = $this->db->get()->result();
            
            $menu_data[] = [
                'parent' => $parent,
                'children' => $children
            ];
        }
        
        return $menu_data;
    }
    
    // Get all roles for admin interface
    public function get_all_roles() {
        return $this->db->get_where('admin_roles', ['status' => 1])->result();
    }
    
    // Get all menu items for admin interface
    public function get_all_menu_items() {
        $this->db->order_by('parent_id, position', 'ASC');
        return $this->db->get_where('admin_menu', ['status' => 1])->result();
    }
    
    // Get permissions for a role
    public function get_role_permissions($role_id) {
        $this->db->where('role_id', $role_id);
        $result = $this->db->get('role_permissions')->result();
        return array_column($result, 'menu_id');
    }
    
    // Update permissions for a role
    public function update_role_permissions($role_id, $menu_ids) {
        // Delete existing permissions
        $this->db->where('role_id', $role_id);
        $this->db->delete('role_permissions');
        
        // Add new permissions
        if (!empty($menu_ids)) {
            $data = [];
            foreach ($menu_ids as $menu_id) {
                $data[] = [
                    'role_id' => $role_id,
                    'menu_id' => $menu_id
                ];
            }
            $this->db->insert_batch('role_permissions', $data);
        }
    }
}


