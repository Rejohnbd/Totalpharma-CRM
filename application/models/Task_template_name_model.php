<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task_template_name_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    

    public function get_template_name()
    {
        return $this->db->get(db_prefix() . 'task_template_names')->result_array();
    }
    
    public function get($id, $where = [])
    {
        $is_admin = is_admin();
        $this->db->where('id', $id);
        $task = $this->db->get(db_prefix() . 'task_template_names')->row();

        return $task;
    }
    
    public function add($data)
    {
        $this->db->insert(db_prefix() . 'task_template_names', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }
        return false;
    }

    
    public function update($data, $id)
    {
        $affectedRows      = 0;
        
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'task_template_names', $data);
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            return true;
        }
        return false;
    }
    
    public function delete_name($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'task_template_names');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }
}