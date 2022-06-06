<?php

use app\services\utilities\Date;
use app\services\tasks\TasksKanban;

defined('BASEPATH') or exit('No direct script access allowed');

class Tasks_template extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tasks_template_model');
        $this->load->library('session');
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
        $templatesNameArray = $this->tasks_template_model->get_template_name();
        $templates_name = [];
        foreach ($templatesNameArray as $key => $value) {
            $templates_name[$value['id']] = $value['template_name'];
        }
        $data['templates_name'] = $templates_name;
        $data['staff'] =  $this->tasks_template_model->get_staff_info();
        $this->load->view('admin/tasks_template/manage', $data);
    }

    public function tasks_template_by_name($id)
    {
        $this->session->set_userdata('tastTemplateId', $id);
        $data['bodyclass']      = 'tasks-template-page';
        $data['title']          = _l('als_tasks_temp');
        $data['task_templates'] = $this->tasks_template_model->get_task_templates_by_id($id);
        $templatesNameArray     = $this->tasks_template_model->get_template_name();
        $templates_name = [];
        foreach ($templatesNameArray as $key => $value) {
            $templates_name[$value['id']] = $value['template_name'];
        }
        $data['templates_name'] = $templates_name;
        $data['staff'] =  $this->tasks_template_model->get_staff_info();
        $this->load->view('admin/tasks_template/manage_two', $data);
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

                $task_template_checklist_items = [];
                if (isset($data['checklist_description']) && isset($data['check_list_assignees'])) {
                    foreach ($data['checklist_description'] as $key => $value) {
                        $task_template_checklist_items[$key] = array(
                            'description'   => $value,
                            'assignee_id'   => $data['check_list_assignees'][$key]
                        );
                    }
                    unset($data['checklist_description']);
                    unset($data['check_list_assignees']);
                }

                $id      = $this->tasks_template_model->add($data);
                
                $_id     = false;
                $success = false;
                $message = '';
                if ($id) {
                    if(count($task_template_checklist_items) > 0){
                        foreach ($task_template_checklist_items as $key => $value) {
                            $saveData = array(
                                'tasks_template_id' => $id,
                                'description' => $value['description'],
                                'assignee_id' => $value['assignee_id']
                            );
                            $this->db->insert(db_prefix() . 'tasks_template_checklist_items', $saveData);
                        }
                    }

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

                $task_template_checklist_items = [];
                if (isset($data['checklist_description']) && isset($data['check_list_assignees'])) {
                    foreach ($data['checklist_description'] as $key => $value) {
                        $task_template_checklist_items[$key] = array(
                            'description'   => $value,
                            'assignee_id'   => $data['check_list_assignees'][$key]
                        );
                    }
                    unset($data['checklist_description']);
                    unset($data['check_list_assignees']);
                }

                $success = $this->tasks_template_model->update($data, $id);

                $this->db->where('tasks_template_id', $id);
                $this->db->delete(db_prefix() . 'tasks_template_checklist_items');

                if(count($task_template_checklist_items) > 0){
                    foreach ($task_template_checklist_items as $key => $value) {
                        $saveData = array(
                            'tasks_template_id' => $id,
                            'description' => $value['description'],
                            'assignee_id' => $value['assignee_id']
                        );
                        $this->db->insert(db_prefix() . 'tasks_template_checklist_items', $saveData);
                    }
                }

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
        $data['templateNames']      = $this->tasks_template_model->get_template_name();
        
        if ($id == '') {
            $title = 'Add New Task';
        } else {
            $data['task'] = $this->tasks_template_model->get($id);
            $this->db->where('tasks_template_id', $id);
            $data['template_items'] = $this->db->get(db_prefix() . 'tasks_template_checklist_items')->result_array();
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
            $this->db->where('tasks_template_id', $id);
            $this->db->delete(db_prefix() . 'tasks_template_checklist_items');
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
