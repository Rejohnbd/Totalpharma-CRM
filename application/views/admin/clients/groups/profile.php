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
               <li role="presentation">
                  <a href="#customer_admins" aria-controls="customer_admins" role="tab" data-toggle="tab">
                  <?php echo _l( 'customer_admins' ); ?>
                  <?php if(count($customer_admins) > 0 ) { ?>
                     <span class="badge bg-default"><?php echo count($customer_admins) ?></span>
                  <?php } ?>
                  </a>
               </li>
               <?php hooks()->do_action('after_customer_admins_tab',$client); ?>
               <?php } ?>
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
         <!-- Add Side Information Tab  -->
         <div role="tabpanel" class="tab-pane" id="site_information">
            <div class="row">
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Title Site</label>
                           <input type="text" name="basfa[site][title]" class="form-control " />
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Colors</label>
                            <textarea name="basfa[site][colors]" class="form-control" rows="6"></textarea>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Logo Information</label>
                            <textarea name="basfa[site][logo_info]" class="form-control" rows="6"></textarea>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Home Page/Menu Structure/Slides</label>
                           <?php echo render_textarea('basfa[site][structure]','','',array(),array(),'','tinymce'); ?>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Picture Information</label>
                           <?php echo render_textarea('basfa[site][picture_info]','','',array(),array(),'','tinymce'); ?>
                       </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Other Pages/Information</label>
                           <?php echo render_textarea('basfa[site][other_info]','','',array(),array(),'','tinymce'); ?>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">SEO keywords</label>
                           <textarea name="basfa[site][seo_keywords]" class="form-control" rows="6"></textarea>
                        </div>
                        <div class="form-group">
                           <label class="control-label">Upload Zip File (Images, logos etc)</label>
                           <input type="file" name="basfa_files" class="form-control " />
                        </div>
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
                           <input type='text' class='form-control' name='basfa[server][domain_name]' />
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label"><input type='radio' name='basfa[server][domain_action]' value='transfer' /> Transfer</label>
                           <label class="control-label"><input type='radio' name='basfa[server][domain_action]' value='register' /> Register</label>
                           <label class="control-label"><input type='radio' name='basfa[server][domain_action]' value='transfercode' /> Transfercode</label>
                           <label class="control-label"> <input type='text' class="form-control input-sm" disabled style="display:inline-block; margin-left:10px;" name='basfa[server][transfercode]' /></label>
                        </div>
                     </div>

                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Brands to Exclude</label><br />
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Louis Widmer" /> Louis Widmer</label>
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Caudalie" /> Caudalie</label>
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Nuxe" /> Nuxe</label>
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Roger & Gallet" /> Roger & Gallet</label>

                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Filorga" /> Filorga</label>
                           <!--<label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Galenic" /> Galenic</label>-->
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="6D Sports" /> 6D Sports</label>
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Cent pur Cent" /> Cent pur Cent</label>

                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Apivita" /> Apivita</label>
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Furterer" /> Furterer</label>
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="Darphin" /> Darphin</label>
                           <label class="control-label"><input type='checkbox' name='basfa[server][brands_exclude][]' value="T Leclerc" /> T Leclerc</label>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Mails to install</label>
                           <textarea name="basfa[server][mails_to_install]" class="form-control" rows="6"></textarea>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Old email credentials</label>
                           <textarea name="basfa[server][old_email_credentials]" class="form-control" rows="6"></textarea>
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="control-label">Email system</label> <br />
                           <label><input name="basfa[server][mail_system][]" type="checkbox" value="Outlook"/> Outlook</label>
                           <label><input name="basfa[server][mail_system][]" type="checkbox" value="Windows Mail"/> Windows Mail</label>
                           <label><input name="basfa[server][mail_system][]" type="checkbox" value="Thunderbird"/> Thunderbird</label>
                           <label><input name="basfa[server][mail_system][]" type="checkbox" value="Gmail"/> Gmail</label>
                           <label><input name="basfa[server][mail_system][]" type="checkbox" value="Other"/> Other</label>
                       </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Teamviewer ID</label>
                           <input type='text' class='form-control' name='basfa[server][teamviewer_id]' />
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Mollie API Key (Test)</label>
                           <input type='text' class='form-control' name='basfa[server][mollie_api_test]' />
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Mollie API Key (Live)</label>
                           <input type='text' class='form-control' name='basfa[server][mollie_api_live]' />
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">bpost Account ID</label>
                           <input type='text' class='form-control' name='basfa[server][bpost_account_id]' />
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">bpost Account Password</label>
                           <input type='text' class='form-control' name='basfa[server][bpost_account_password]' />
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">bpost Secret Passphrase</label>
                           <input type='text' class='form-control' name='basfa[server][bpost_secret_passphrase]' />
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
                           <?php echo render_textarea('basfa[other][comment]','','',array(),array(),'','tinymce'); ?>
                        </div>
                        <div class="form-group  file_upload_div">
                           <label class="control-label">Upload Files</label>
                           <div class="input-group">
                              <input class="form-control" type="file" name="basfa[other][files][]" />
                              <span class="input-group-btn">
                                 <button id="add_file_uploader" style="width:40px;font-weight:bold;" class="btn btn-success">+</button>
                              </span>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="panel panel-default">
                           <div class="panel-heading">
                             Services
                           </div>
                           <div class="panel-body">
                              <div class="form-group">
                                 <label class="control-label"><strong>Website</strong></label><br />
                                 <label class="control-label"><input type='radio' name='basfa[site][package]' value="Proweb" /> Proweb</label>
                                 <label class="control-label"><input type='radio' name='basfa[site][package]' value="PharmaReserve" /> PharmaReserve</label>
                                 <label class="control-label"><input type='radio' name='basfa[site][package]' value="Online Master" /> Online Master</label>
                              </div>

                              <div class="form-group">
                                 <label class="control-label"><strong>Checkout</strong></label> <br />
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Payment system"/> Online payment</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Safes"/> Afhaalkluisjes (safes)</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Home Delivery"/> Thuislevering (home delivery)</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Robot"/> Buitenautomaat (vending machine)</label>
                              </div>

                              <div class="form-group">
                                 <label class="control-label"><strong>PharmaChannel</strong></label> <br />
                                 <label><input name="basfa[site][services][]" type="checkbox" value="PharmaBooking"/> PharmaBooking</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="PharmaChat"/> PharmaChat</label>
                                 <label><input name="basfa[site][services][]" type="checkbox" value="App"/> ZorgExpert App</label>
                              </div>
                              <div class="form-group">
                                 <label class="control-label"><strong>Others</strong></label> <br />
                                 <label><input name="basfa[site][services][]" type="checkbox" value="Logo design"/> Logo design</label>
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
                     for($i=0;$i<count($bcats);$i++){ 
                  ?>
                     <div class="panel panel-info">
                        <div class="panel-heading"><?php echo $bcats[$i]; ?></div>
                           <div class="panel-body">
                              <?php for($j=0;$j<count($brands[$i]);$j++){ ?>
                                 <label class="control-label"><input type='checkbox' name='basfa[brands_checklist][<?php echo $i; ?>][]' value="<?php echo $brands[$i][$j]; ?>" checked /> <?php echo $brands[$i][$j]; ?></label>
                              <?php } ?>
                              <div class="row">
                                 <?php for($x=0;$x<4;$x++){ ?>
                                    <div class="col-md-3">
                                       <input type="text" class="form-control" name="basfa[brands_checklist][<?php echo $i; ?>][custom][]" value="" />
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
