<?php
// * M_swm_cost_pool
// * @Model Da_swm_cost_pool
// * @author   Weerapong Sooksangacharoen
// * @Create Date  2562-08-17
// * @Update   Weerapong Sooksangacharoen
// * @Update Date  2562-08-27
include_once(dirname(__FILE__) . "/../Da_swm_cost_pool.php");

class M_swm_cost_pool extends Da_swm_cost_pool
{
	/*
	* get_options
	* @input    aOrderBy
	* @output   query
	* @author   Weerapong Sooksangacharoen
	* @Create Date  2562-08-17
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
				FROM swm_cost_pool 
				$orderBy";
		$query = $this->swm->query($sql);
		return $query;
	}
	/*
	* get_options
	* @input    optional
	* @output   opt
	* @author   Weerapong Sooksangacharoen
	* @Create Date  2562-08-17
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
	* get_cost_pool_detail
	* @get cost pool detail
	* @input    scp_sug_id
	* @output   update_date
	* @author   Weerapong Sooksangacharoen
	* @Create Date  2562-08-17
	*/
	function get_cost_pool_detail($sug_id)
	{
		$sql = "SELECT scp_id,scp_age_min,scp_age_max,scp_cost,scp_sug_id,scp_reference,scp_is_active,SUBSTR(scp_update_date,1 ,10) AS update_date
				FROM $this->swm_db.swm_cost_pool 
				WHERE scp_sug_id = $sug_id
				AND scp_is_remove = 'N'
				ORDER BY scp_reference DESC";
		$query = $this->swm->query($sql);
		return $query;
	}

	/*
	* update_cost_pool
	* @update cost pool
	* @input    scp_reference
	* @output   -
	* @author   Weerapong Sooksangacharoen
	* @Create Date  2562-08-17
	*/
	function update_cost_pool()
	{

		$sql = "UPDATE " . $this->swm_db . ".swm_cost_pool 
				SET	scp_is_active = 'Y'
				WHERE scp_reference =?";
		$this->swm->query($sql, array($this->scp_reference));
	}

	/*
	* update_cost_pool_active
	* @update cost pool active
	* @input    scp_sug_id
	* @output   -
	* @author   Weerapong Sooksangacharoen
	* @Create Date  2562-08-17
	*/
	function update_cost_pool_active()
	{

		$sql = "UPDATE " . $this->swm_db . ".swm_cost_pool 
				SET	scp_is_active = 'N'
				WHERE scp_sug_id =?";
		$this->swm->query($sql, array($this->scp_sug_id));
	}
	/*
	* search_scp_reference
	* @search scp_reference
	* @input    scp_sug_id
	* @output   scp_reference
	* @author   Weerapong Sooksangacharoen
	* @Create Date  2562-08-17
	*/
	function search_scp_reference()
	{
		$sql = "SELECT scp_reference
				FROM $this->swm_db.swm_cost_pool
				WHERE scp_sug_id = ? 
				GROUP BY scp_reference";
		$query = $this->swm->query($sql, array($this->scp_sug_id));
		return $query;
	}
	/*
	* search
	* @search by scp_id
	* @input    scp_age_min, scp_age_max, scp_sug_id, scp_is_active
	* @output   scp_id, scp_cost
	* @author   Weerapong Sooksangacharoen
	* @Create Date  2562-08-17
	*/
	function search()
	{
		$sql = "SELECT scp_id, scp_cost
				FROM $this->swm_db.swm_cost_pool
				WHERE ? BETWEEN scp_age_min AND scp_age_max
				AND scp_sug_id = ?
				AND scp_is_active = 'Y'";
		$query = $this->swm->query($sql, array($this->ages, $this->user_group));
		return $query;
	}
	/*
	* get_new_scp_reference
	* @get new scp_reference
	* @input    -
	* @output   reference_id 
	* @author   Weerapong Sooksangacharoen
	* @Create Date  2562-08-17
	*/
	function get_new_scp_reference()
	{
		$sql = "SELECT MAX(scp_reference) + 1 AS reference_id
				FROM $this->swm_db.swm_cost_pool";
		$query = $this->swm->query($sql);
		return $query;
	}
	/*
	* remove_price_config
	* @delete price config
	* @input    scp_reference
	* @output   -
	* @author   Weerapong Sooksangacharoen
	* @Create Date  2562-08-17
	*/
	function remove_price_config()
	{ //remove by scp_reference
		$sql = "UPDATE swm_cost_pool
				SET scp_is_remove = 'Y' 
				WHERE scp_reference = ?";
		$this->swm->query($sql, array($this->scp_reference));
	}
	/*
	* get_id_by_reference
	* @get id by reference
	* @input    reference
	* @output   scp_id
	* @author   Weerapong Sooksangacharoen
	* @Create Date  2562-08-17
	*/
	function get_id_by_reference($reference)
	{
		$sql = "SELECT scp_id 
				FROM $this->swm_db.swm_cost_pool 
				WHERE scp_reference = $reference
				ORDER BY scp_id ASC";
		$query = $this->swm->query($sql);
		return $query;
	}
	/*
	* update
	* @update cost pool
	* @input    scp_id
	* @output   -
	* @author   Weerapong Sooksangacharoen
	* @Create Date  2562-08-17
	*/
	function update()
	{
		// if there is no primary key, please remove WHERE clause.
		$sql = "UPDATE " . $this->swm_db . ".swm_cost_pool 
				SET	scp_age_min=?, scp_age_max=?, scp_cost=?, scp_update_date=? 
				WHERE scp_id=?";
		$this->swm->query($sql, array($this->scp_age_min, $this->scp_age_max, $this->scp_cost, $this->scp_update_date, $this->scp_id));
	}
	/*
	* get_all_cost_pool_age_range
	* @get all cost pool age range
	* @input    -
	* @output   all age range
	* @author   Weerapong Sooksangacharoen
	* @Create Date  2562-12-19
	*/
	function get_all_cost_pool_age_range()
	{
		$sql = "SELECT DISTINCT CONCAT(scp_age_min, ' AND ', scp_age_max) AS age_range,
					   scp_age_min,
					   scp_age_max
				FROM swm_cost_pool";
		$query = $this->swm->query($sql);
		return $query;
	}
	/*
	* get_age_range_by_sug_id
	* @get age ranges
	* @input    scp_sug_id
	* @output   actived age range
	* @author   Weerapong Sooksangacharoen
	* @Create Date  2562-12-22
	*/
	function get_age_range_by_sug_id()
	{
		$sql = "SELECT DISTINCT scp_age_min, scp_age_max
				FROM swm_cost_pool
				WHERE scp_is_active = 'Y'
				AND scp_sug_id = ?";
		return $this->swm->query($sql, array($this->scp_sug_id));
	}
} // end class M_swm_cost_pool
