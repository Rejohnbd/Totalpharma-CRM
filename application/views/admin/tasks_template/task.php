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
               
               <?php
                  if(isset($task)
                     && $task->status == Tasks_model::STATUS_COMPLETE
                     && (has_permission('create') || has_permission('edit'))){
                     echo render_datetime_input('datefinished','task_finished',_dt($task->datefinished));
                  }
               ?>
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
   var _rel_id = $('#rel_id'),
   _rel_type = $('#rel_type'),
   _rel_id_wrapper = $('#rel_id_wrapper'),
   _current_member = undefined,
   data = {};

   var _milestone_selected_data;
   _milestone_selected_data = undefined;

   <?php if(get_option('new_task_auto_assign_current_member') == '1') { ?>
      _current_member = "<?php echo get_staff_user_id(); ?>";
   <?php } ?>
   $(function(){

    // $( "body" ).off( "change", "#rel_id" );

    var inner_popover_template = '<div class="popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>';

    $('#_task_modal .task-menu-options .trigger').popover({
      html: true,
      placement: "bottom",
      trigger: 'click',
      title:"<?php echo _l('actions'); ?>",
      content: function() {
       return $('body').find('#_task_modal .task-menu-options .content-menu').html();
     },
     template: inner_popover_template
   });

    custom_fields_hyperlink();

    appValidateForm($('#task-form'), {
      name: 'required',
      startdate: 'required',
      repeat_every_custom: { min: 1},
    },task_form_handler);

    $('.rel_id_label').html(_rel_type.find('option:selected').text());

    _rel_type.on('change', function() {

     var clonedSelect = _rel_id.html('').clone();
     _rel_id.selectpicker('destroy').remove();
     _rel_id = clonedSelect;
     $('#rel_id_select').append(clonedSelect);
     $('.rel_id_label').html(_rel_type.find('option:selected').text());

     task_rel_select();     
     if($(this).val() != ''){
      _rel_id_wrapper.removeClass('hide');
    } else {
      _rel_id_wrapper.addClass('hide');
    }
    init_project_details(_rel_type.val());
   });

    init_datepicker();
    init_color_pickers();
    init_selectpicker();
    task_rel_select();
   
   var _allAssigneeSelect = $("#assignees").html();

    $('body').on('change','#rel_id',function(){
     if($(this).val() != ''){
       if(_rel_type.val() == 'project'){
         $.get(admin_url + 'projects/get_rel_project_data/'+$(this).val()+'/'+taskid,function(project){
           $("select[name='milestone']").html(project.milestones);
           if(typeof(_milestone_selected_data) != 'undefined'){
            $("select[name='milestone']").val(_milestone_selected_data.id);
            $('input[name="duedate"]').val(_milestone_selected_data.due_date)
          }
          $("select[name='milestone']").selectpicker('refresh');
         
           $("#assignees").html(project.assignees);
           if(typeof(_current_member) != 'undefined'){ 
            $("#assignees").val(_current_member);
           }
           $("#assignees").selectpicker('refresh')
          if(project.billing_type == 3){
           $('.task-hours').addClass('project-task-hours');
          } else {
            $('.task-hours').removeClass('project-task-hours');
          }

          if(project.deadline) {
            var $duedate = $('#_task_modal #duedate');
            var currentSelectedTaskDate = $duedate.val();
            $duedate.attr('data-date-end-date', project.deadline);
            $duedate.datetimepicker('destroy');
            init_datepicker($duedate);

            if(currentSelectedTaskDate) {
               var dateTask = new Date(unformat_date(currentSelectedTaskDate));
               var projectDeadline = new Date(project.deadline);
               if(dateTask > projectDeadline) {
                  $duedate.val(project.deadline_formatted);
               }
            }
          } else {
            reset_task_duedate_input();
          }
          init_project_details(_rel_type.val(),project.allow_to_view_tasks);
         },'json');
       
 

       } else {
         reset_task_duedate_input();
       }
     }
   });

    <?php if(!isset($task) && $rel_id != ''){ ?>
      _rel_id.change();
      <?php } ?>

   _rel_type.on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
      if (previousValue == 'project') {
         $("#assignees").html(_allAssigneeSelect);
      if(typeof(_current_member) != 'undefined'){ 
         $("#assignees").val(_current_member);
      }
      $("#assignees").selectpicker('refresh')
      }
   });

    });

   <?php if(isset($_milestone_selected_data)){ ?>
    _milestone_selected_data = '<?php echo json_encode($_milestone_selected_data); ?>';
    _milestone_selected_data = JSON.parse(_milestone_selected_data);
    <?php } ?>

    function task_rel_select(){
      var serverData = {};
      serverData.rel_id = _rel_id.val();
      data.type = _rel_type.val();
      init_ajax_search(_rel_type.val(),_rel_id,serverData);
     }

    //  function init_project_details(type,tasks_visible_to_customer){
    //   var wrap = $('.non-project-details');
    //   var wrap_task_hours = $('.task-hours');
    //   if(type == 'project'){
    //     if(wrap_task_hours.hasClass('project-task-hours') == true){
    //       wrap_task_hours.removeClass('hide');
    //     } else {
    //       wrap_task_hours.addClass('hide');
    //     }
    //     wrap.addClass('hide');
    //     $('.project-details').removeClass('hide');
    //   } else {
    //     wrap_task_hours.removeClass('hide');
    //     wrap.removeClass('hide');
    //     $('.project-details').addClass('hide');
    //     $('.task-visible-to-customer').addClass('hide').prop('checked',false);
    //   }
    //   if(typeof(tasks_visible_to_customer) != 'undefined'){
    //     if(tasks_visible_to_customer == 1){
    //       $('.task-visible-to-customer').removeClass('hide');
    //       $('.task-visible-to-customer input').prop('checked',true);
    //     } else {
    //       $('.task-visible-to-customer').addClass('hide')
    //       $('.task-visible-to-customer input').prop('checked',false);
    //     }
    //   }
    // }
   //  function reset_task_duedate_input() {
   //    var $duedate = $('#_task_modal #duedate');
   //    $duedate.removeAttr('data-date-end-date');
   //    $duedate.datetimepicker('destroy');
   //    init_datepicker($duedate);
   // }
</script>
