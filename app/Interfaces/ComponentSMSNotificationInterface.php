<?php

namespace App\Interfaces;

interface ComponentSMSNotificationInterface 
{    
    public function find($id);

    public function create(array $details);

    public function create_schedule(array $details);

    public function update($id, array $newDetails);   
    
    public function update_setting($id, array $newDetails);

    public function listItems($request);

    public function send($receipient, $message);

    public function send_later($receipient, $message);

    public function search_user($keywords);

    public function search_employee($keywords);

    public function search_taxpayer($keywords);

    public function search_citizen($keywords);

    public function validate();

    public function update_sms($id, array $newDetails);  

    public function tracking_listItems($request);

    public function get_sms_count_via_status($status);

    public function resend($id);

    public function send_now($job);

    public function settings_listItems($request);

    public function get_masks();

    public function update_settings(array $newDetails);

    public function find_setting($id);

    public function allMaskings();

    public function allGroupMenus();

    public function allSmsTypes();

    public function allSmsActions();

    public function store_setting(array $details);

    public function template_listItems($request);

    public function reload_module($group);

    public function reload_sub_module($group, $module);

    public function validate_template($application);

    public function find_template($id);

    public function create_template(array $details);

    public function update_template($id, array $newDetails); 

    public function fetch_setting();

    public function group_lists();

    public function type_lists();

    public function outbox_listItems($request);

    public function fetch_codex();
}