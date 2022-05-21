<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_open_multipart(admin_url('tasks_template/task/'.$id),array('id'=>'task-form')); ?>

<div class="modal fade<?php if(isset($task)){echo ' edit';} ?>" id="_task_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"<?php if($this->input->get('opened_from_lead_id')){echo 'data-lead-id='.$this->input->get('opened_from_lead_id'); } ?>>
<div class="modal-dialog" role="document">
   <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title" id="myModalLabel">
            <?php echo $title; ?>
         </h4>
      </div>
      <div class="modal-body">
         <div class="row">
            <div class="col-md-12">
               <?php $value = (isset($task) ? $task->name : ''); ?>
               <?php echo render_input('name','task_add_edit_subject',$value); ?>
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="priority" class="control-label"><?php echo _l('task_add_edit_priority'); ?></label>
                        <select name="priority" class="selectpicker" id="priority" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <?php foreach(get_tasks_priorities() as $priority) { ?>
                           <option value="<?php echo $priority['id']; ?>"<?php if(isset($task) && $task->priority == $priority['id'] || !isset($task) && get_option('default_task_priority') == $priority['id']){echo ' selected';} ?>><?php echo $priority['name']; ?></option>
                           <?php } ?>
                           <?php hooks()->do_action('task_priorities_select', (isset($task) ? $task : 0)); ?>
                        </select>
                     </div>
                  </div>
               </div>
               <?php if (!isset($task)) { ?>
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group select-placeholder>">                     
                     <label for="assignees"><?php echo _l('task_single_assignees'); ?></label>
                     <select name="assignees[]" id="assignees" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" multiple data-live-search="true">
                     <?php foreach($members as $member){ ?>
                        <option value="<?php echo $member['staffid']; ?>" <?php if((get_option('new_task_auto_assign_current_member') == '1') && get_staff_user_id()  == $member['staffid']){echo 'selected'; } ?>>
                           <?php echo $member['firstname'] . ' ' .  $member['lastname']; ?>
                        </option>
                        <?php } ?>
                     </select>
                     </div>
                  </div>
               </div>
               <?php } ?>
               
               <div class="form-group checklist-templates-wrapper<?php if(count($checklistTemplates) == 0 || isset($task)){echo ' hide';}  ?>">
                  <label for="checklist_items"><?php echo _l('insert_checklist_templates'); ?></label>
                  <select id="checklist_items" name="checklist_items[]" class="selectpicker checklist-items-template-select" multiple="1" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex') ?>" data-width="100%" data-live-search="true" data-actions-box="true">
                     <option value="" class="hide"></option>
                     <?php foreach($checklistTemplates as $chkTemplate){ ?>
                     <option value="<?php echo $chkTemplate['id']; ?>">
                        <?php echo $chkTemplate['description']; ?>
                     </option>
                     <?php } ?>
                  </select>
               </div>
               <div class="form-group">
                  <div id="inputTagsWrapper">
                     <label for="tags" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i> <?php echo _l('tags'); ?></label>
                     <input type="text" class="tagsinput" id="tags" name="tags" value="<?php echo (isset($task) ? prep_tags_input(get_tags_in($task->id,'task')) : ''); ?>" data-role="tagsinput">
                  </div>
               </div>
               <?php $rel_id_custom_field = (isset($task) ? $task->id : false); ?>
               <?php echo render_custom_fields('tasks',$rel_id_custom_field); ?>
               <hr />
               <p class="bold"><?php echo _l('task_add_edit_description'); ?></p>
               <?php
               // onclick and onfocus used for convert ticket to task too
               echo render_textarea('description','',(isset($task) ? $task->description : ''),array('rows'=>6,'placeholder'=>_l('task_add_description'),'data-task-ae-editor'=>true, !is_mobile() ? 'onclick' : 'onfocus'=>(!isset($task) || isset($task) && $task->description == '' ? 'init_editor(\'.tinymce-task\', {height:200, auto_focus: true});' : '')),array(),'no-mbot','tinymce-task'); ?>
            </div>
         </div>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
         <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
      </div>
   </div>
</div>
<?php echo form_close(); ?>

<script>
   $(function(){

      appValidateForm($('#task-form'), {
         name: 'required',
      },task_template_form_handler);
      
      init_datepicker();
      init_color_pickers();
      init_selectpicker();
    
      var _allAssigneeSelect = $("#assignees").html();
   });

   function task_template_form_handler(form) {

    tinymce.triggerSave();

    // Disable the save button in cases od duplicate clicks
    $('#_task_modal').find('button[type="submit"]').prop('disabled', true);

    $("#_task_modal input[type=file]").each(function () {
        if ($(this).val() === "") {
            $(this).prop('disabled', true);
        }
    });

    var formURL = form.action;
    var formData = new FormData($(form)[0]);

    $.ajax({
        type: $(form).attr('method'),
        data: formData,
        mimeType: $(form).attr('enctype'),
        contentType: false,
        cache: false,
        processData: false,
        url: formURL
    }).done(function (response) {
        response = JSON.parse(response);
        if (response.success === true || response.success == 'true') {
            $('#_task_modal').modal('hide');
            $('#task-modal').modal('hide');
            alert_float('success', response.message);
            setTimeout(function(){ 
                location.reload();
            }, 1000);
        }
        
    }).fail(function (error) {
        alert_float('danger', JSON.parse(error.responseText));
    });

    return false;
}
</script>
