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
                        <?php // if(has_permission('tasks','','create')){ ?>
                        <a href="#" onclick="new_task_template(<?php if($this->input->get('project_id')){ echo "'".admin_url('tasks_template/task?rel_id='.$this->input->get('project_id').'&rel_type=project')."'";} ?>); return false;" class="btn btn-info pull-left new"><?php echo _l('new_task_temp'); ?></a>
                        <?php //} ?>
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
                                  <th>Description</th>
                                  <th>tags</th>
                                  <th>Assigned</th>
                                  <th>Priority</th>
                              </tr>
                          </thead>
                          <tbody>
                           <?php foreach ($task_templates as $key => $template) { ?>
                              <tr>
                                 <td><?= $key + 1; ?></td>
                                 <td>
                                     <a href="#" class="display-block" ><?= $template['name'] ?></a>
                                     <br/>
                                     <div class="row-options">
                                       <a href="#" onclick="edit_task_template(<?= $template['id'] ?>); return false">Edit </a>
                                       <span class="text-dark"> | </span>
                                       <a href="<?= admin_url('tasks_template/delete_task/'. $template['id']) ?>" class="text-danger _delete task-delete">Delete </a>
                                    </div>
                                 </td>
                                 <td><?= $template['description'] ?></td>
                                 <td>
                                    <div class="tags-labels" style="white-space: inherit;">
                                       <?php  foreach(explode(',', $template['tags_ids']) as $key => $value){ ?>
                                          <span class="label label-tag tag-id-<?= $key ?>">
                                             <span class="tag"><?= $value ?></span>
                                          </span>
                                       <?php } ?> 
                                    </div>
                                 </td>
                                 <td>1, 2</td>
                                 <td>
                                    <span style="color:#ff6f00" class="inline-block"></span>
                                    <?= $template['priority'] ?>
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
   function new_task_template(url, timer_id) {
    url = typeof (url) != 'undefined' ? url : admin_url + 'tasks_template/task';

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

function edit_task_template(task_id) {
    requestGet('tasks_template/task/' + task_id).done(function (response) {
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
