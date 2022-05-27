<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                <div class="row _buttons">
                     <div class="col-md-8">
                        <a href="#" onclick="new_template_name(<?php if($this->input->get('project_id')){ echo "'".admin_url('template_name/name?id='.$this->input->get('project_id'))."'";} ?>); return false;" class="btn btn-info pull-left new"><?php echo 'New Template Name'; ?></a>
                     </div>
                     <div class="col-md-4">
                       
                     </div>
                  </div>
                  <hr class="hr-panel-heading hr-10" />
                  <div class="clearfix"></div>
                  <div class="table-responsive">
                      <table class="table">
                          <thead>
                              <tr role="row">
                                  <th>#</th>
                                  <th>Name</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody>
                           <?php foreach ($template_names as $key => $name) { ?>
                              <tr>
                                 <td><?= $key + 1; ?></td>
                                 <td>
                                     <a href="#" class="display-block" ><?= $name['template_name'] ?></a>
                                 </td>
                                 <td>
                                    <div class="row-options">
                                       <a href="#" onclick="edit_template_name(<?= $name['id'] ?>); return false">Edit </a>
                                       <span class="text-dark"> | </span>
                                       <a href="<?= admin_url('template_name/delete_name/'. $name['id']) ?>" class="text-danger _delete task-delete">Delete </a>
                                    </div>
                                 </td>
                              </tr>
                           <?php } ?>
                          </tbody>
                      </table>
                  </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<?php init_tail(); ?>
<script>
   function new_template_name(url, timer_id) {
    url = typeof (url) != 'undefined' ? url : admin_url + 'template_name/name';

    var $leadModal = $('#lead-modal');
    if ($leadModal.is(':visible')) {
        url += '&opened_from_lead_id=' + $leadModal.find('input[name="leadid"]').val();
        if (url.indexOf('?') === -1) {
            url = url.replace('&', '?');
        }
        $leadModal.modal('hide');
    }

    var $taskSingleModal = $('#task-modal');
    if ($taskSingleModal.is(':visible')) {
        $taskSingleModal.modal('hide');
    }

    var $taskEditModal = $('#_task_modal');
    if ($taskEditModal.is(':visible')) {
        $taskEditModal.modal('hide');
    }

    requestGet(url).done(function (response) {
        $('#_task').html(response);
        $("body").find('#_task_modal').modal({
            show: true,
            backdrop: 'static'
        });

        var stopTimerPopover = $('#timer-select-task');
        if (stopTimerPopover.is(':visible')) {
            $('.system-popup-close').click();
            window._timer_id = timer_id;
        }

    }).fail(function (error) {
        alert_float('danger', error.responseText);
    })
}

function edit_template_name(name_id) {
    requestGet('template_name/name/' + name_id).done(function (response) {
        $('#_task').html(response);
        $('#task-modal').modal('hide');
        $("body").find('#_task_modal').modal({
            show: true,
            backdrop: 'static'
        });
    });
}

</script>
</body>
</html>
