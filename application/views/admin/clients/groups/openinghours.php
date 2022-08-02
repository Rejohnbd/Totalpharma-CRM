<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="customer-profile-group-heading" style="position: relative !important;"><?php echo 'Opening Hours' ?></h4>
<div class="row">
   <?php if(isset($client) && count($openinghours) > 0){ ?>
      <?php echo form_open($this->uri->uri_string(),array('class'=>'client-form','autocomplete'=>'off')); ?>
      <input type="hidden" name="form_type" value="opening_hours">
      <div class="additional"></div>
      <div class="col-md-12">
         <div class="form-group">
            <div class="tableview">
      <?php 
         foreach($openinghours as $key=>$value){
            $i = $key+1;
      ?>
         <table class="table">      
            <?php if($i == 1) { ?>
               <tr> 
                   <td class="td" style="width: 90px;">
                       <div class="hideondesktop"><strong>Day</strong></div>
                   </td>
              
                   <td class="td" style="width: 80px;">
                       <div class="hideondesktop"><strong>Closed</strong></div>
                   </td>
             
                   <td class="td" style="width: 130px;">
                       <div class="hideondesktop"><strong>Open 24 Hours</strong></div>
                   </td>
               
                   <td class="td">
                       <div class="hideondesktop"><strong>Opening Time 1</strong></div>
                   </td>
               
                   <td class="td right">
                     <div class="hideondesktop"><strong>Opening Time 2</strong></div>
                   </td>
               </tr>
               <?php } ?>
               <tr class=" p-0">
                  <td class="td" style="width: 90px;">
                     <?= getWeekDay($i); ?>
                  </td>
              
                  <td class="td" style="width: 80px;">
                     <div class="checker">
                        <span>
                           <input type="checkbox" class="closed" id="checkboxClosed_<?= $i ?>" value="<?= $i ?>" <?= $value['is_holiday'] == 1?'checked':'' ?> <?= $value['open_24_hours'] == 1?'disabled':'' ?>>
                           <input type="hidden" name="closed[<?= $i ?>]" id="closed_<?= $i ?>" value="<?= $value['is_holiday'] ?>">
                        </span> closed
                     </div> 
                  </td>
                  <td class="td" style="width: 130px;"> 
                     <div class="checker">
                        <span>
                           <input type="checkbox" class="twentyfour_hours" id="checkbox24_<?= $i ?>" value="<?= $i ?>" <?= $value['open_24_hours'] == 1?'checked':'' ?> <?= $value['is_holiday'] == 1?'disabled':'' ?>>
                           <input type="hidden" name="twentyfour_hours[<?= $i ?>]" id="twentyfour_hours_<?= $i ?>" value="<?= $value['open_24_hours'] ?>">
                        </span> open_24_hours
                     </div> 
                  </td>
                  <td class="td"> 
                     <div style="display: flex;">
                         <input type="text" class="timepicker picker_<?= $i ?> picker1_<?= $i ?>" name="open_time_1[<?= $i ?>]" id="open_time_1_<?= $i ?>" maxlength="5" value="<?= $value['open_time1'] ?>" autocomplete="off" <?= ($value['open_24_hours'] != 1 && $value['is_holiday'] != 1)?'required':'' ?> <?= ($value['open_24_hours'] == 1 || $value['is_holiday'] == 1)?'disabled':'' ?> style="width: 100px; margin-right: 3px;">
                         <input type="text" class="timepicker picker_<?= $i ?> picker1_<?= $i ?>" name="close_time_1[<?= $i ?>]" id="close_time_1_<?= $i ?>" maxlength="5" value="<?= $value['close_time1'] ?>" autocomplete="off" <?= ($value['open_24_hours'] != 1 && $value['is_holiday'] != 1)?'required':'' ?> <?= ($value['open_24_hours'] == 1 || $value['is_holiday'] == 1)?'disabled':'' ?> style="width: 100px;">
                     </div>
                  </td>

                  <td class="td right">
                      <div class="checker display: flex;">
                          <span>
                              <input type="checkbox" class="second_time" id="second_time_<?= $i ?>" value="<?= $i ?>" <?= $value['open_time2'] != ""?'checked':'' ?> <?= ($value['open_24_hours'] == 1 || $value['is_holiday'] == 1)?'disabled':'' ?>>
                              <input type="hidden" name="second_time[<?= $i ?>]" id="second_time_value_<?= $i ?>" value="0"/>
                          </span>
                          <input type="text" class="timepicker picker_<?= $i ?> picker2_<?= $i ?>" name="open_time_2[<?= $i ?>]" id="open_time_2_<?= $i ?>" maxlength="5" value="<?= $value['open_time2'] ?>" autocomplete="off" <?= $value['open_time2'] == ""?'disabled':'' ?> style="width: 100px;">
                      <input type="text" class="timepicker picker_<?= $i ?> picker2_<?= $i ?>" name="close_time_2[<?= $i ?>]" id="close_time_2_<?= $i ?>" maxlength="5" value="<?= $value['close_time2'] ?>" autocomplete="off" <?= $value['open_time2'] == ""?'disabled':'' ?> style="width: 100px;">
                      </div>
                  </td>
               </tr>
         </table>
      <?php } ?>
            </div>
         </div>
      </div>
      <button type="submit" class="btn btn-info pull-right" style="margin-right: 15px;"><?php echo _l('submit'); ?></button>
      <?php echo form_close(); ?>
   <?php } else {?> 
      <?php echo form_open($this->uri->uri_string(),array('class'=>'client-form','autocomplete'=>'off')); ?>
         <input type="hidden" name="form_type" value="opening_hours">
         <div class="additional"></div>
         <div class="col-md-12">
            <div class="form-group">
                <div class="tableview">
                    <?php for($i=1;$i<=7;$i++){ ?> 
                    <table class="table">
                        <?php if($i == 1) {?>
                        <tr> 
                            <td class="td" style="width: 90px;">
                                <div class="hideondesktop"><strong>Day</strong></div>
                            </td>
                       
                            <td class="td" style="width: 80px;">
                                <div class="hideondesktop"><strong>Closed</strong></div>
                            </td>
                      
                            <td class="td" style="width: 130px;">
                                <div class="hideondesktop"><strong>Open 24 Hours</strong></div>
                            </td>
                        
                            <td class="td">
                                <div class="hideondesktop"><strong>Opening Time 1</strong></div>
                            </td>
                        
                            <td class="td right">
                              <div class="hideondesktop"><strong>Opening Time 2</strong></div>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr class=" p-0"> 
                            <td class="td" style="width: 90px;">
                                <?= getWeekDay($i); ?>
                            </td>
                       
                            <td class="td" style="width: 80px;">
                                <div class="checker">
                                    <span>
                                        <input type="checkbox" class="closed" id="checkboxClosed_<?= $i; ?>" value="<?= $i; ?>">
                                        <input type="hidden" name="closed[<?= $i ?>]" id="closed_<?= $i ?>" value="0">
                                    </span> Closed
                                </div> 
                            </td>
                      
                            <td class="td" style="width: 130px;">
                                <div class="checker">
                                    <span>
                                        <input type="checkbox" class="twentyfour_hours" id="checkbox24_<?= $i ?>" value="<?= $i ?>">
                                        <input type="hidden" name="twentyfour_hours[<?= $i ?>]" id="twentyfour_hours_<?= $i ?>" value="0">
                                    </span> Open 24 Hours
                                </div> 
                            </td>
                        
                            <td class="td">
                                <div style="display: flex;">
                                 <input type="text" class="timepicker picker_<?= $i ?> picker1_<?= $i ?>" name="open_time_1[<?= $i ?>]" id="open_time_1_<?= $i ?>" maxlength="5" autocomplete="off" required style="width: 100px; margin-right: 3px;">
                                 <input type="text" class="timepicker picker_<?= $i ?> picker1_<?= $i ?>" name="close_time_1[<?= $i ?>]" id="close_time_1_<?= $i ?>" maxlength="5" autocomplete="off" required style="width: 100px;">
                                </div>
                            </td>
                        
                            <td class="td right">
                                 <div class="checker display: flex;">
                                    <span>
                                        <input type="checkbox" class="second_time" id="second_time_<?= $i ?>" value="<?= $i ?>">
                                        <input type="hidden" name="second_time[<?= $i ?>]" id="second_time_value_<?= $i ?>" value="0"/>
                                    </span> 
                                    <input type="text" class="timepicker picker_<?= $i ?> picker2_<?= $i ?>" name="open_time_2[<?= $i ?>]" id="open_time_2_<?= $i ?>" maxlength="5" autocomplete="off" disabled="" style="width: 100px;">
                                    <input type="text" class="timepicker picker_<?= $i ?> picker2_<?= $i ?>" name="close_time_2[<?= $i ?>]" id="close_time_2_<?= $i ?>" maxlength="5" autocomplete="off" disabled="" style="width: 100px;">
                                </div>
                            </td>
                        </tr>
                    </table>
                    <?php }?>
                </div>
            </div>
         </div>
         <button type="submit" class="btn btn-info pull-right" style="margin-right: 15px;"><?php echo _l('submit'); ?></button>
         <?php echo form_close(); ?>
   <?php } ?>
   
</div>

<?php 
   function getWeekDay($day_number){
      $days_in_english = array("1"=>"Monday","2"=>"Tuesday","3"=>"Wednesday","4"=>"Thursday","5"=>"Friday","6"=>"Saturday","7"=>"Sunday");
      return $days_in_english[$day_number];
   }
?>