<?php
// * Swm_member_config
// * @Control member config
// * @input    id,age,total
// * @output   id,age,total
// * @author   Kanyarat Rodtong
// * @Update   Kanyarat Rodtong
// * @Update Date   2562-09-24
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/../../UMS_Controller.php");

class Swm_register_price_config extends  UMS_Controller
{

    public $user;
    function __construct()
    {
        parent::__construct();
        $this->user = $this->session->userdata();
    }
    /*
    * index
    * @show  register price config
    * @input    -
    * @output   -
    * @author   Kanyarat Rodtong
    * @Create Date  2562-09-25
    */
    function index()
    {
        $this->output('swm/backend/price_config/register_price_config/v_register_price_config');
    }
    /*
    * get_table_data_ajax
    * @return data table
    * @input    -
    * @output   all data
    * @author   Kanyarat Rodtong
    * @Update   Kanyarat Rodtong
    * @Update Date  2562-09-24
    * @Create Date  2562-09-25
    */
    function get_table_data_ajax()
    {
        $this->load->model('swm/backend/M_swm_cost_register', 'mcr');

        $data['rs_cost_register'] = $this->mcr->get_cost_register_detail()->result();

        $data['tmr_arr'] = array();

        foreach ($data['rs_cost_register'] as $row) {
            $row->update_date = abbreDate2($row->update_date);
            $data['tmr_arr'][$row->scr_reference][] = $row;
        }

        echo json_encode(array_reverse($data['tmr_arr']));
    }
    /*
    * get_register_cost_data_ajax
    * @register cost
    * @input    -
    * @output   all data
    * @author   Kanyarat Rodtong
    * @Update   Kanyarat Rodtong
    * @Update Date  2562-09-24
    * @Create Date  2562-09-25
    */
    function get_register_cost_data_ajax()
    {
        $this->load->model('swm/backend/M_swm_cost_register', 'mcr');

        $this->mcr->scr_reference = $this->input->post('scr_reference');
        $data['rs_cost_register'] = $this->mcr->get_cost_register_detail_by_scr_reference()->result();

        $data['tmr_arr'] = array();

        foreach ($data['rs_cost_register'] as $row) {
            $data['tmr_arr'][] = $row;
        }

        echo json_encode($data['tmr_arr']);
    }
    /*
    * register_price_config_change_ajax
    * @change config register price
    * @input    -
    * @output   new config register price
    * @author   Kanyarat Rodtong
    * @Update   Kanyarat Rodtong
    * @Update Date  2562-09-24
    * @Create Date  2562-09-25
    */
    function register_price_config_change_ajax()
    {
        $scr_reference = $this->input->post('scr_reference');

        $this->load->model('swm/backend/M_swm_cost_register', 'mcr');
        $mcr = $this->mcr;

        $mcr->scr_reference = $scr_reference;
        //update cost
        $mcr->update_cost_register();

        echo json_encode($scr_reference);
    }
    /*
    * register_price_config_change_active_ajax    
    * @update status of register price
    * @input    -
    * @output   new status register price
    * @author   Kanyarat Rodtong
    * @Update   Kanyarat Rodtong
    * @Update Date  2562-09-24
    * @Create Date  2562-09-25
    */
    function register_price_config_change_active_ajax()
    {
        $this->load->model('swm/backend/M_swm_cost_register', 'mcr');
        //update cost and change status
        $this->mcr->update_cost_register_active();

        echo json_encode('');
    }
    /*
    * register_price_config_insert_ajax
    * @insert register price and config price
    * @input    -
    * @output   new register price
    * @author   Kanyarat Rodtong
    * @Update   Kanyarat Rodtong
    * @Update Date  2562-09-24
    * @Create Date  2562-09-25
    */
    function register_price_config_insert_ajax()
    {
        $this->load->model('swm/backend/M_swm_cost_register', 'mcr');

        $scr_reference = $this->mcr->get_new_scr_reference()->row()->reference_id;

        // insert youth
        $this->mcr->scr_age_min = $this->input->post('min_age_youth');
        $this->mcr->scr_age_max = $this->input->post('max_age_youth');
        $this->mcr->scr_cost = $this->input->post('cost_youth');
        $this->mcr->scr_create_date = date('Y-m-d H:i:s');
        $this->mcr->scr_update_date = date('Y-m-d H:i:s');
        $this->mcr->scr_reference = $scr_reference;
        $this->mcr->scr_is_active = "N";
        $this->mcr->insert();

        // insert adult
        $this->mcr->scr_age_min = $this->input->post('min_age_adult');
        $this->mcr->scr_age_max = $this->input->post('max_age_adult');
        $this->mcr->scr_cost = $this->input->post('cost_adult');
        $this->mcr->scr_create_date = date('Y-m-d H:i:s');
        $this->mcr->scr_update_date = date('Y-m-d H:i:s');
        $this->mcr->scr_reference = $scr_reference;
        $this->mcr->scr_is_active = "N";
        $this->mcr->insert();

        echo json_encode($scr_reference);
    }
    /*
    * register_price_config_delete_ajax
    * @delete register price
    * @input    -
    * @output   -
    * @author   Kanyarat Rodtong
    * @Update   Kanyarat Rodtong
    * @Update Date  2562-09-24
    * @Create Date  2562-09-25
    */
    function register_price_config_delete_ajax()
    {
        $this->load->model('swm/backend/M_swm_cost_register', 'mcr');

        $this->mcr->scr_reference = $this->input->post('scr_reference');
        //delete price 
        $this->mcr->remove_price_config();

        echo json_encode($this->mcr->scr_reference);
    }
    /*
    * register_price_config_update_ajax
    * @update register price
    * @input    -
    * @output   new config update register price
    * @author   Kanyarat Rodtong
    * @Update   Kanyarat Rodtong
    * @Update Date  2562-09-24
    * @Create Date  2562-09-25
    */
    function register_price_config_update_ajax()
    {
        $this->load->model('swm/backend/M_swm_cost_register', 'mcr');

        // update youth
        $this->mcr->scr_id = $this->mcr->get_id_by_reference($this->input->post('reference'))->result()[0]->scr_id;
        $this->mcr->scr_age_min = $this->input->post('min_age_youth');
        $this->mcr->scr_age_max = $this->input->post('max_age_youth');
        $this->mcr->scr_cost = $this->input->post('cost_youth');
        $this->mcr->scr_update_date = date('Y-m-d H:i:s');

        $this->mcr->update();

        // update adult
        $this->mcr->scr_id = $this->mcr->get_id_by_reference($this->input->post('reference'))->result()[1]->scr_id;
        $this->mcr->scr_age_min = $this->input->post('min_age_adult');
        $this->mcr->scr_age_max = $this->input->post('max_age_adult');
        $this->mcr->scr_cost = $this->input->post('cost_adult');
        $this->mcr->scr_update_date = date('Y-m-d H:i:s');

        $this->mcr->update();

        echo json_encode($this->mcr->get_id_by_reference($this->input->post('reference'))->result());
    }
}
