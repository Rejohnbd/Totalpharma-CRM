<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task_template_name extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('task_template_name_model');
    }

    
    public function index()
    {
        $this->list_template_name();
    }
    
    public function list_template_name($id = '')
    {
        $data['bodyclass']      = 'template-name';
        $data['title']          = 'Template Name';
        $data['template_names'] = $this->task_template_name_model->get_template_name();
        $this->load->view('admin/template_name/manage', $data);
    }

    // Add new task or update existing
    public function name($id = '')
    {
      
        // if (!has_permission('tasks', '', 'edit') && !has_permission('tasks', '', 'create')) {
        //     ajax_access_denied();
        // }

        $data = [];
        if ($this->input->post()) {
            $data                = $this->input->post();
            if ($id == '') {
                // if (!has_permission('tasks', '', 'create')) {
                //     header('HTTP/1.0 400 Bad error');
                //     echo json_encode([
                //         'success' => false,
                //         'message' => _l('access_denied'),
                //     ]);
                //     die;
                // }
                $id      = $this->task_template_name_model->add($data);
                
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
                $success = $this->task_template_name_model->update($data, $id);
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

        
        if ($id == '') {
            $title = _l('add_new', 'template name');
        } else {
            $data['task']  = $this->task_template_name_model->get($id);
            $title = _l('edit', 'template name') . ' ' . $data['task']->template_name;
            // print_r();
            // die();
            // $this->tasks_template_model->get_tasks_name_by_id($data['task']->tags_ids);
        }
        
        $data['id']      = $id;
        $data['title']   = $title;
     
        $this->load->view('admin/template_name/name', $data);
    }


    
    public function table()
    {
        // $output = $this->db->get(db_prefix() . 'tasks_template')->result_array();

        $this->app->get_table_data('tasks_template');
        echo json_encode($output);
        die;
    }
    
    
    public function delete_name($id)
    {
        // if (!has_permission('tasks', '', 'delete')) {
        //     access_denied('tasks');
        // }
        $success = $this->task_template_name_model->delete_name($id);
        $message = _l('problem_deleting', 'Template Name');
        if ($success) {
            $message = _l('deleted', 'Template Name');
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
