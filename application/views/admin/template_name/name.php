<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_open_multipart(admin_url('template_name/name/'.$id),array('id'=>'task-form')); ?>
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
               <?php $value = (isset($task) ? $task->template_name : '');?>

               <?php echo render_input('template_name','Template Name', $value); ?>
           
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
         template_name: 'required',
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
