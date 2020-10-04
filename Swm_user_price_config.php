<?php
// * Swm_user_price_config
// * @Update   Weerapong Sooksangacharoen
// * @Create Date  2562-08-17
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/../../UMS_Controller.php");

class Swm_user_price_config extends  UMS_Controller
{

    public $user;
    function __construct()
    {
        parent::__construct();
        $this->user = $this->session->userdata();
        date_default_timezone_set("Asia/Bangkok");
    }
    /*
    * index
    * @input    -
    * @output   get_cost_pool_detail
    * @author   Kanyarat Rodtong
    * @Update   Weerapong Sooksangacharoen
    * @Create Date  2562-08-17
    * @Update Date  2562-08-27
    */
    function index()
    {

        $this->load->model('swm/backend/M_swm_cost_pool', 'mcp');
        $mcp = $this->mcp;
        $data['rs_cost_pool'] = $mcp->get_cost_pool_detail(2)->result();
        $data['rs_cost_pool_nonmember'] = $mcp->get_cost_pool_detail(1)->result();

        $data['tmp_arr'] = array();
        $data['tmp_arr_nonmember'] = array();

        foreach ($data['rs_cost_pool'] as $row) {
            $data['tmp_arr'][$row->scp_reference][] = $row;
        }
        foreach ($data['rs_cost_pool_nonmember'] as $row) {
            $data['tmp_arr_nonmember'][$row->scp_reference][] = $row;
        }

        $this->output('swm/backend/price_config/user_price_config/v_user_price_config', $data);
    }
    /*
    * user_price_config_change_ajax
    * @input    scp_reference
    * @output   scp_reference
    * @author   Kanyarat Rodtong
    * @Update   Weerapong Sooksangacharoen
    * @Create Date  2562-08-17
    * @Update Date  2562-08-27
    */

    function user_price_config_change_ajax()
    {
        $scp_reference = $this->input->post('scp_reference');

        $this->load->model('swm/backend/M_swm_cost_pool', 'mcp');
        $mcp = $this->mcp;

        $mcp->scp_reference = $scp_reference;

        $mcp->update_cost_pool();

        echo json_encode($scp_reference);
    }
    /*
    * user_price_config_change_active_ajax
    * @input    scp_sug_id
    * @output   scp_sug_id
    * @author   Kanyarat Rodtong
    * @Update   Weerapong Sooksangacharoen
    * @Create Date  2562-08-17
    * @Update Date  2562-08-27
    */
    function user_price_config_change_active_ajax()
    {
        $scp_sug_id = $this->input->post('scp_sug_id');

        $this->load->model('swm/backend/M_swm_cost_pool', 'mcp');
        $mcp = $this->mcp;

        $mcp->scp_sug_id = $scp_sug_id;

        $mcp->update_cost_pool_active();

        echo json_encode($scp_sug_id);
    }
    /*
    * user_price_config_insert_ajax
    * @input    user_group, min_age_youth, max_age_youth, cost_youth, Y-m-d H:i:s
    * @output   scp_reference
    * @author   Kanyarat Rodtong
    * @Update   Weerapong Sooksangacharoen
    * @Create Date  2562-08-17
    * @Update Date  2562-08-27
    */
    function user_price_config_insert_ajax()
    {
        $this->load->model('swm/backend/M_swm_cost_pool', 'mcp');

        $scp_reference = $this->mcp->get_new_scp_reference()->row()->reference_id;

        // insert youth
        $this->mcp->scp_sug_id = $this->input->post('user_group');
        $this->mcp->scp_age_min = $this->input->post('min_age_youth');
        $this->mcp->scp_age_max = $this->input->post('max_age_youth');
        $this->mcp->scp_cost = $this->input->post('cost_youth');
        $this->mcp->scp_create_date = date('Y-m-d H:i:s');
        $this->mcp->scp_update_date = date('Y-m-d H:i:s');
        $this->mcp->scp_reference = $scp_reference;

        $this->mcp->insert();

        // insert adult
        $this->mcp->scp_sug_id = $this->input->post('user_group');
        $this->mcp->scp_age_min = $this->input->post('min_age_adult');
        $this->mcp->scp_age_max = $this->input->post('max_age_adult');
        $this->mcp->scp_cost = $this->input->post('cost_adult');
        $this->mcp->scp_create_date = date('Y-m-d H:i:s');
        $this->mcp->scp_update_date = date('Y-m-d H:i:s');
        $this->mcp->scp_reference = $scp_reference;

        $this->mcp->insert();

        echo json_encode($scp_reference);
    }
    /*
    * user_price_config_delete_ajax
    * @input    scp_reference
    * @output   scp_reference
    * @author   Kanyarat Rodtong
    * @Update   Weerapong Sooksangacharoen
    * @Create Date  2562-08-17
    * @Update Date  2562-08-27
    */
    function user_price_config_delete_ajax()
    {
        $this->load->model('swm/backend/M_swm_cost_pool', 'mcp');

        $this->mcp->scp_reference = $this->input->post('scp_reference');

        $this->mcp->remove_price_config();

        echo json_encode($this->mcp->scp_reference);
    }
    /*
    * user_price_config_update_ajax
    * @input  min_age_youth, max_age_youth, cost_youth, Y-m-d H:i:s
    * @output id
    * @author   Kanyarat Rodtong
    * @Update   Weerapong Sooksangacharoen
    * @Create Date  2562-08-17
    * @Update Date  2562-08-27
    */  
    function user_price_config_update_ajax()
    {
        $this->load->model('swm/backend/M_swm_cost_pool', 'mcp');

        // update youth
        $this->mcp->scp_id = $this->mcp->get_id_by_reference($this->input->post('reference'))->result()[0]->scp_id;
        $this->mcp->scp_age_min = $this->input->post('min_age_youth');
        $this->mcp->scp_age_max = $this->input->post('max_age_youth');
        $this->mcp->scp_cost = $this->input->post('cost_youth');
        $this->mcp->scp_update_date = date('Y-m-d H:i:s');

        $this->mcp->update();

        // update adult
        $this->mcp->scp_id = $this->mcp->get_id_by_reference($this->input->post('reference'))->result()[1]->scp_id;
        $this->mcp->scp_age_min = $this->input->post('min_age_adult');
        $this->mcp->scp_age_max = $this->input->post('max_age_adult');
        $this->mcp->scp_cost = $this->input->post('cost_adult');
        $this->mcp->scp_update_date = date('Y-m-d H:i:s');

        $this->mcp->update();

        echo json_encode($this->mcp->get_id_by_reference($this->input->post('reference'))->result());
    }
}
