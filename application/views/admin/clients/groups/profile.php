<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="customer-profile-group-heading"><?php echo _l('client_add_edit_profile'); ?></h4>
<div class="row">
   <?php echo form_open($this->uri->uri_string(),array('class'=>'client-form','autocomplete'=>'off')); ?>
   <div class="additional"></div>
   <div class="col-md-12">
      <div class="horizontal-scrollable-tabs">
         <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
         <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
         <div class="horizontal-tabs">
            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
               <li role="presentation" class="<?php if(!$this->input->get('tab')){echo 'active';}; ?>">
                  <a href="#contact_info" aria-controls="contact_info" role="tab" data-toggle="tab">
                  <?php echo _l( 'customer_profile_details'); ?>
                  </a>
               </li>
               <?php
                  $customer_custom_fields = false;
                  if(total_rows(db_prefix().'customfields',array('fieldto'=>'customers','active'=>1)) > 0 ){
                       $customer_custom_fields = true;
                   ?>
               <li role="presentation" class="<?php if($this->input->get('tab') == 'custom_fields'){echo 'active';}; ?>">
                  <a href="#custom_fields" aria-controls="custom_fields" role="tab" data-toggle="tab">
                  <?php echo hooks()->apply_filters('customer_profile_tab_custom_fields_text', _l( 'custom_fields')); ?>
                  </a>
               </li>
               <?php } ?>
               <?php
               /*
               <li role="presentation">
                  <a href="#billing_and_shipping" aria-controls="billing_and_shipping" role="tab" data-toggle="tab">
                  <?php echo _l( 'billing_shipping'); ?>
                  </a>
               </li> 
               */ ?>
               <?php hooks()->do_action('after_customer_billing_and_shipping_tab', isset($client) ? $client : false); ?>
               <?php if(isset($client)){ ?>
               <?php 
               /*
               <li role="presentation">
                  <a href="#customer_admins" aria-controls="customer_admins" role="tab" data-toggle="tab">
                  <?php echo _l( 'customer_admins' ); ?>
                  <?php if(count($customer_admins) > 0 ) { ?>
                     <span class="badge bg-default"><?php echo count($customer_admins) ?></span>
                  <?php } ?>
                  </a>
               </li>
               <?php hooks()->do_action('after_customer_admins_tab',$client); ?>
               */ ?>
               <?php } ?>
               <li role="presentation">
                  <a href="#credentials" aria-controls="credentials" role="tab" data-toggle="tab">
                  <?php echo ('Credentials'); ?>
                  </a>
               </li>
               <li role="presentation">
                  <a href="#pharmacy_information" aria-controls="pharmacy_information" role="tab" data-toggle="tab">
                  <?php echo ('Pharmacy Information'); ?>
                  </a>
               </li>
               <li role="presentation">
                  <a href="#site_information" aria-controls="site_information" role="tab" data-toggle="tab">
                  <?php echo ('Site Information'); ?>
                  </a>
               </li> 
               <li role="presentation">
                  <a href="#server_settings" aria-controls="server_settings" role="tab" data-toggle="tab">
                  <?php echo ('Server Settings'); ?>
                  </a>
               </li>
               <li role="presentation">
                  <a href="#other_settings" aria-controls="other_settings" role="tab" data-toggle="tab">
                  <?php echo ('Other Settings'); ?>
                  </a>
               </li>
               <li role="presentation">
                  <a href="#brand_checklist" aria-controls="brand_checklist" role="tab" data-toggle="tab">
                  <?php echo ('Brand Checklist'); ?>
                  </a>
               </li>
            </ul>
         </div>
      </div>
      <div class="tab-content mtop15">
         <?php hooks()->do_action('after_custom_profile_tab_content',isset($client) ? $client : false); ?>
         <?php if($customer_custom_fields) { ?>
         <div role="tabpanel" class="tab-pane <?php if($this->input->get('tab') == 'custom_fields'){echo ' active';}; ?>" id="custom_fields">
            <?php $rel_id=( isset($client) ? $client->userid : false); ?>
            <?php echo render_custom_fields( 'customers',$rel_id); ?>
         </div>
         <?php } ?>
         <div role="tabpanel" class="tab-pane<?php if(!$this->input->get('tab')){echo ' active';}; ?>" id="contact_info">
            <div class="row">
               <div class="col-md-12 mtop15 <?php if(isset($client) && (!is_empty_customer_company($client->userid) && total_rows(db_prefix().'contacts',array('userid'=>$client->userid,'is_primary'=>1)) > 0)) { echo ''; } else {echo ' hide';} ?>" id="client-show-primary-contact-wrapper">
                  <div class="checkbox checkbox-info mbot20 no-mtop">
                     <input type="checkbox" name="show_primary_contact"<?php if(isset($client) && $client->show_primary_contact == 1){echo ' checked';}?> value="1" id="show_primary_contact">
                     <label for="show_primary_contact"><?php echo _l('show_primary_contact',_l('invoices').', '._l('estimates').', '._l('payments').', '._l('credit_notes')); ?></label>
                  </div>
               </div>
               <div class="col-md-6">
                  <?php $value=( isset($client) ? $client->company : ''); ?>
                  <?php $attrs = (isset($client) ? array() : array('autofocus'=>true)); ?>
                  <?php echo render_input( 'company', 'client_company',$value,'text',$attrs); ?>
                  <div id="company_exists_info" class="hide"></div>
                  <?php if(get_option('company_requires_vat_number_field') == 1){
                     $value=( isset($client) ? $client->vat : '');
                     echo render_input( 'vat', 'client_vat_number',$value);
                     } ?>
                  <?php $value=( isset($client) ? $client->phonenumber : ''); ?>
                  <?php echo render_input( 'phonenumber', 'client_phonenumber',$value); ?>
                  <?php if((isset($client) && empty($client->website)) || !isset($client)){
                     $value=( isset($client) ? $client->website : '');
                     echo render_input( 'website', 'client_website',$value);
                     } else { ?>
                  <div class="form-group">
                     <label for="website"><?php echo _l('client_website'); ?></label>
                     <div class="input-group">
                        <input type="text" name="website" id="website" value="<?php echo $client->website; ?>" class="form-control">
                        <div class="input-group-addon">
                           <span><a href="<?php echo maybe_add_http($client->website); ?>" target="_blank" tabindex="-1"><i class="fa fa-globe"></i></a></span>
                        </div>
                     </div>
                  </div>
                  <?php }
                     $selected = array();
                     if(isset($customer_groups)){
                       foreach($customer_groups as $group){
                          array_push($selected,$group['groupid']);
                       }
                     }
                     if(is_admin() || get_option('staff_members_create_inline_customer_groups') == '1'){
                      echo render_select_with_input_group('groups_in[]',$groups,array('id','name'),'customer_groups',$selected,'<a href="#" data-toggle="modal" data-target="#customer_group_modal"><i class="fa fa-plus"></i></a>',array('multiple'=>true,'data-actions-box'=>true),array(),'','',false);
                      } else {
                        echo render_select('groups_in[]',$groups,array('id','name'),'customer_groups',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false);
                      }
                     ?>
                  <?php if(!isset($client)){ ?>
                  <i class="fa fa-question-circle pull-left" data-toggle="tooltip" data-title="<?php echo _l('customer_currency_change_notice'); ?>"></i>
                  <?php }
                     $s_attrs = array('data-none-selected-text'=>_l('system_default_string'));
                     $selected = '';
                     if(isset($client) && client_have_transactions($client->userid)){
                        $s_attrs['disabled'] = true;
                     }
                     foreach($currencies as $currency){
                        if(isset($client)){
                          if($currency['id'] == $client->default_currency){
                            $selected = $currency['id'];
                         }
                      }
                     }
                            // Do not remove the currency field from the customer profile!
                     echo render_select('default_currency',$currencies,array('id','name','symbol'),'invoice_add_edit_currency',$selected,$s_attrs); ?>
                  <?php if(!is_language_disabled()){ ?>
                  <div class="form-group select-placeholder">
                     <label for="default_language" class="control-label"><?php echo _l('localization_default_language'); ?>
                     </label>
                     <select name="default_language" id="default_language" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                        <option value=""><?php echo _l('system_default_string'); ?></option>
                        <?php foreach($this->app->get_available_languages() as $availableLanguage){
                           $selected = '';
                           if(isset($client)){
                              if($client->default_language == $availableLanguage){
                                 $selected = 'selected';
                              }
                           }
                           ?>
                        <option value="<?php echo $availableLanguage; ?>" <?php echo $selected; ?>><?php echo ucfirst($availableLanguage); ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <?php } ?>
               </div>
               <div class="col-md-6">
                  <?php $value=( isset($client) ? $client->address : ''); ?>
                  <?php echo render_textarea( 'address', 'client_address',$value); ?>
                  <?php $value=( isset($client) ? $client->city : ''); ?>
                  <?php echo render_input( 'city', 'client_city',$value); ?>
                  <?php $value=( isset($client) ? $client->state : ''); ?>
                  <?php echo render_input( 'state', 'client_state',$value); ?>
                  <?php $value=( isset($client) ? $client->zip : ''); ?>
                  <?php echo render_input( 'zip', 'client_postal_code',$value); ?>
                  <?php $countries= get_all_countries();
                     $customer_default_country = get_option('customer_default_country');
                     $selected =( isset($client) ? $client->country : $customer_default_country);
                     echo render_select( 'country',$countries,array( 'country_id',array( 'short_name')), 'clients_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                     ?>
               </div>
            </div>
         </div>
         <?php if(isset($client)){ ?>
         <?php
         /*
         <div role="tabpanel" class="tab-pane" id="customer_admins">
            <?php if (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit')) { ?>
            <a href="#" data-toggle="modal" data-target="#customer_admins_assign" class="btn btn-info mbot30"><?php echo _l('assign_admin'); ?></a>
            <?php } ?>
            <table class="table dt-table">
               <thead>
                  <tr>
                     <th><?php echo _l('staff_member'); ?></th>
                     <th><?php echo _l('customer_admin_date_assigned'); ?></th>
                     <?php if(has_permission('customers','','create') || has_permission('customers','','edit')){ ?>
                     <th><?php echo _l('options'); ?></th>
                     <?php } ?>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach($customer_admins as $c_admin){ ?>
                  <tr>
                     <td><a href="<?php echo admin_url('profile/'.$c_admin['staff_id']); ?>">
                        <?php echo staff_profile_image($c_admin['staff_id'], array(
                           'staff-profile-image-small',
                           'mright5'
                           ));
                           echo get_staff_full_name($c_admin['staff_id']); ?></a>
                     </td>
                     <td data-order="<?php echo $c_admin['date_assigned']; ?>"><?php echo _dt($c_admin['date_assigned']); ?></td>
                     <?php if(has_permission('customers','','create') || has_permission('customers','','edit')){ ?>
                     <td>
                        <a href="<?php echo admin_url('clients/delete_customer_admin/'.$client->userid.'/'.$c_admin['staff_id']); ?>" class="btn btn-danger _delete btn-icon"><i class="fa fa-remove"></i></a>
                     </td>
                     <?php } ?>
                  </tr>
                  <?php } ?>
               </tbody>
            </table>
         </div>
         */ ?>
         <?php } ?>
         <?php
         /*
         <div role="tabpanel" class="tab-pane" id="billing_and_shipping">
            <div class="row">
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-md-6">
                        <h4 class="no-mtop"><?php echo _l('billing_address'); ?> <a href="#" class="pull-right billing-same-as-customer"><small class="font-medium-xs"><?php echo _l('customer_billing_same_as_profile'); ?></small></a></h4>
                        <hr />
                        <?php $value=( isset($client) ? $client->billing_street : ''); ?>
                        <?php echo render_textarea( 'billing_street', 'billing_street',$value); ?>
                        <?php $value=( isset($client) ? $client->billing_city : ''); ?>
                        <?php echo render_input( 'billing_city', 'billing_city',$value); ?>
                        <?php $value=( isset($client) ? $client->billing_state : ''); ?>
                        <?php echo render_input( 'billing_state', 'billing_state',$value); ?>
                        <?php $value=( isset($client) ? $client->billing_zip : ''); ?>
                        <?php echo render_input( 'billing_zip', 'billing_zip',$value); ?>
                        <?php $selected=( isset($client) ? $client->billing_country : '' ); ?>
                        <?php echo render_select( 'billing_country',$countries,array( 'country_id',array( 'short_name')), 'billing_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                     </div>
                     <div class="col-md-6">
                        <h4 class="no-mtop">
                           <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('customer_shipping_address_notice'); ?>"></i>
                           <?php echo _l('shipping_address'); ?> <a href="#" class="pull-right customer-copy-billing-address"><small class="font-medium-xs"><?php echo _l('customer_billing_copy'); ?></small></a>
                        </h4>
                        <hr />
                        <?php $value=( isset($client) ? $client->shipping_street : ''); ?>
                        <?php echo render_textarea( 'shipping_street', 'shipping_street',$value); ?>
                        <?php $value=( isset($client) ? $client->shipping_city : ''); ?>
                        <?php echo render_input( 'shipping_city', 'shipping_city',$value); ?>
                        <?php $value=( isset($client) ? $client->shipping_state : ''); ?>
                        <?php echo render_input( 'shipping_state', 'shipping_state',$value); ?>
                        <?php $value=( isset($client) ? $client->shipping_zip : ''); ?>
                        <?php echo render_input( 'shipping_zip', 'shipping_zip',$value); ?>
                        <?php $selected=( isset($client) ? $client->shipping_country : '' ); ?>
                        <?php echo render_select( 'shipping_country',$countries,array( 'country_id',array( 'short_name')), 'shipping_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                     </div>
                     <?php if(isset($client) &&
                        (total_rows(db_prefix().'invoices',array('clientid'=>$client->userid)) > 0 || total_rows(db_prefix().'estimates',array('clientid'=>$client->userid)) > 0 || total_rows(db_prefix().'creditnotes',array('clientid'=>$client->userid)) > 0)){ ?>
                     <div class="col-md-12">
                        <div class="alert alert-warning">
                           <div class="checkbox checkbox-default">
                              <input type="checkbox" name="update_all_other_transactions" id="update_all_other_transactions">
                              <label for="update_all_other_transactions">
                              <?php echo _l('customer_update_address_info_on_invoices'); ?><br />
                              </label>
                           </div>
                           <b><?php echo _l('customer_update_address_info_on_invoices_help'); ?></b>
                           <div class="checkbox checkbox-default">
                              <input type="checkbox" name="update_credit_notes" id="update_credit_notes">
                              <label for="update_credit_notes">
                              <?php echo _l('customer_profile_update_credit_notes'); ?><br />
                              </label>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
         </div>
         */ ?>
         
         <?php 
          $basfa = json_decode($client->basfa,true);
         ?>
         <!-- Add Credentials Tab -->
         <div role="tabpanel" class="tab-pane" id="credentials">
            <div class="row">
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Internally Credentials</label>
                           <?php echo render_textarea('basfa[site][internal_credentials]','',isset($basfa['site']['internal_credentials']) ? $basfa['site']['internal_credentials'] : '',array(),array(),'','tinymce'); ?>
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Clients Credentials</label>
                           <?php echo render_textarea('basfa[site][clients_credentials]','',isset($basfa['site']['clients_credentials']) ? $basfa['site']['clients_credentials'] : '',array(),array(),'','tinymce'); ?>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- Add Pharmacy Information Tab -->
         <div role="tabpanel" class="tab-pane" id="pharmacy_information">
            <div class="row">
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Number of Pharmacies</label>
                           <select class='form-control' id="apotheek_no" name="basfa[apotheek_no]" required>
                              <option value="">Select</option>
                              <option value="1" <?php if(isset($basfa['apotheek_no'])){ if($basfa['apotheek_no'] == 1){ ?>selected<?php }} ?>>1</option>
                              <option value="2" <?php if(isset($basfa['apotheek_no'])){ if($basfa['apotheek_no'] == 2){ ?>selected<?php }} ?>>2</option>
                              <option value="3" <?php if(isset($basfa['apotheek_no'])){ if($basfa['apotheek_no'] == 3){ ?>selected<?php }} ?>>3</option>
                              <option value="4" <?php if(isset($basfa['apotheek_no'])){ if($basfa['apotheek_no'] == 4){ ?>selected<?php }} ?>>4</option>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <?php for($i=0;$i<4;$i++){ ?>
                        <div class="panel panel-info panel-apotheek hide" data-no='<?php echo $i+1; ?>'>
                           <div class="panel-heading">
                              Pharmacy-<?php echo $i+1; ?>
                           </div>
                           <div class="panel-body">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Name of Pharmacy</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][name]" value="<?php if(isset($basfa['pharmacy'][$i]['name'])){ echo $basfa['pharmacy'][$i]['name']; } ?>" class="form-control " />
                                 </div>
                                 
                                 <div class="form-group">
                                    <label class="control-label">Street</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][street]" value="<?php if(isset($basfa['pharmacy'][$i]['street'])){ echo $basfa['pharmacy'][$i]['street']; } ?>"   class="form-control " />
                                 </div>
                                 <div class="form-group">
                                    <label class="control-label">Number.</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][number]" value="<?php if(isset($basfa['pharmacy'][$i]['number'])){ echo $basfa['pharmacy'][$i]['number']; } ?>"  class="form-control " />
                                 </div>
                                 <div class="form-group">
                                    <label class="control-label">Postcode</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][postcode]"  value="<?php if(isset($basfa['pharmacy'][$i]['postcode'])){ echo $basfa['pharmacy'][$i]['postcode']; } ?>"  class="form-control " />
                                 </div>
                                 <div class="form-group">
                                    <label class="control-label">Location</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][location]" value="<?php if(isset($basfa['pharmacy'][$i]['location'])){ echo $basfa['pharmacy'][$i]['location']; } ?>"   class="form-control " />
                                 </div>

                                 <div class="form-group">
                                    <label class="control-label">APB-nr</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][apb]" value="<?php if(isset($basfa['pharmacy'][$i]['apb'])){ echo $basfa['pharmacy'][$i]['apb']; } ?>"   class="form-control " />
                                 </div>

                                 <div class="form-group">
                                    <label class="control-label">BTW-nr</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][btw]" value="<?php if(isset($basfa['pharmacy'][$i]['btw'])){  echo $basfa['pharmacy'][$i]['btw'];} ?>"   class="form-control " />
                                 </div>

                                 <div class="form-group">
                                    <label class="control-label">Owner Pharmacy</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][owner]" value="<?php if(isset($basfa['pharmacy'][$i]['owner'])){  echo $basfa['pharmacy'][$i]['owner']; } ?>" class="form-control " />
                                 </div>

                              </div>
                              
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Contact Person</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][contact_person]" value="<?php if(isset($basfa['pharmacy'][$i]['contact_person'])){  echo $basfa['pharmacy'][$i]['contact_person']; } ?>"   class="form-control" />
                                 </div>

                                 <div class="form-group">
                                    <label class="control-label">Phone</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][phone]" value="<?php if(isset($basfa['pharmacy'][$i]['phone'])){ echo $basfa['pharmacy'][$i]['phone']; } ?>"   class="form-control " />
                                 </div>
                                 <div class="form-group">
                                    <label class="control-label">Mobile</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][mobile]" value="<?php if(isset($basfa['pharmacy'][$i]['mobile'])){ echo $basfa['pharmacy'][$i]['mobile']; } ?>" class="form-control " />
                                 </div>
                                 <div class="form-group">
                                    <label class="control-label">Email 1</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][email][]" value="<?php if(isset($basfa['pharmacy'][$i]['email'][0])){ echo $basfa['pharmacy'][$i]['email'][0]; } ?>"   class="form-control " />
                                 </div>
                                 <div class="form-group">
                                    <label class="control-label">Email 2</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][email][]" value="<?php if(isset($basfa['pharmacy'][$i]['email'][1])){ echo $basfa['pharmacy'][$i]['email'][1]; } ?>"  class="form-control " />
                                 </div>
                                 <div class="form-group">
                                    <label class="control-label">Website</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][website]" value="<?php if(isset($basfa['pharmacy'][$i]['website'])){ echo $basfa['pharmacy'][$i]['website']; } ?>"  class="form-control " />
                                 </div>
                                 <div class="form-group">
                                    <label class="control-label">Facebook</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][facebook]"  value="<?php if(isset($basfa['pharmacy'][$i]['facebook'])){  echo $basfa['pharmacy'][$i]['facebook']; } ?>" class="form-control " />
                                 </div>
                                 <div class="form-group">
                                    <label class="control-label">Instagram</label>
                                    <input type="text" name="basfa[pharmacy][<?php echo $i; ?>][instagram]"  value="<?php if(isset($basfa['pharmacy'][$i]['instagram'])){ echo $basfa['pharmacy'][$i]['instagram']; } ?>" class="form-control " />
                                 </div>
                              </div>

                              <div class="col-md-12">
                                 <h4>Openingsuren</h4>
                                 <table class='table table bordered'>
                                   <tr>
                                     <td>Day</td>
                                     <td>Closed</td>
                                     <td>24 Hours Open</td>
                                     <td>Opening Time 1</td>
                                     <td>Opening Time 2</td>
                                   </tr>
                                   <tr>
                                     <td>Maandag</td>
                                     <td>
                                       <input type='checkbox' class='opening_hours_closed' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][mon][closed]" value='1' <?php if(isset($basfa['pharmacy'][$i]['opening_hours']['mon']['closed']) && $basfa['pharmacy'][$i]['opening_hours']['mon']['closed']){ ?>checked<?php } ?> /> Closed
                                     </td>
                                     <td>
                                       <input type='checkbox' class='twentyfour_hours' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][mon][24hrs]" <?php if(isset($basfa['pharmacy'][$i]['opening_hours']['mon']['24hrs']) && $basfa['pharmacy'][$i]['opening_hours']['mon']['24hrs']){ ?>checked<?php } ?> value='1' /> 24 Hours Open
                                     </td>
                                     <td>
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][mon][0][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['mon'][0][0]))?$basfa['pharmacy'][$i]['opening_hours']['mon'][0][0]:'';?>" />
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][mon][0][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['mon'][0][1]))?$basfa['pharmacy'][$i]['opening_hours']['mon'][0][1]:'';?>" />
                                     </td>
                                     <td>
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][mon][1][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['mon'][1][0]))?$basfa['pharmacy'][$i]['opening_hours']['mon'][1][0]:'';?>" />
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][mon][1][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['mon'][1][1]))?$basfa['pharmacy'][$i]['opening_hours']['mon'][1][1]:'';?>" />
                                     </td>
                                   </tr>
                                   <tr>
                                     <td>Dinsdag</td>
                                     <td>
                                       <input type='checkbox' class='opening_hours_closed' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][tue][closed]" value='1' <?php if(isset($basfa['pharmacy'][$i]['opening_hours']['tue']['closed']) && $basfa['pharmacy'][$i]['opening_hours']['tue']['closed']){ ?>checked<?php } ?> /> Closed
                                     </td>
                                     <td>
                                       <input type='checkbox' class='twentyfour_hours' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][tue][24hrs]" <?php if(isset($basfa['pharmacy'][$i]['opening_hours']['tue']['24hrs']) && $basfa['pharmacy'][$i]['opening_hours']['tue']['24hrs']){ ?>checked<?php } ?> value='1' /> 24 Hours Open
                                     </td>
                                     <td>
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][tue][0][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['tue'][0][0]))?$basfa['pharmacy'][$i]['opening_hours']['tue'][0][0]:'';?>" />
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][tue][0][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['tue'][0][1]))?$basfa['pharmacy'][$i]['opening_hours']['tue'][0][1]:'';?>" />
                                     </td>
                                     <td>
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][tue][1][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['tue'][1][0]))?$basfa['pharmacy'][$i]['opening_hours']['tue'][1][0]:'';?>" />
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][tue][1][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['tue'][1][1]))?$basfa['pharmacy'][$i]['opening_hours']['tue'][1][1]:'';?>" />
                                     </td>
                                   </tr>
                                   <tr>
                                     <td>Woensdag</td>
                                     <td>
                                       <input type='checkbox' class='opening_hours_closed' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][wed][closed]" value='1' <?php if(isset($basfa['pharmacy'][$i]['opening_hours']['wed']['closed']) && $basfa['pharmacy'][$i]['opening_hours']['wed']['closed']){ ?>checked<?php } ?> /> Closed
                                     </td>
                                     <td>
                                       <input type='checkbox' class='twentyfour_hours' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][wed][24hrs]" value='1' <?php if(isset($basfa['pharmacy'][$i]['opening_hours']['wed']['24hrs']) && $basfa['pharmacy'][$i]['opening_hours']['wed']['24hrs']){ ?>checked<?php } ?> /> 24 Hours Open
                                     </td>
                                     <td>
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][wed][0][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['wed'][0][0]))?$basfa['pharmacy'][$i]['opening_hours']['wed'][0][0]:'';?>" />
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][wed][0][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['wed'][0][1]))?$basfa['pharmacy'][$i]['opening_hours']['wed'][0][1]:'';?>" />
                                     </td>
                                     <td>
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][wed][1][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['wed'][1][0]))?$basfa['pharmacy'][$i]['opening_hours']['wed'][1][0]:'';?>" />
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][wed][1][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['wed'][1][1]))?$basfa['pharmacy'][$i]['opening_hours']['wed'][1][1]:'';?>" />
                                     </td>
                                   </tr>
                                   <tr>
                                     <td>Donderdag</td>
                                     <td>
                                       <input type='checkbox' class='opening_hours_closed' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][thu][closed]" value='1' <?php if(isset($basfa['pharmacy'][$i]['opening_hours']['thu']['closed']) && $basfa['pharmacy'][$i]['opening_hours']['thu']['closed']){ ?>checked<?php } ?> /> Closed
                                     </td>
                                     <td>
                                       <input type='checkbox' class='twentyfour_hours' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][thu][24hrs]" value='1' <?php if(isset($basfa['pharmacy'][$i]['opening_hours']['thu']['24hrs']) && $basfa['pharmacy'][$i]['opening_hours']['thu']['24hrs']){ ?>checked<?php } ?> /> 24 Hours Open
                                     </td>
                                     <td>
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][thu][0][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['thu'][0][0]))?$basfa['pharmacy'][$i]['opening_hours']['thu'][0][0]:'';?>" />
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][thu][0][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['thu'][0][1]))?$basfa['pharmacy'][$i]['opening_hours']['thu'][0][1]:'';?>" />
                                     </td>
                                     <td>
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][thu][1][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['thu'][1][0]))?$basfa['pharmacy'][$i]['opening_hours']['thu'][1][0]:'';?>" />
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][thu][1][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['thu'][1][1]))?$basfa['pharmacy'][$i]['opening_hours']['thu'][1][1]:'';?>" />
                                     </td>
                                   </tr>
                                   <tr>
                                     <td>Vrijdag</td>
                                     <td>
                                       <input type='checkbox' class='opening_hours_closed' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][fri][closed]" value='1' <?php if(isset($basfa['pharmacy'][$i]['opening_hours']['fri']['closed']) && $basfa['pharmacy'][$i]['opening_hours']['fri']['closed']){ ?>checked<?php } ?> /> Closed
                                     </td>
                                     <td>
                                       <input type='checkbox' class='twentyfour_hours' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][fri][24hrs]" value='1' <?php if(isset($basfa['pharmacy'][$i]['opening_hours']['fri']['24hrs']) && $basfa['pharmacy'][$i]['opening_hours']['fri']['24hrs']){ ?>checked<?php } ?> /> 24 Hours Open
                                     </td>
                                     <td>
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][fri][0][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['fri'][0][0]))?$basfa['pharmacy'][$i]['opening_hours']['fri'][0][0]:'';?>" />
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][fri][0][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['fri'][0][1]))?$basfa['pharmacy'][$i]['opening_hours']['fri'][0][1]:'';?>" />
                                     </td>
                                     <td>
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][fri][1][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['fri'][1][0]))?$basfa['pharmacy'][$i]['opening_hours']['fri'][1][0]:'';?>" />
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][fri][1][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['fri'][1][1]))?$basfa['pharmacy'][$i]['opening_hours']['fri'][1][1]:'';?>" />
                                     </td>
                                   </tr>
                                   <tr>
                                     <td>Zaterdag</td>
                                     <td>
                                       <input type='checkbox' class='opening_hours_closed' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][sat][closed]" value='1' <?php if(isset($basfa['pharmacy'][$i]['opening_hours']['sat']['closed']) && $basfa['pharmacy'][$i]['opening_hours']['sat']['closed']){ ?>checked<?php } ?> /> Closed
                                     </td>
                                     <td>
                                       <input type='checkbox' class='twentyfour_hours' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][sat][24hrs]" value='1' <?php if(isset($basfa['pharmacy'][$i]['opening_hours']['sat']['24hrs']) && $basfa['pharmacy'][$i]['opening_hours']['sat']['24hrs']){ ?>checked<?php } ?> /> 24 Hours Open
                                     </td>
                                     <td>
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][sat][0][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['sat'][0][0]))?$basfa['pharmacy'][$i]['opening_hours']['sat'][0][0]:'';?>" />
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][sat][0][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['sat'][0][1]))?$basfa['pharmacy'][$i]['opening_hours']['sat'][0][1]:'';?>" />
                                     </td>
                                     <td>
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][sat][1][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['sat'][1][0]))?$basfa['pharmacy'][$i]['opening_hours']['sat'][1][0]:'';?>" />
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][sat][1][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['sat'][1][1]))?$basfa['pharmacy'][$i]['opening_hours']['sat'][1][1]:'';?>" />
                                     </td>
                                   </tr>
                                   <tr>
                                     <td>Zondag</td>
                                     <td>
                                       <input type='checkbox' class='opening_hours_closed' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][sun][closed]" value='1' <?php if(isset($basfa['pharmacy'][$i]['opening_hours']['sun']['closed']) && $basfa['pharmacy'][$i]['opening_hours']['sun']['closed']){ ?>checked<?php } ?> /> Closed
                                     </td>
                                     <td>
                                       <input type='checkbox' class='twentyfour_hours' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][sun][24hrs]" value='1' <?php if(isset($basfa['pharmacy'][$i]['opening_hours']['sun']['24hrs']) && $basfa['pharmacy'][$i]['opening_hours']['sun']['24hrs']){ ?>checked<?php } ?> /> 24 Hours Open
                                     </td>
                                     <td>
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][sun][0][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['sun'][0][0]))?$basfa['pharmacy'][$i]['opening_hours']['sun'][0][0]:'';?>" />
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][sun][0][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['sun'][0][1]))?$basfa['pharmacy'][$i]['opening_hours']['sun'][0][1]:'';?>" />
                                     </td>
                                     <td>
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][sun][1][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['sun'][1][0]))?$basfa['pharmacy'][$i]['opening_hours']['sun'][1][0]:'';?>" />
                                       <input type='text' class='timepicker' name="basfa[pharmacy][<?php echo $i; ?>][opening_hours][sun][1][]" value="<?php echo (isset($basfa['pharmacy'][$i]['opening_hours']['sun'][1][1]))?$basfa['pharmacy'][$i]['opening_hours']['sun'][1][1]:'';?>" />
                                     </td>
                                   </tr>
                                 </table>
                              </div>

                              <div class="col-md-12"><h4>Team Page</h4></div>
                              <div class="form-group team_members<?php echo $i; ?>">
                                 <?php
                                 if(isset($basfa['pharmacy'][$i]['team_name'])){
                                    $team_count = count($basfa['pharmacy'][$i]['team_name']);
                                    if($team_count == 0){
                                      $team_count = 1;
                                    }
                                 }else{
                                    $team_count=1;
                                 }
                                 for($x=0;$x<$team_count;$x++){ ?>
                                 <div class="col-md-4 team_member" style="min-height:225px;">
                                    <div class="panel panel-info">
                                       <div class="panel-body">
                                          <label class="control-label">Name</label>
                                          <input class="form-control" name="basfa[pharmacy][<?php echo $i; ?>][team_name][]" value="<?php if(isset($basfa['pharmacy'][$i]['team_name'][$x])){ echo $basfa['pharmacy'][$i]['team_name'][$x]; }?>" />
                                          <label class="control-label">Title</label>
                                          <select name="basfa[pharmacy][<?php echo $i; ?>][team_title][]" class="form-control team_title">
                                             <option value="">---</option>
                                             <option value="Apotheker-Titularis" <?php if(isset($basfa['pharmacy'][$i]['team_title'][$x]) && $basfa['pharmacy'][$i]['team_title'][$x]=="Apotheker-Titularis"){ echo "selected";} ?>>Apotheker-Titularis</option>
                                             <option value="Adjunct-Apotheker" <?php if(isset($basfa['pharmacy'][$i]['team_title'][$x]) && $basfa['pharmacy'][$i]['team_title'][$x]=="Adjunct-Apotheker"){ echo "selected";} ?>>Adjunct-Apotheker</option>
                                             <option value="Assistent-Apotheker" <?php if(isset($basfa['pharmacy'][$i]['team_title'][$x]) && $basfa['pharmacy'][$i]['team_title'][$x]=="Assistent-Apotheker"){ echo "selected";} ?>>Assistent-Apotheker</option>
                                             <option value="Farmaceutisch Technisch Assistent" <?php if(isset($basfa['pharmacy'][$i]['team_title'][$x]) && $basfa['pharmacy'][$i]['team_title'][$x]=="Farmaceutisch Technisch Assistent"){ echo "selected";} ?>>Farmaceutisch Technisch Assistent</option>
                                             <option value="Stagiair" <?php if(isset($basfa['pharmacy'][$i]['team_title'][$x]) && $basfa['pharmacy'][$i]['team_title'][$x]=="Stagiair"){ echo "selected";} ?>>Stagiair</option>
                                             <option value="Apotheker" <?php if(isset($basfa['pharmacy'][$i]['team_title'][$x]) && $basfa['pharmacy'][$i]['team_title'][$x]=="Apotheker"){ echo "selected";} ?>>Apotheker</option>
                                             <option value="Other" <?php if(isset($basfa['pharmacy'][$i]['team_title'][$x]) && $basfa['pharmacy'][$i]['team_title'][$x]=="Other"){ echo "selected";} ?>>Other</option>
                                          </select>
                                          <input  name="basfa[pharmacy][<?php echo $i; ?>][team_title_other][]" placeholder="Write Title..." value="<?php if(isset($basfa['pharmacy'][$i]['team_title_other'][$x])){ echo $basfa['pharmacy'][$i]['team_title_other'][$x]; }?>" class="team_title_other form-control <?php if(isset($basfa['pharmacy'][$i]['team_title'][$x]) && $basfa['pharmacy'][$i]['team_title'][$x]=="Other"){?>visible<?php }else{ ?>hidden<?php } ?>" />
                                       </div>
                                    </div>
                                 </div>
                                 <?php } ?>
                              </div>
                              <a class='btn btn-info' id="add_team_member<?php echo $i; ?>" href='#'>+ Add More Member</a>
                              
                           </div>
                        </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
         </div>
         <!-- Add Side Information Tab  -->
         <div role="tabpanel" class="tab-pane" id="site_information">
            <div class="row">
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Title Site</label>
                           <input type="text" name="basfa[site][title]" value="<?php if(isset($basfa['site']['title'])){ echo $basfa['site']['title'];} ?>" class="form-control" />
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Colors</label>
                            <textarea name="basfa[site][colors]" class="form-control" rows="6"><?php if(isset($basfa['site']['colors'])){ echo $basfa['site']['colors']; }?></textarea>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Logo Information</label>
                            <textarea name="basfa[site][logo_info]" class="form-control" rows="6"><?php if(isset($basfa['site']['logo_info'])){ echo $basfa['site']['logo_info'];} ?></textarea>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Home Page/Menu Structure/Slides</label>
                           <?php echo render_textarea('basfa[site][structure]','',isset($basfa['site']['structure']) ? $basfa['site']['structure'] : '',array(),array(),'','tinymce'); ?>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Picture Information</label>
                           <?php echo render_textarea('basfa[site][picture_info]','',isset($basfa['site']['picture_info']) ? $basfa['site']['picture_info'] : '',array(),array(),'','tinymce'); ?>
                       </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Other Pages/Information</label>
                           <?php echo render_textarea('basfa[site][other_info]','',isset($basfa['site']['other_info']) ? $basfa['site']['other_info'] : '',array(),array(),'','tinymce'); ?>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">SEO keywords</label>
                           <textarea name="basfa[site][seo_keywords]" class="form-control" rows="6"><?php if(isset($basfa['site']['seo_keywords'])){ echo $basfa['site']['seo_keywords'];} ?></textarea>
                        </div>
                        <!-- <div class="form-group">
                           <label class="control-label">Upload Zip File (Images, logos etc)</label>
                           <input type="file" name="basfa_files" class="form-control " />
                        </div> -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- Add erver Settings Tab  -->
         <div role="tabpanel" class="tab-pane" id="server_settings">
            <div class="row">
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Domain Name(s)</label>
                           <input type='text' class='form-control' name='basfa[server][domain_name]' value='<?php if(isset($basfa['server']['domain_name'])){ echo $basfa['server']['domain_name'];} ?>' />
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label"><input type='radio' name='basfa[server][domain_action]' value='transfer' <?php if(isset($basfa['server']['domain_action'])){ if($basfa['server']['domain_action'] == "transfer"){ echo "checked"; }} ?> /> Transfer</label>
                           <label class="control-label"><input type='radio' name='basfa[server][domain_action]' value='register' <?php if(isset($basfa['server']['domain_action'])){ if($basfa['server']['domain_action'] == "register"){ echo "checked"; }} ?> /> Register</label>
                           <label class="control-label"><input type='radio' name='basfa[server][domain_action]' value='transfercode' <?php if(isset($basfa['server']['domain_action'])){ if($basfa['server']['domain_action'] == "transfercode"){ echo "checked"; }} ?> /> Transfercode</label>
                           <label class="control-label"> <input type='text' class="form-control input-sm" disabled style="display:inline-block; margin-left:10px;" name='basfa[server][transfercode]' value="<?php if(isset($basfa['server']['domain_action'])){ if(isset($basfa['server']['transfercode'])) {echo $basfa['server']['transfercode'];}} ?>" /></label>
                        </div>
                     </div>

                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Brands to Exclude</label><br />
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Louis Widmer" <?php if(isset($basfa['server']['brands_exclude'])){ if(in_array("Louis Widmer",$basfa['server']['brands_exclude'])){ ?>checked<?php }} ?> /> Louis Widmer</label>
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Caudalie" <?php if(isset($basfa['server']['brands_exclude'])){ if(in_array("Caudalie",$basfa['server']['brands_exclude'])){ ?>checked<?php }} ?> /> Caudalie</label>
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Nuxe" <?php if(isset($basfa['server']['brands_exclude'])){ if(in_array("Nuxe",$basfa['server']['brands_exclude'])){ ?>checked<?php }} ?> /> Nuxe</label>
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Roger & Gallet" <?php if(isset($basfa['server']['brands_exclude'])){ if(in_array("Roger & Gallet",$basfa['server']['brands_exclude'])){ ?>checked<?php }} ?> /> Roger & Gallet</label>

                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Filorga" <?php if(isset($basfa['server']['brands_exclude'])){ if(in_array("Filorga",$basfa['server']['brands_exclude'])){ ?>checked<?php }} ?> /> Filorga</label>
                           <!--<label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Galenic" /> Galenic</label>-->
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="6D Sports" <?php if(isset($basfa['server']['brands_exclude'])){ if(in_array("6D Sports",$basfa['server']['brands_exclude'])){ ?>checked<?php }} ?> /> 6D Sports</label>
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Cent pur Cent" <?php if(isset($basfa['server']['brands_exclude'])){ if(in_array("Cent pur Cent",$basfa['server']['brands_exclude'])){ ?>checked<?php }} ?> /> Cent pur Cent</label>

                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Apivita" <?php if(isset($basfa['server']['brands_exclude'])){ if(in_array("Apivita",$basfa['server']['brands_exclude'])){ ?>checked<?php }} ?> /> Apivita</label>
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Furterer" <?php if(isset($basfa['server']['brands_exclude'])){ if(in_array("Furterer",$basfa['server']['brands_exclude'])){ ?>checked<?php }} ?> /> Furterer</label>
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Darphin" <?php if(isset($basfa['server']['brands_exclude'])){ if(in_array("Darphin",$basfa['server']['brands_exclude'])){ ?>checked<?php }} ?> /> Darphin</label>
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="T Leclerc" <?php if(isset($basfa['server']['brands_exclude'])){ if(in_array("T Leclerc",$basfa['server']['brands_exclude'])){ ?>checked<?php }} ?> /> T Leclerc</label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Mails to install</label>
                           <textarea name="basfa[server][mails_to_install]" class="form-control" rows="6"><?php if(isset($basfa['server']['mails_to_install'])){ echo $basfa['server']['mails_to_install'];} ?></textarea>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Old email credentials</label>
                           <textarea name="basfa[server][old_email_credentials]" class="form-control" rows="6"><?php if(isset($basfa['server']['old_email_credentials'])){ echo $basfa['server']['old_email_credentials'];} ?></textarea>
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Email system</label> <br />
                           <label><input name="basfa[server][mail_system][]" type="checkbox" value="Outlook" <?php if(isset($basfa['server']['mail_system'])){ if(in_array("Outlook",$basfa['server']['mail_system'])){ echo "checked"; }} ?>/> Outlook</label>
                           <label><input name="basfa[server][mail_system][]" type="checkbox" value="Windows Mail" <?php if(isset($basfa['server']['mail_system'])){  if(in_array("Windows Mail",$basfa['server']['mail_system'])){ echo "checked"; }} ?> /> Windows Mail</label>
                           <label><input name="basfa[server][mail_system][]" type="checkbox" value="Thunderbird" <?php if(isset($basfa['server']['mail_system'])){  if(in_array("Thunderbird",$basfa['server']['mail_system'])){ echo "checked"; }} ?> /> Thunderbird</label>
                           <label><input name="basfa[server][mail_system][]" type="checkbox" value="Gmail" <?php if(isset($basfa['server']['mail_system'])){  if(in_array("Gmail",$basfa['server']['mail_system'])){ echo "checked"; }} ?> /> Gmail</label>
                           <label><input name="basfa[server][mail_system][]" type="checkbox" value="Other" <?php if(isset($basfa['server']['mail_system'])){  if(in_array("Other",$basfa['server']['mail_system'])){ echo "checked"; }} ?> /> Other</label>
                       </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Teamviewer ID</label>
                           <input type='text' class='form-control' name='basfa[server][teamviewer_id]' value='<?php if(isset($basfa['server']['teamviewer_id'])){ echo $basfa['server']['teamviewer_id'];} ?>' />
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Mollie API Key (Test)</label>
                           <input type='text' class='form-control' name='basfa[server][mollie_api_test]' value='<?php if(isset(['server']['mollie_api_test'])){ echo $basfa['server']['mollie_api_test']; }?>' />
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Mollie API Key (Live)</label>
                           <input type='text' class='form-control' name='basfa[server][mollie_api_live]' value='<?php if(isset($basfa['server']['mollie_api_live'])){ echo $basfa['server']['mollie_api_live'];} ?>' />
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">bpost Account ID</label>
                           <input type='text' class='form-control' name='basfa[server][bpost_account_id]' value='<?php if(isset($basfa['server']['bpost_account_id'])){ echo $basfa['server']['bpost_account_id'];} ?>' />
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">bpost Account Password</label>
                           <input type='text' class='form-control' name='basfa[server][bpost_account_password]' value='<?php if(isset($basfa['server']['bpost_account_password'])){ echo $basfa['server']['bpost_account_password'];} ?>' />
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">bpost Secret Passphrase</label>
                           <input type='text' class='form-control' name='basfa[server][bpost_secret_passphrase]' value='<?php if(isset($basfa['server']['bpost_secret_passphrase'])){ echo $basfa['server']['bpost_secret_passphrase'];} ?>' />
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- Add Other Settings Tab  -->
         <div role="tabpanel" class="tab-pane" id="other_settings">
            <div class="row">
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Other Comments</label>
                           <?php echo render_textarea('basfa[other][comment]','',isset($basfa['other']['comment']) ? $basfa['other']['comment'] : '',array(),array(),'','tinymce'); ?>
                        </div>
                        <!-- <div class="form-group  file_upload_div">
                           <label class="control-label">Upload Files</label>
                           <div class="input-group">
                              <input class="form-control" type="file" name="basfa[other][files][]" />
                              <span class="input-group-btn">
                                 <button id="add_file_uploader" style="width:40px;font-weight:bold;" class="btn btn-success">+</button>
                              </span>
                           </div>
                        </div> -->
                     </div>
                     <div class="col-md-6">
                        <div class="panel panel-default">
                           <div class="panel-heading">
                             Services
                           </div>
                           <div class="panel-body">
                              <div class="form-group">
                                 <label class="control-label"><strong>Website</strong></label><br />
                                 <label class="control-label"><input type='radio' name='basfa[site][package]' <?php if(isset($basfa['site']['package'])){ if($basfa['site']['package'] == "Proweb"){ ?>checked<?php }} ?> value="Proweb" /> Proweb</label>
                                 <label class="control-label"><input type='radio' name='basfa[site][package]' <?php if(isset($basfa['site']['package'])){ if($basfa['site']['package'] == "PharmaReserve"){ ?>checked<?php }} ?> value="PharmaReserve" /> PharmaReserve</label>
                                 <label class="control-label"><input type='radio' name='basfa[site][package]' <?php if(isset($basfa['site']['package'])){ if($basfa['site']['package'] == "Online Master"){ ?>checked<?php }} ?> value="Online Master" /> Online Master</label>
                              </div>

                              <div class="form-group">
                                 <label class="control-label"><strong>Checkout</strong></label> <br />
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Payment system"  <?php if(isset($basfa['site']['services'])){ if(in_array("Payment system",$basfa['site']['services'])){ echo "checked"; }} ?> /> Online payment</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Safes" <?php if(isset($basfa['site']['services'])){ if(in_array("Safes",$basfa['site']['services'])){ echo "checked"; }} ?>  /> Afhaalkluisjes (safes)</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Home Delivery" <?php if(isset($basfa['site']['services'])){ if(in_array("Home Delivery",$basfa['site']['services'])){ echo "checked"; }} ?> /> Thuislevering (home delivery)</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Robot" <?php if(isset($basfa['site']['services'])){ if(in_array("Robot",$basfa['site']['services'])){ echo "checked"; }} ?> /> Buitenautomaat (vending machine)</label>
                              </div>

                              <div class="form-group">
                                 <label class="control-label"><strong>PharmaChannel</strong></label> <br />
                                 <label><input name="basfa[site][services][]" type="checkbox" value="PharmaBooking" <?php if(isset($basfa['site']['services'])){ if(in_array("PharmaBooking",$basfa['site']['services'])){ echo "checked"; }} ?> /> PharmaBooking</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="PharmaChat" <?php if(isset($basfa['site']['services'])){ if(in_array("PharmaChat",$basfa['site']['services'])){ echo "checked"; }} ?> /> PharmaChat</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="App" <?php if(isset($basfa['site']['services'])){ if(in_array("App",$basfa['site']['services'])){ echo "checked"; }} ?> /> ZorgExpert App</label>
                              </div>
                              <div class="form-group">
                                 <label class="control-label"><strong>Others</strong></label> <br />
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Logo design" <?php if(isset($basfa['site']['services'])){ if(in_array("Logo design",$basfa['site']['services'])){ echo "checked"; }} ?> /> Logo design</label>
                              </div>
                              <div class="form-group">
                                 <label class="control-label"><strong>Software</strong></label> <br />
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Farmad" <?php if(isset($basfa['site']['services'])){ if(in_array("Farmad",$basfa['site']['services'])){ echo "checked"; }} ?> /> Farmad</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Corilus" <?php if(isset($basfa['site']['services'])){ if(in_array("Corilus",$basfa['site']['services'])){ echo "checked"; }} ?> /> Corilus</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Officinall" <?php if(isset($basfa['site']['services'])){ if(in_array("Officinall",$basfa['site']['services'])){ echo "checked"; }} ?> /> Officinall</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Nextpharm" <?php if(isset($basfa['site']['services'])){ if(in_array("Nextpharm",$basfa['site']['services'])){ echo "checked"; }} ?> /> Nextpharm</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Pharmagest" <?php if(isset($basfa['site']['services'])){ if(in_array("Pharmagest",$basfa['site']['services'])){ echo "checked"; }} ?> /> Pharmagest</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Pharmony" <?php if(isset($basfa['site']['services'])){ if(in_array("Pharmony",$basfa['site']['services'])){ echo "checked"; }} ?> /> Pharmony</label>
                              </div>
                              <div class="form-group">
                                 <label class="control-label"><strong>Wholesaler</strong></label> <br />
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Febelco" <?php if(isset($basfa['site']['services'])){ if(in_array("Febelco",$basfa['site']['services'])){ echo "checked"; }} ?> /> Febelco</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="PBB" <?php if(isset($basfa['site']['services'])){ if(in_array("PBB",$basfa['site']['services'])){ echo "checked"; }} ?> /> Pharma Belgium/Belmedis</label>
                              </div>
                              <div class="form-group">
                                 <label class="control-label"><strong>Other Partners</strong></label> <br />
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Orthoshop" <?php if(isset($basfa['site']['services'])){ if(in_array("Orthoshop",$basfa['site']['services'])){ echo "checked"; }} ?> /> Orthoshop</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Zorgpunt" <?php if(isset($basfa['site']['services'])){ if(in_array("Zorgpunt",$basfa['site']['services'])){ echo "checked"; }} ?> /> Zorgpunt</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="APB" <?php if(isset($basfa['site']['services'])){ if(in_array("APB",$basfa['site']['services'])){ echo "checked"; }} ?> /> APB</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Lochting" <?php if(isset($basfa['site']['services'])){ if(in_array("Lochting",$basfa['site']['services'])){ echo "checked"; }} ?> /> Lochting</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="DigitalPharma" <?php if(isset($basfa['site']['services'])){ if(in_array("DigitalPharma",$basfa['site']['services'])){ echo "checked"; }} ?> /> Digital Pharma</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="SalvusHealth" <?php if(isset($basfa['site']['services'])){ if(in_array("SalvusHealth",$basfa['site']['services'])){ echo "checked"; }} ?> /> Salvus Health</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="BDRowa" <?php if(isset($basfa['site']['services'])){ if(in_array("BDRowa",$basfa['site']['services'])){ echo "checked"; }} ?> /> BD/Rowa</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Meditech" <?php if(isset($basfa['site']['services'])){ if(in_array("Meditech",$basfa['site']['services'])){ echo "checked"; }} ?> /> Meditech</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="RedPharma" <?php if(isset($basfa['site']['services'])){ if(in_array("RedPharma",$basfa['site']['services'])){ echo "checked"; }} ?> /> Red Pharma</label>
                              </div>
                           </div>
                       </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- Add Brand Checklist Tab -->
         <div role="tabpanel" class="tab-pane" id="brand_checklist">
            <?php
               $brands = array(
                  array('COSMETICA / HUID / HAAR / LICHAAM', 'BABY EN MOEDER','VOEDINGSSUPPLEMENTEN','DIERGENEESMIDDELEN','FYTOTHERAPIE','HOMEOPATHIE'),
                  array(
                     array('Louis Widmer', 'Vichy', 'A Derma', 'Bioderma', 'Roc', 'Bodysol', 'NUXE', 'Eucerin', 'Uriage', 'Darphin', 'La Roche Posay', 'Apivita', 'La Roche Posay', 'Apivita', 'Caudalie', 'Avène', 'CentpurCent', 'René Furterer', 'T. Leclerc', 'Roger & Gallet'. 'Klorane', 'Lierac', 'Ducray', 'Filorga', 'Couleurs de Noir'),
                     array('Mustela',  'MAM', 'Avent', 'Difrax', 'Bibi', 'Nutrilon', 'NAN', 'Widmer Baby','Uriage Bébé','Galenco','BIBS' ),
                     array('IXX Pharma', 'Metagenics', 'Omnivit', 'Etixx', 'MorEPA (Minami)', 'XLS Medical', 'Nutrisan', 'Forté Pharma', '6D Sports' ),
                     array('Frontline', 'Royal Canin', 'Hills', 'Drontal', 'Bolfo', 'Advantix','Advantage'),
                     array('Puressentiel', 'Vitanza', 'Soria', 'Tilman', 'A. Vogel', 'Arkocaps', 'Phytosun', 'VSM'),
                     array('Unda', 'Boiron', 'Heel', 'Bach Bloesems')
                  )
               );
             ?>
            <div class="row">
               <div class="col-md-12">
                  <?php 
                     $bcats = $brands[0];
                     $brands = $brands[1];
                     $brands_checklist = isset($basfa['brands_checklist']) ? $basfa['brands_checklist'] : array();
                     for($i=0;$i<count($bcats);$i++){ 
                  ?>
                     <div class="panel panel-info">
                        <div class="panel-heading"><?php echo $bcats[$i]; ?></div>
                           <div class="panel-body">
                              <?php for($j=0;$j<count($brands[$i]);$j++){ ?>
                                 <label class="control-label"><input type='checkbox' name='basfa[brands_checklist][<?php echo $i; ?>][]' value="<?php echo $brands[$i][$j]; ?>" <?php if(isset($brands_checklist[$i])){if(in_array($brands[$i][$j],$brands_checklist[$i])){ ?>checked<?php }} ?> /> <?php echo $brands[$i][$j]; ?></label>
                              <?php } ?>
                              <div class="row">
                                 <?php for($x=0;$x<4;$x++){ ?>
                                    <div class="col-md-3">
                                       <input type="text" class="form-control" name="basfa[brands_checklist][<?php echo $i; ?>][custom][]" value="<?php echo isset($brands_checklist[$i]['custom'][$x])?$brands_checklist[$i]['custom'][$x]:'';?>" />
                                    </div>
                                 <?php } ?>
                              </div>
                           </div>
                     </div>
                  <?php } ?>
               </div>
            </div>
         </div>

      </div>
   </div>
   <?php echo form_close(); ?>
</div>
<?php if(isset($client)){ ?>
<?php if (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit')) { ?>
<div class="modal fade" id="customer_admins_assign" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <?php echo form_open(admin_url('clients/assign_admins/'.$client->userid)); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo _l('assign_admin'); ?></h4>
         </div>
         <div class="modal-body">
            <?php
               $selected = array();
               foreach($customer_admins as $c_admin){
                  array_push($selected,$c_admin['staff_id']);
               }
               echo render_select('customer_admins[]',$staff,array('staffid',array('firstname','lastname')),'',$selected,array('multiple'=>true),array(),'','',false); ?>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
         </div>
      </div>
      <!-- /.modal-content -->
      <?php echo form_close(); ?>
   </div>
   <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php } ?>
<?php } ?>
<?php $this->load->view('admin/clients/client_group'); ?>
