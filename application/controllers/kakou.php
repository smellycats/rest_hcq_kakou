<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is vehicle info rest
 *
 * @package		CodeIgniter
 * @subpackage	Kakou Rest Server
 * @category	Controller
 * @author		Fire
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
//require APPPATH . '/libraries/REST_Controller.php';

class Kakou extends Parsing_Controller
{
	function __construct()
    {
        // Construct our parent class
        parent::__construct();
        
        $this->load->model('Mhcq');

		$this->load->helper('date');

        // header('Cache-Control: public, max-age=60, s-maxage=60');
        header('Content-Type: application/json');
        header("HTTP/1.1 200 OK");

        $this->img_ip = array(
            'HDWJ-KKDATA1' => '10.44.249.227:81',
            'HDWJ-KKDATA2' => '10.44.249.227:82'
        );
        $this->hpys_id = array(
            '其他' => 1,
            '蓝牌' => 2,
            '黄牌' => 3,
            '白牌' => 4,
            '黑牌' => 5
        );
        $this->hpys_code = array(
            '其他' => 'QT',
            '蓝牌' => 'BU',
            '黄牌' => 'YL',
            '白牌' => 'WT',
            '黑牌' => 'BK'
        );
        $this->fxbh_code = array(
            '其他' => 'QT',
            '进城' => 'IN',
            '出城' => 'OT',
            '由东往西' => 'EW',
            '由南往北' => 'SN',
            '由西往东' => 'WE',
            '由北往南' => 'NS'
        );
		$this->code2fxbh = array(
            'QT' => '其他',
            'IN' => '进城',
            'OT' => '出城',
            'EW' => '由东往西',
            'SN' => '由南往北',
            'WE' => '由西往东',
            'NS' => '由北往南'
		);
    }


	function test_get()
	{
		/*
		$query = $this->Mhcq->getKkdd('441302');
		$items = [];
		foreach($query->result_array() as $id=>$row) {
			$items[$id]['kkdd_id'] = $row['kkdd_id'];
			$items[$id]['kkdd_name'] = $row['kkdd_name'];
			$items[$id]['fxbh_list'] = json_decode($row['fxbh_list']);
		}
		#var_dump($data);
		$json = json_encode(array('total_count' => $query->num_rows(), 'items' => $items));
		echo $json; */
		$data['st'] = '2016-03-01 12:23:34';
		$data['et'] = '2016-03-01 14:23:34';
		$data['kkdd'] = '东江大桥卡口';
		$result = $this->Mhcq->getCltx($data)->row();
		var_dump($result);
	}

    /**
     * 根据kkdd_id获取卡口地点信息
     * 
     * @return json
     */
	public function kkdd_get()
	{
		$kkdd_id = $this->uri->segment(3);
		$query = $this->Mhcq->getKkdd($kkdd_id);
		$items = [];
		foreach($query->result_array() as $id=>$row) {
			$items[$id]['kkdd_id'] = $row['kkdd_id'];
			$items[$id]['kkdd_name'] = $row['kkdd_name'];
			$items[$id]['fxbh_list'] = json_decode($row['fxbh_list']);
		}
		#var_dump($data);
		$json = json_encode(array('total_count' => $query->num_rows(), 'items' => $items));
		echo $json;
	}
	
	/*
	public function carinfo2_get()
	{
		#empty(@$this->gets['q']);
		#$this->gets['q'];
		#var_dump(array_key_exists('q', $this->gets));
		#var_dump($this->gets['q']);
		
`		if (!array_key_exists('q', $this->gets)) {
			var_dump('test');
		}
			#var_dump('test');
			#$e = [array('resource' => 'Search', 'field' => 'q', 'code' => 'missing')];
			#$this->response(array('message' => 'Validation Failed', 'errors' => $e), 422);
		#$q_arr = h_convert_param($this->gets['q']);
		#var_dump($q_arr); 
	} */

	public function carinfo_get()
	{
		if (!array_key_exists('q', $this->gets)) {
			$e = [array('resource' => 'Search', 'field' => 'q', 'code' => 'missing')];
			$this->response(array('message' => 'Validation Failed', 'errors' => $e), 422);
		}

		$q_arr = h_convert_param($this->gets['q']);
		if (!array_key_exists('st', $q_arr)) {
			$e = [array('resource' => 'Search', 'field' => 'st', 'code' => 'missing')];
			$this->response(array('message' => 'Validation Failed', 'errors' => $e), 422);
		} else {
			$data['st'] = $q_arr['st'];
		}
		if (!array_key_exists('et', $q_arr)) {
			$e = [array('resource' => 'Search', 'field' => 'et', 'code' => 'missing')];
			$this->response(array('message' => 'Validation Failed', 'errors' => $e), 422);
		} else {
			$data['et'] = $q_arr['et'];
		}
		if (array_key_exists('fxbh', $q_arr)) {
			$data['fxbh'] = array_key_exists($q_arr['fxbh'], $this->code2fxbh) ? $this->code2fxbh[$q_arr['fxbh']] : 'QT';
		}
		if (array_key_exists('cdbh', $q_arr)) {
			$data['cdbh'] = $q_arr['cdbh'];
		}
		$query = $this->Mhcq->getKkddById($q_arr['q']);
		if ($query->num_rows() == 0) {
			echo json_encode(array('count' => 0));
		}
		$data['kkdd'] = $query->row()->kkdd_name;
		$count = $this->Mhcq->getCltx($data)->row()->SUM;
		echo json_encode(array('count' => (int)$count));
	}
}