<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Test_controller extends AdminController
{
	public function index()
	{
		// $query = $this->db->get('tblpharmacy');
		// $data = array();
		// foreach($query->result_array() as $row){
		// 	$row_arr = array(
		// 		'name' => $row['pharmacy_name'],
		// 		'title' => $row['pharmacy_name'],
		// 		'description' => $row['pharmacy_details'],
		// 	);
		// 	array_push($data, $row_arr);
		// }
		// $this->db->insert_batch('tblpharmacy_leads', $data);
		echo "ok";

	}

	public function import_data()
    {
        $pharmacies = $this->select_all_pharmacy();
        $count = 0;

        foreach($pharmacies as $pharmacy) {
            $data = [];
            $data["name"] = $pharmacy["pharmacy_name"]." (".$pharmacy["pharmacy_apb"].")";
            $data["title"] = $pharmacy["pharmacy_name"]." (".$pharmacy["pharmacy_apb"].")";
            $data["company"] = $pharmacy["pharmacy_name"]." (".$pharmacy["pharmacy_apb"].")";
            $data["country"] = 22;
            $data["zip"] = $pharmacy["pharmacy_post_code"];
            $data["city"] = $this->db->where("city_id",$pharmacy["pharmacy_city_id"])->get("city")->row()->city_name;
            $data["state"] = $this->db->where("province_id",$pharmacy["pharmacy_province_id"])->get("province")->row()->province_name;
            $data["address"] = $pharmacy["pharmacy_street"];
            $data["dateadded"] = date("Y-m-d H:i:s");
            $data["status"] = 2;
            $data["source"] = 3;
            $data["addedfrom"] = 0;
            $data["email"] = $pharmacy["pharmacy_email"];
            $data["website"] = $pharmacy["pharmacy_website"];
            $data["phonenumber"] = $pharmacy["pharmacy_mobile"];
            $this->db->insert("tblpharmacy_leads",$data);
            $count++;
        }

        echo "complete: ".$count;
    }

    public function select_all_pharmacy() 
    {
        $this->db->where('pharmacy_id >=',1);
        $this->db->where('pharmacy_id <=',10000);
        return $this->db->get("tblpharmacy")->result_array();   
    }
}