<?php

use app\services\utilities\Date;
use app\services\tasks\TasksKanban;

defined('BASEPATH') or exit('No direct script access allowed');

class Tasks_template extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('projects_model');
        $this->load->model('tasks_template_model');
    }

    
    public function index()
    {
        $this->list_tasks();
    }
    
    // List all tasks
    public function list_tasks($id = '')
    {
        $data['bodyclass']     = 'tasks-template-page';
        $data['title'] = _l('als_tasks_temp');
        $data['task_templates'] = $this->tasks_template_model->get_task_templates();
        $data['staff'] =  $this->tasks_template_model->get_staff_info();
        $this->load->view('admin/tasks_template/manage', $data);
    }

    // Add new task or update existing
    public function task($id = '')
    {
      
        // if (!has_permission('tasks', '', 'edit') && !has_permission('tasks', '', 'create')) {
        //     ajax_access_denied();
        // }

        $data = [];
        if ($this->input->post()) {
            $data                = $this->input->post();
            $data['description'] = html_purify($this->input->post('description', false));
            if ($id == '') {
                // if (!has_permission('tasks', '', 'create')) {
                //     header('HTTP/1.0 400 Bad error');
                //     echo json_encode([
                //         'success' => false,
                //         'message' => _l('access_denied'),
                //     ]);
                //     die;
                // }
                $id      = $this->tasks_template_model->add($data);
                
                $_id     = false;
                $success = false;
                $message = '';
                if ($id) {
                    $success       = true;
                    $_id           = $id;
                    $message       = _l('added_successfully', _l('new_task_temp'));
                }
                echo json_encode([
                    'success' => $success,
                    'id'      => $_id,
                    'message' => $message,
                ]);

            } else {
                // if (!has_permission('tasks', '', 'edit')) {
                //     header('HTTP/1.0 400 Bad error');
                //     echo json_encode([
                //         'success' => false,
                //         'message' => _l('access_denied'),
                //     ]);
                //     die;
                // }
                $success = $this->tasks_template_model->update($data, $id);
                $message = '';
                if ($success) {
                    $message = _l('updated_successfully', _l('new_task_temp'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                    'id'      => $id,
                ]);
            }

            die;
        }

        $data['checklistTemplates'] = $this->tasks_template_model->get_checklist_templates();
        
        if ($id == '') {
            $title = _l('add_new', _l('task_template_lowercase'));
        } else {
            $data['task'] = $this->tasks_template_model->get($id);
            $title = _l('edit', _l('task_template_lowercase')) . ' ' . $data['task']->name;
            // print_r();
            // die();
            // $this->tasks_template_model->get_tasks_name_by_id($data['task']->tags_ids);
        }
        
        $data['members'] = $this->staff_model->get();
        $data['id']      = $id;
        $data['title']   = $title;
     
        $this->load->view('admin/tasks_template/task', $data);
    }


    
    public function table()
    {
        // $output = $this->db->get(db_prefix() . 'tasks_template')->result_array();

        $this->app->get_table_data('tasks_template');
        echo json_encode($output);
        die;
    }
    
    // Delete task from database
    public function delete_task($id)
    {
        // if (!has_permission('tasks', '', 'delete')) {
        //     access_denied('tasks');
        // }
        $success = $this->tasks_template_model->delete_task($id);
        $message = _l('problem_deleting', _l('task_template_lowercase'));
        if ($success) {
            $message = _l('deleted', _l('new_task_temp'));
            set_alert('success', $message);
        } else {
            set_alert('warning', $message);
        }

        if (strpos($_SERVER['HTTP_REFERER'], 'tasks/index') !== false || strpos($_SERVER['HTTP_REFERER'], 'tasks/view') !== false) {
            redirect(admin_url('tasks'));
        } elseif (preg_match("/projects\/view\/[1-9]+/", $_SERVER['HTTP_REFERER'])) {
            $project_url = explode('?', $_SERVER['HTTP_REFERER']);
            redirect($project_url[0] . '?group=project_tasks');
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
}
