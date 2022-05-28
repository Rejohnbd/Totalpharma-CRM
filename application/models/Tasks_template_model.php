<?php

use app\services\AbstractKanban;
use app\services\tasks\TasksKanban;

defined('BASEPATH') or exit('No direct script access allowed');

class Tasks_template_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('projects_model');
        $this->load->model('staff_model');
    }
    
    // Get task by id
    // @param  mixed $id task id
    // @return object

    public function get_task_templates()
    {
        return $this->db->get(db_prefix() . 'tasks_template')->result_array();
    }

    public function get_staff_info()
    {
        $staff = [];
        $staffArray = $this->db->get(db_prefix() . 'staff')->result_array();
        // foreach ($staffArray as $key => $value) {
        //     $staff[$value['staffid']] = $value['firstname'] . ' ' . $value['lastname'];
        // }
        foreach ($staffArray as $key => $value) {
            $staff[$key] = array(
                'id'    => $value['staffid'],
                'name'  => $value['firstname'] . ' ' . $value['lastname']
            );
        }
        return $staff;
    }
    
    public function get($id, $where = [])
    {
        $is_admin = is_admin();
        $this->db->where('id', $id);
        $this->db->where($where);
        $task = $this->db->get(db_prefix() . 'tasks_template')->row();

        return hooks()->apply_filters('get_task', $task);
    }
    
    public function add($data, $clientRequest = false)
    {
        $tags = '';
        if (isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }

        if (isset($data['assignees'])) {
            $assignees = implode (", ", $data['assignees']);
            unset($data['assignees']);
        }
        $data['tags_ids'] = $tags;
        $data['assigneed_ids'] = $assignees;
      

        $this->db->insert(db_prefix() . 'tasks_template', $data);
       
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            handle_tags_save($tags, $insert_id, 'task');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update task data
     * @param  array $data task data $_POST
     * @param  mixed $id   task id
     * @return boolean
     */
    
    public function update($data, $id, $clientRequest = false)
    {
        $affectedRows      = 0;
        
        if ($clientRequest == false) {
            $original_task = $this->get($id);
        }

        $data['tags_ids'] = $data['tags'];
        unset($data['tags']);

        $assignees = implode (", ", $data['assignees']);
        unset($data['assignees']);
        $data['assigneed_ids'] = $assignees;
        
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'tasks_template', $data);
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            // hooks()->do_action('after_update_task', $id);
            log_activity('Task Template Updated [ID:' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }
    
    public function delete_task($id, $log_activity = true)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'tasks_template');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    public function get_checklist_templates()
    {
        $this->db->order_by('description', 'asc');

        return $this->db->get(db_prefix() . 'tasks_checklist_templates')->result_array();
    }

    // public function get_tasks_name_by_id($id)
    // {
    //     $this->db->where('id', $id);
    //     $tags = $this->db->get(db_prefix() . 'tags')->row();  
    //     print_r($tags);
    //     exit();
    //     return $tags;
    // }

    public function get_template_name()
    {
        return $this->db->get(db_prefix() . 'task_template_names')->result_array();   
    }
    
}