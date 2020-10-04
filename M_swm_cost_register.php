<?php
// * M_swm_cost_register
// * @Model Da_swm_cost_register
// * @author Weerapong Sooksangacharoen
// * @Create Date 2562-08-17
include_once(dirname(__FILE__) . "/../Da_swm_cost_register.php");

class M_swm_cost_register extends Da_swm_cost_register
{
	/*
	* get_all
	* @Extracts all data from the swm_user table, in the order in the order. 
	* @input    aOrderBy
	* @output   query
	* @author Weerapong Sooksangacharoen
	* @Create Date 2562-08-17
	*/
	function get_all($aOrderBy = "")
	{
		$orderBy = "";
		if (is_array($aOrderBy)) {
			$orderBy .= "ORDER BY ";
			foreach ($aOrderBy as $key => $value) {
				$orderBy .= "$key $value, ";
			}
			$orderBy = substr($orderBy, 0, strlen($orderBy) - 2);
		}
		$sql = "SELECT * 
				FROM swm_cost_register 
				$orderBy";
		$query = $this->swm->query($sql);
		return $query;
	}
	/*
	* get_options
	* @get all data of swm_user table 
	* @input    optional
	* @output   opt
	* @author Weerapong Sooksangacharoen
	* @Create Date 2562-08-17
	*/

