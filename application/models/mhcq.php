<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mhcq extends CI_Model
{
	private $tj_db;
	private $kakou_db;
    /**
     * Construct a mhd instance
     *
     */
	public function __construct()
	{
		parent::__construct();
		
		$this->tj_db = $this->load->database('tj_db', TRUE);
		$this->kakou_db = $this->load->database('kakou_db', TRUE);
	}
	
    /**
     * 根据卡口地点编码获取卡口地点信息
     * 
	 * @param kkdd_id 卡口地点编码,6位如441302
     * @return object
     */
	public function getKkdd($kkdd_id)
	{
		$this->tj_db->like('kkdd_id', $kkdd_id, 'after');
		$this->tj_db->where('banned', 0);
		return $this->tj_db->get('kkdd');
	}

    /**
     * 根据卡口地点编码获取一条卡口地点信息
     * 
	 * @param kkdd_id 卡口地点编码,如441302001
     * @return object
     */
	public function getKkddById($kkdd_id)
	{
		$this->tj_db->where('kkdd_id', $kkdd_id);
		return $this->tj_db->get('kkdd');
	}

    /**
     * 根据查询条件获取车流量
     * 
	 * @param data 输入条件数组,包括st,et,fxbh,cdbh,kkdd
     * @return object
     */
	public function getCltx($data)
	{
		$sqlstr = '';
		if (isset($data['fxbh'])) {
			$sqlstr .= " AND fxbh='$data[fxbh]'";
		}
		if (isset($data['cdbh'])) {
			$sqlstr .= " AND cdbh=$data[cdbh]";
		}
		return $this->kakou_db->query("SELECT count(*) AS sum FROM cltx t WHERE jgsj >= to_date('$data[st]', 'yyyy-mm-dd hh24:mi:ss') AND jgsj <= to_date('$data[et]', 'yyyy-mm-dd hh24:mi:ss') AND wzdd='$data[kkdd]'" . $sqlstr);
	}

}
?>