	/*
	 * create array of pk field and value for generate select list in view, must edit PK_FIELD and FIELD_NAME manually
	 * the first line of select list is '-----เลือก-----' by default.
	 * if you do not need the first list of select list is '-----เลือก-----', please pass $optional parameter to other values. 
	 * you can delete this function if it not necessary.
	 */
	function get_options($optional = 'y')
	{
		$qry = $this->get_all();
		if ($optional == 'y') $opt[''] = '-----เลือก-----';
		foreach ($qry->result() as $row) {
			$opt[$row->PK_FIELD] = $row->FIELD_NAME;
		}
		return $opt;
	}
	/*
	* get_cost_register_detail
	* @cost register detail
	* @input    -
	* @output   scr_id, scr_age_min, scr_age_max, scr_cost, scr_reference, scr_is_active, scr_update_date
	* @author Weerapong Sooksangacharoen
	* @Create Date 2562-08-17
	*/
	function get_cost_register_detail()
	{
		$sql = "SELECT scr_id,scr_age_min,scr_age_max,scr_cost,scr_reference,scr_is_active,SUBSTR(scr_update_date,1 ,10) AS update_date
				FROM $this->swm_db.swm_cost_register
				WHERE scr_is_remove = 'N'";

		$query = $this->swm->query($sql);
		return $query;
	}
	/*
	* get_cost_register_detail_by_scr_reference
	* @cost register detail by scr_reference
	* @input    -
	* @output   scr_id, scr_age_min, scr_age_max, scr_cost, scr_reference, scr_is_active, scr_update_date
	* @author Weerapong Sooksangacharoen
	* @Create Date 2562-08-17
	*/
	function get_cost_register_detail_by_scr_reference()
	{
		$sql = "SELECT scr_id,scr_age_min,scr_age_max,scr_cost,scr_reference,scr_is_active,SUBSTR(scr_update_date,1 ,10) AS update_date
				FROM $this->swm_db.swm_cost_register
				WHERE scr_reference = ?";

		$query = $this->swm->query($sql, array($this->scr_reference));
		return $query;
	}
	/*
	* update_cost_register
	* @update cost register
	* @input    scr_reference
	* @output   -
	* @author Weerapong Sooksangacharoen
	* @Create Date 2562-08-17
	*/
	function update_cost_register()
	{
		$sql = "UPDATE " . $this->swm_db . ".swm_cost_register 
				SET	scr_is_active = 'Y'
				WHERE scr_reference =?";
		$this->swm->query($sql, array($this->scr_reference));
	}
	/*
	* update_cost_register_active
	* @update status cost register
	* @input    -
	* @output   -
	* @author Weerapong Sooksangacharoen
	* @Create Date 2562-08-17
	*/
	function update_cost_register_active()
	{
		$sql = "UPDATE " . $this->swm_db . ".swm_cost_register 
				SET	scr_is_active = 'N'";
		$this->swm->query($sql);
	}
	/*
	* search_scr_reference
	* @serarch reference
	* @input    scr_sug_id
	* @output   scr_reference
	* @author Weerapong Sooksangacharoen
	* @Create Date 2562-08-17
	*/
	function search_scr_reference()
	{
		$sql = "SELECT scr_reference
				FROM $this->swm_db.swm_cost_register
				WHERE scr_sug_id = ? 
				GROUP BY scr_reference";
		$query = $this->swm->query($sql, array($this->scr_sug_id));
		return $query;
	}
	/*
	* search
	* @search all data
	* @input    scr_age_min, scr_age_min, scr_sug_id, scr_is_active
	* @output   scr_id, scr_cost
	* @author Weerapong Sooksangacharoen
	* @Create Date 2562-08-17
	*/
	function search()
	{
		$sql = "SELECT scr_id, scr_cost
				FROM $this->swm_db.swm_cost_register
				WHERE ? BETWEEN scr_age_min AND scr_age_min
				AND scr_sug_id = ?
				AND scr_is_active = 'Y'";
		$query = $this->swm->query($sql, array($this->ages));
		return $query;
	}
	/*
	* get_new_scr_reference
	* @new reference
	* @input    -
	* @output   reference_id
	* @author Weerapong Sooksangacharoen
	* @Create Date 2562-08-17
	*/
	function get_new_scr_reference()
	{
		$sql = "SELECT MAX(scr_reference) + 1 AS reference_id
				FROM $this->swm_db.swm_cost_register";
		$query = $this->swm->query($sql);
		return $query;
	}
	/*
	* get_actived_register_cost_by_age
	* @status active of register cost
	* @input    age, scr_is_active, scr_age_max
	* @output   scr_cost
	* @author Weerapong Sooksangacharoen
	* @Create Date 2562-08-17
	*/
	function get_actived_register_cost_by_age($age)
	{
		$sql = "SELECT `scr_cost`
				FROM `swm_cost_register` 
				WHERE `scr_is_active`='Y'
					AND `scr_age_max`>= $age
				ORDER BY `scr_id` ASC
				LIMIT 1";

		$query = $this->swm->query($sql);
		return $query;
	}
	/*
	* remove_price_config
	* @delete price 
	* @input    scr_reference
	* @output   -
	* @author Weerapong Sooksangacharoen
	* @Create Date 2562-08-17
	*/
	function remove_price_config()
	{ //remove by scp_reference
		$sql = "UPDATE swm_cost_register
				SET scr_is_remove = 'Y' 
				WHERE scr_reference = ?";
		$this->swm->query($sql, array($this->scr_reference));
	}
	/*
	* get_id_by_reference
	* @id of reference
	* @input    reference
	* @output   scr_id
	* @author Weerapong Sooksangacharoen
	* @Create Date 2562-08-17
	*/
	function get_id_by_reference($reference)
	{
		$sql = "SELECT scr_id 
				FROM $this->swm_db.swm_cost_register 
				WHERE scr_reference = $reference
				ORDER BY scr_id ASC";
		$query = $this->swm->query($sql);
		return $query;
	}

	/*
	* get_all_register_age_range
	* @get all register age range
	* @input    
	* @output set of age ranges
	* @author Weerapong Sooksangacharoen
	* @Create Date 2562-12-17
	*/
	function get_all_register_age_range()
	{
		$sql = "SELECT DISTINCT CONCAT(scr_age_min, ' AND ' , scr_age_max) AS age_range,
					   			scr_age_min,
					   			scr_age_max
				FROM swm_cost_register";
		$query = $this->swm->query($sql);
		return $query;
	}
} // end class M_swm_cost_register
