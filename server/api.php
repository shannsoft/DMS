<?php
header('Access-Control-Allow-Origin: *');

	require_once("Rest.inc.php");
	class API extends REST {
		public $data = "";

		const DB_SERVER = "localhost";
		const DB_USER = "root";
		//const DB_USER = "goapps";
		const DB_PASSWORD = "";
		//const DB_PASSWORD = "goapps123";
	  	const DB = "teknobiz_docs";
		// adding table names
		const usersTable = "user_table";
		const clientTable = "client_table";
		const enqTable = "enquiry_table";
		const enqItemTable = "enquiry_item_table";
		const quotTable = " quotaion_table";
		const quotDetTable = "quotaion_details_table";
		const poTable = "purchase_order_table";
		const poDetTable = "purchase_order_details_table";
		const quotHisTable = "quotation_history_table";
		const quotDetHisTable = "quotation_details_history_table";

		const fileUploadPath = "files/";
		private $db = NULL;
		private $proxy = NULL;
		private $storeApiLogin = false;
		public $errorCodes = array(
			"200" => "The client request has succeeded",
			"201" => "Created",
			"202" => "Accepted",
			"203" => "Non-authoritative information.",
			"204" => "No content",
			"205" => "Reset content",
			"206" => "Partial content",
			"302" => "Object moved",
			"304" => "Not modified",
			"307" => "Temporary redirect",
			"400" => "Bad request",
			"401" => "Access denied",
			"402" => "Payment Required",
			"403" => "Forbidden",
			"404" => "Not found",
			"405" => "HTTP verb used to access this page is not allowed",
			"406" => "Client browser does not accept the MIME type of the requested page",
			"407" => "Proxy authentication required",
			"412" => "Precondition failed",
			"413" => "Request entity too large",
			"414" => "Request-URL too long",
			"415" => "Unsupported media type",
			"416" => "Requested range not satisfiable",
			"417" => "Execution failed",
			"423" => "Locked error",
			"500" => "Internal server error",
			"501" => "Header values specify a configuration that is not implemented",
			"502" => "Bad Gateway",
			"503" => "Service unavailable",
			"504" => "Gateway timeout",
			"505" => "HTTP version not supported"
		);
		public $messages = array(
			"operationNotDefined" => "Operation not Defined",
			"dataFetched" => "data fetched success",
			"userCreated" => "User created successfully",
			"deleted" => "data deleted successfully",
			"userUpdated" => "data updated successfully",
			"loginSuccess" => "successfully Logedin",
			"userLogout" => "Successfully log out",
			"changedPassword" => "Successfully Changed your password",
			"dataSaved" => "Data saved successfully",
			"planDeleted" => "Plan Successfully Deleted"
		);

		public function __construct(){
			parent::__construct();
			$this->dbConnect();
		}

		private function dbConnect(){

			$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
			if($this->db)
                mysql_select_db(self::DB, $this->db) or die('ERRROR:'.mysql_error());
			else
				echo "db not exists";
		}

		public function processApi(){
			$func='';
			if(isset($_REQUEST['service']))
				$func = strtolower(trim(str_replace("/", "", $_REQUEST['service'])));
			else if(isset($_REQUEST['reqmethod']))
				$func = strtolower(trim(str_replace("/", "", $_REQUEST['reqmethod'])));
			if($func){
				if (method_exists($this, $func)) {
					$this->$func();
				} else{
					$this->log('invalid service:'.$func." at ".date("Y-m-d H:i:s"));
					$this->response('invalid service', 406);
				}
			}
			else
				echo "invalid function";
		}

		public function log($logText,$type = 3 ,$destFile= 'error_log.txt'){
			error_log("\n".$logText,$type,$destFile);
		}
		public function json($data){
	        if(is_array($data))
	        {
              $formatted= json_encode($data);
              return $this->formatJson($formatted);
	        }
			else {
				return $data;
			}
    	}
	    private function formatJson($jsonData){
	        $formatted = $jsonData;
	        $formatted = str_replace('"{', '{', $formatted);
	        $formatted = str_replace('}"', '}', $formatted);
	        $formatted = str_replace('\\', '', $formatted);
	        return $formatted;
	    }
		private function isValidCall($apiKey){
			$flag=false;
			$apiKey = mysql_real_escape_string($apiKey);

			$sql="SELECT api_key  FROM ".self::TABLE_API_DATA." WHERE api_key ='$apiKey' ";
			$result = mysql_query($sql, $this->db);
			if(mysql_num_rows($result) > 0) {
          $rows =  mysql_fetch_array($result,MYSQL_ASSOC);
          $apiKeyDB = $rows['api_key'];
          $flag = true;
			}
			return $flag;
		}
		public function generateRandomString($length = 60) {
		    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
		    return $randomString;
		}
		/*
		START  :: 07/07/17 :: Santosh Kumar Majhi (sath sath ek sath)
		*/
    	public function executeGenericDQLQuery($query){
          try{
              if(!$this->db)
              {
                  $this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
              }
              $result = mysql_query($query, $this->db);

              $rows = array();
              while($row = mysql_fetch_array($result)){
                  array_push($rows,$row);
              }
              return $rows;
          }
          catch(Exception $e){
              $response = array();
              $response['status'] = false;
              $response['message'] = $e->getMessage();
              $this->response($this->json($response), 200);
          }
        }
        public function executeGenericDMLQuery($query){
            try{
                $result = mysql_query($query, $this->db);
                if(mysql_errno($this->db) != 0){
                    throw new Exception("Error   :".mysql_errno($this->db)."   :  ".mysql_error($this->db));
                }
				return mysql_affected_rows(); 
            }
            catch(Exception $e){
                $response = array();
                $response['status'] = false;
                $response['message'] = $e->getMessage();
                //echo json_encode($response);
                $this->response($this->json($response), 200);
            }
        }
        public function executeGenericInsertQuery($query){
            try{
                $result = mysql_query($query, $this->db);
                if(mysql_errno($this->db) != 0){
                    throw new Exception("Error   :".mysql_errno($this->db)."   :  ".mysql_error($this->db));
                }
                return mysql_insert_id($this->db);
            }
            catch(Exception $e){
                $response = array();
                $response['status'] = false;
                $response['message'] = $e->getMessage();
                //echo json_encode($response);
                $this->response($this->json($response), 200);
            }
        }
		public function sendResponse($statusCode,$status,$message = null ,$data = null){
			$response = array();
			$response['statusCode'] = $statusCode;
			$response['status'] = $status;
			$response['message'] = $message;
			$response['data'] = $data;
			$this->response($this->json($response), 200);
        }
		public function sendResponse2($statusCode,$message = null ,$data = null){
			$response = array();
			$response[$statusCode] = $this->errorCodes[$statusCode];
			$response['message'] = $message;
			$response['data'] = $data;
			$this->response($this->json($response), 200);
        }
        public function clearArray($arr){
            unset($arr);
            $arr = array();
            return $arr;
        }
       
		public function login() {
			if(!isset($this->_request['user_name']) || !isset($this->_request['password']))
				$this->sendResponse(202,"failed","validation Error","Invalid user name or password");
			$user_name = $this->_request['user_name'];
			$password = md5($this->_request['password']);
			$token = $this->generateRandomString();
			$sql = "update ".self::usersTable." set token='$token' where user_name='$user_name'";
			$result = $this->executeGenericDMLQuery($sql);
			if($result){
				$sql = "select * from ".self::usersTable." where user_name = '$user_name' and password = '$password' limit 1";
				$rows = $this->executeGenericDQLQuery($sql);
				if(sizeof($rows)){
					$users = array();
					if($rows[0]['is_active'] == 1){
						$users['id'] = $rows[0]['user_id'];
						$users['user_name'] = $rows[0]['user_name'];
						$users['first_name'] = $rows[0]['first_name'];
						$users['last_name'] = $rows[0]['last_name'];
						$users['token'] = $rows[0]['token'];
						$users['is_active'] = $rows[0]['is_active'];
						$this->sendResponse(200,'success',$this->messages['loginSuccess'],$users);
					}
					else {
						$this->sendResponse(202,"failed","You are not a active user contact to admin");
					}
				}
				else {
					$this->sendResponse(201,"failure","fail");
				}
			}
			else{
				$this->sendResponse(202,"validation Error","Invalid user name or password");
			}
        }
		public function logout(){
			$headers = apache_request_headers();
			if($headers['Accesstoken']){
				$token = $headers['Accesstoken'];
				$sql = "update ".self::usersTable." set token='' where token='$token'";
				$result = $this->executeGenericDMLQuery($sql);
				if($result){
					$this->sendResponse2(200,$this->messages['userLogout']);
				}
			}
		}
		public function getUserDetails(){
			$headers = apache_request_headers();
			if($headers['Accesstoken']){
				$token = $headers['Accesstoken'];
				$sql = "SELECT * FROM ".self::usersTable." where token='$token'";
				$rows = $this->executeGenericDQLQuery($sql);
				$user = array();
				$user['user_id'] = $rows[0]['user_id'];
				$user['first_name'] = $rows[0]['first_name'];
				$user['last_name'] = $rows[0]['last_name'];
				$user['user_name'] = $rows[0]['user_name'];
				$user['token'] = $rows[0]['token'];
				$user['is_active'] = $rows[0]['is_active'];
				$this->sendResponse(200,"success","",$user);
			}
		}
		public function changePassword(){
			if(isset($this->_request['token'])){
				$token = $this->_request['token'];
				$password = md5($this->_request['password']);
				$sql = "update ".self::usersTable." set password='$password' where token='$token'";
				$result = $this->executeGenericDMLQuery($sql);
				if($result){
					$this->sendResponse2(200,$this->messages['changedPassword']);
				}
			}
		}
		public function updateProfile(){
			$user_data = isset($this->_request['user_data']) ? $this->_request['user_data'] : $this->_request;
			$email = isset($user_data['email']) ? $user_data['email'] : '';
			$first_name = isset($user_data['first_name']) ? $user_data['first_name'] : '';
			$last_name = isset($user_data['last_name']) ? $user_data['last_name'] : '';
			$mobile = isset($user_data['mobile']) ? $user_data['mobile'] : '';
			$token = $user_data['token'];
			$previous = false;
			$sql = "update ".self::usersTable." set ";
			if(isset($user_data['email'])){
				$sql .="email ='$email'";
				$previous = true;
			}
			if(isset($user_data['first_name'])){
				$comma = ($previous) ? ',' : '';
				$sql .="$comma first_name ='$first_name'";
				$previous = true;
			}
			if(isset($user_data['last_name'])){
				$comma = ($previous) ? ',' : '';
				$sql .="$comma last_name ='$last_name'";
				$previous = true;
			}
			if(isset($user_data['mobile'])){
				$comma = ($previous) ? ',' : '';
				$sql .="$comma mobile ='$mobile'";
				$previous = true;
			}
			$sql .= " where token='$token'";
			$result = $this->executeGenericDMLQuery($sql);
			if($result){
				$this->sendResponse2(200,$this->messages['userUpdated']);
			}
		}
		public function checkPassword(){
			if(isset($this->_request['password']) && isset($this->_request['token'])){
				$cpass = $this->_request['password'];
				$token = $this->_request['token'];
				$sql = "SELECT password FROM ".self::usersTable." where token='$token'";
				$rows = $this->executeGenericDQLQuery($sql);
				$users = array();
				if($rows[0]['password'] == md5($cpass)){
					$this->sendResponse(200,"success","ok");
				}
				else{
					$this->sendResponse(201,"failure","fail");
				}
			}
		}
		public function client(){
			$headers = apache_request_headers();
			if($headers['Accesstoken']){
				if(!isset($this->_request['operation']))
					$this->sendResponse2(400,$this->messages['operationNotDefined']);
				$sql = null;
				switch ($this->_request['operation']) {
					case 'create':
						$client_data = $this->_request['client_data'];
						$org_name = $client_data['org_name'];
						$mobile = $client_data['mobile'];
						$email = $client_data['email'];
						$contact_person = $client_data['contact_person'];
						$gstn_no = $client_data['gstn_no'];
						$address = $client_data['address'];
						$sql = "INSERT INTO ".self::clientTable."(org_name, mobile, email, contact_person, GSTN_No,address) 
							VALUES ('$org_name','$mobile','$email','$contact_person','$gstn_no','$address')";
						$result = $this->executeGenericDMLQuery($sql);
						if($result && (isset($client_data['phone']) || isset($client_data['web']) )){
							$client_id = mysql_insert_id();
							$sql = "update ".self::clientTable." set ";
							$comma = '';
							if(isset($client_data['phone'])){
								$phone = $client_data['phone'];
								$sql .= "phone='$phone'";
								$comma = ', ';
							}
							if(isset($client_data['web'])){
								$web = $client_data['web'];
								$sql .= $comma."web='$web'";	
							}
							$sql .=" where client_id=".$client_id;
							$result1 = $this->executeGenericDMLQuery($sql);
						}
						$this->sendResponse(200,'Ok','Client added successfully');
						break;
					case 'get' :
						$sql = "SELECT * FROM ".self::clientTable.' where is_deleted=0';
						if(isset($this->_request['id'])){
							$id = $this->_request['id'];
							$sql .= ' AND client_id='.$id;
						}
						$rows = $this->executeGenericDQLQuery($sql);
						$data = array();
						for($i=0;$i<sizeof($rows);$i++){
							$data[$i]['client_id'] = (int)$rows[$i]['client_id'];
							$data[$i]['org_name'] = $rows[$i]['org_name'];
							$data[$i]['mobile'] = $rows[$i]['mobile'];
							$data[$i]['phone'] = $rows[$i]['phone'];
							$data[$i]['email'] = $rows[$i]['email'];
							$data[$i]['contact_person'] = $rows[$i]['contact_person'];
							$data[$i]['gstn_no'] = $rows[$i]['GSTN_No'];
							$data[$i]['web'] = $rows[$i]['web'];
							$data[$i]['address'] = $rows[$i]['address'];
						}
						$this->sendResponse(200,'success',$this->messages['dataFetched'],$data);
						break;
					case 'delete' :
						if(isset($this->_request['id'])){
							$id = $this->_request['id'];
							$sql = "update ".self::clientTable." set is_deleted=1 where client_id=".$id;
							$this->executeGenericDMLQuery($sql);
							$this->sendResponse(200,'success','Client deleted successfully');
						}
						break;
					case 'update':
						$client_data = $this->_request['client_data'];
						$client_id = $client_data['client_id'];
						$sql = "update ".self::clientTable." set ";
						$comma = '';
						if(isset($client_data['org_name'])){
							$org_name = $client_data['org_name'];
							$sql .= "org_name = '$org_name'";
							$comma = ', ';
						}
						if(isset($client_data['mobile'])){
							$mobile = $client_data['mobile'];
							$sql .= $comma."mobile = '$mobile'";
							$comma = ', ';
						}
						if(isset($client_data['email'])){
							$email = $client_data['email'];
							$sql .= $comma."email = '$email'";
							$comma = ', ';
						}
						if(isset($client_data['contact_person'])){
							$contact_person = $client_data['contact_person'];
							$sql .= $comma."contact_person = '$contact_person'";
							$comma = ', ';
						}
						if(isset($client_data['contact_person'])){
							$gstn_no = $client_data['gstn_no'];
							$sql .= $comma."GSTN_No = '$gstn_no'";
							$comma = ', ';
						}
						if(isset($client_data['phone'])){
							$phone = $client_data['phone'];
							$sql .= $comma."phone='$phone'";
							$comma = ', ';
						}
						if(isset($client_data['web'])){
							$web = $client_data['web'];
							$sql .= $comma."web='$web'";	
						}
						if(isset($client_data['address'])){
							$address = $client_data['address'];
							$sql .= $comma."address='$address'";	
						}
						$sql .= ' where client_id='.$client_id;
						$this->executeGenericDMLQuery($sql);
						$this->sendResponse(200,'success','Client updated successfully');
						break;

						
				}
			}
			else{
				$this->sendResponse(404,"Failed","Unauthorized user");
			}
		}
		public function enquiry(){
			$headers = apache_request_headers();
			if($headers['Accesstoken']){
				if(!isset($this->_request['operation']))
					$this->sendResponse2(400,$this->messages['operationNotDefined']);
				$sql = null;
				switch ($this->_request['operation']) {
					case 'create':
						$enq_data = $this->_request['enquiry_data'];
						$client_id = $enq_data['client_id'];
						$date = $enq_data['cur_date'];
						$due_date = $enq_data['cur_due_date'];
						$sql = "INSERT INTO ".self::enqTable."(client_id, enq_date, due_date, status) 
						VALUES ('$client_id','$date','$due_date','No Quotation')";
						$result = $this->executeGenericDMLQuery($sql);
						if($result){
							$enq_id = mysql_insert_id();
							$ref_no = 'TEK/ENQ/'.$enq_id;
							$sql = "update ".self::enqTable." set ref_no='$ref_no' where enq_id=".$enq_id;
							$this->executeGenericDMLQuery($sql);

							$count = sizeof($enq_data['itemList']);
							$item_list = $enq_data['itemList'];
							$query = "INSERT INTO ".self::enqItemTable."(enq_id, item_name, quantity, uom) VALUES ";
							$comma = ', ';
							for ($i = 0; $i < $count; $i++) {
								if($i == $count-1)
									$comma = '';
								$query.="('$enq_id','".$item_list[$i]["name"]."','".$item_list[$i]["qua"]."','".$item_list[$i]["uom"]."')".$comma;
							}
							$result1 = $this->executeGenericDMLQuery($query);
							$this->sendResponse(200,'success','Enquiry added successfully');
						}
						break;
					case 'get' :
						$sql = "select a.enq_id,a.client_id,a.enq_date,a.due_date,a.ref_no,a.status,a.remarks,b.org_name,(select count(*) from quotaion_table where enq_id = a.enq_id) as quot_count from enquiry_table a INNER JOIN client_table b ON a.client_id = b.client_id where a.is_deleted=0";
						$rows = $this->executeGenericDQLQuery($sql);
						$data = array();
						for($i=0;$i<sizeof($rows);$i++){
							$data[$i]['enq_id'] = (int)$rows[$i]['enq_id'];
							$data[$i]['client_id'] = (int)$rows[$i]['client_id'];
							$data[$i]['org_name'] = $rows[$i]['org_name'];
							$data[$i]['enq_date'] = $rows[$i]['enq_date'];
							$data[$i]['due_date'] = $rows[$i]['due_date'];
							$data[$i]['ref_no'] = $rows[$i]['ref_no'];
							$data[$i]['status'] = $rows[$i]['status'];
							$data[$i]['remarks'] = $rows[$i]['remarks'];
							$data[$i]['quot_count'] = $rows[$i]['quot_count'];
						}
						$this->sendResponse(200,$this->messages['dataFetched'],'',$data);
						break;
				}
			}
			else{
				$this->sendResponse(404,"Failed","Unauthorized user");
			}
		}
		public function enquiryDetails(){
			$headers = apache_request_headers();
			if($headers['Accesstoken']){
				$id = $this->_request['id'];
				$sql = "select a.enq_id,a.client_id,a.enq_date,a.due_date,a.ref_no,a.status,a.remarks,b.org_name from enquiry_table a INNER JOIN client_table b ON a.client_id = b.client_id where a.is_deleted = 0 AND a.enq_id=".$id;
				$rows = $this->executeGenericDQLQuery($sql);
				$data = array();
				$data['enq_id'] = $rows[0]['enq_id'];
				$data['client_id'] = (int)$rows[0]['client_id'];
				$data['org_name'] = $rows[0]['org_name'];
				$data['enq_date'] = $rows[0]['enq_date'];
				$data['due_date'] = $rows[0]['due_date'];
				$data['ref_no'] = $rows[0]['ref_no'];
				$data['status'] = $rows[0]['status'];
				$data['remarks'] = $rows[0]['remarks'];
				if(sizeof($rows)){
					$query = "select * from ".self::enqItemTable." where enq_id=".$id;
					$result = $this->executeGenericDQLQuery($query);
					$item = array();
					for($i = 0; $i < sizeof($result); $i++){
						$item[$i]['item_id'] = $result[$i]['item_id'];
						$item[$i]['item_name'] = $result[$i]['item_name'];
						$item[$i]['description'] = $result[$i]['description'];
						$item[$i]['quantity'] = (int)$result[$i]['quantity'];
						$item[$i]['uom'] = $result[$i]['uom'];
					}
				}
				$data['itemList'] = $item;
				$this->sendResponse(200,'success',$this->messages['dataFetched'],$data);
			}
		}
		public function createQuotation(){
			$headers = apache_request_headers();
			if($headers['Accesstoken']){
				$quot_data = $this->_request['quot_data'];
				$enq_id = $quot_data['enq_id'];
				$quot_date = $quot_data['quot_date'];
				$sql = "INSERT INTO ".self::quotTable."(enq_id, quot_date) VALUES ('$enq_id','$quot_date')";
				$result = $this->executeGenericDMLQuery($sql);
				if($result){
					$quot_id = mysql_insert_id();
					$ref_no = 'TEK/QUOT/'.$quot_id;
					$sql = "update ".self::quotTable." set ref_no='$ref_no' where quot_id=".$quot_id;
					$this->executeGenericDMLQuery($sql);
					$count = sizeof($quot_data['itemList']);
					$item_list = $quot_data['itemList'];
					$query = "INSERT INTO ".self::quotDetTable."(quot_id, item_id, price, discount, tot_price, hsn_no, tax, tax_price, valid_upto, description) VALUES ";
					$comma = ', ';
					for ($i = 0; $i < $count; $i++) {
						if($i == $count-1)
							$comma = '';
						$description  = (isset($item_list[$i]["description"])) ? $item_list[$i]["description"] : '';
						$tax  = (isset($item_list[$i]["tax"])) ? $item_list[$i]["tax"] : 0;
						$tax_price  = (isset($item_list[$i]["tax_price"])) ? $item_list[$i]["tax_price"] : 0;
						$hsn_no  = (isset($item_list[$i]["hsn_no"])) ? $item_list[$i]["hsn_no"] : null;

						$query.="('$quot_id','".$item_list[$i]["item_id"]."','".$item_list[$i]["price"]."','".$item_list[$i]["discount"]."','".$item_list[$i]["tot_price"]."','".$hsn_no."','".$tax."','".$tax_price."','".$item_list[$i]["exp_date"]."','".$description."')".$comma;
					}
					$this->executeGenericDMLQuery($query);
					$sql_query = "UPDATE ".self::enqTable." set status='Quotation added' where enq_id=".$enq_id;
					$result = $this->executeGenericDMLQuery($sql_query);
					$this->sendResponse(200,'success','Quotation created successfully');
				}
			}
		}
		public function updateQuotation(){
			$headers = apache_request_headers();
			if($headers['Accesstoken']){
				$quot_data = $this->_request['quot_data'];
				$enq_id = $quot_data['enq_id'];
				$quot_date = $quot_data['quot_date'];
				$quot_id = $quot_data['quot_id'];
				$item_list = $quot_data['itemList'];
				$query = "SELECT * FROM ".self::quotTable." WHERE quot_id=".$quot_id;
				$rows = $this->executeGenericDQLQuery($query);
				if($rows){
					$quot_old_date = $rows[0]['quot_date'];
					$sql = "INSERT INTO ".self::quotHisTable."(quot_id, quot_date) VALUES ('$quot_id','$quot_old_date')";
					$this->executeGenericDMLQuery($sql);
					$his_id = mysql_insert_id();
					$sql_update = "UPDATE ".self::quotTable." set quot_date='$quot_date' where quot_id=".$quot_id;
					$this->executeGenericDMLQuery($sql_update);
					$query = "SELECT * FROM ".self::quotDetTable." WHERE quot_id=".$quot_id;
					$result = $this->executeGenericDQLQuery($query);
					if(sizeof($result)){
						$query = "INSERT INTO ".self::quotDetHisTable."(history_id,quot_id, item_id, price, discount, total_price, hsn_no, tax, tax_price, valid_upto, description) VALUES ";
						$comma = ', ';
						for($i = 0; $i < sizeof($result); $i++){
							if($i == sizeof($result)-1){
								$comma = '';
							}
							$valid_upto_date = $item_list[$i]['valid_upto'];
							$description = $item_list[$i]['description'];
							$tax  = (isset($item_list[$i]["tax"])) ? $item_list[$i]["tax"] : 0;
							$tax_price  = (isset($item_list[$i]["tax_price"])) ? $item_list[$i]["tax_price"] : 0;
							$hsn_no  = (isset($item_list[$i]["hsn_no"])) ? $item_list[$i]["hsn_no"] : null;

							$query.="('$his_id','$quot_id','".$result[$i]["item_id"]."','".$result[$i]["price"]."','".$result[$i]["discount"]."','".$result[$i]["tot_price"]."','".$hsn_no."','".$tax."','".$tax_price."','".$result[$i]["valid_upto"]."','".$result[$i]["description"]."')".$comma;

							$update = "UPDATE ".self::quotDetTable." set price=".$item_list[$i]['price'].", discount=".$item_list[$i]['discount'].", tot_price=".$item_list[$i]['tot_price'].", valid_upto='$valid_upto_date',description='$description' where quot_det_id=".$item_list[$i]['quot_det_id'];
							$this->executeGenericDMLQuery($update);
						}
						$this->executeGenericDMLQuery($query);
						$this->sendResponse(200,'success','Quotation Updated successfully');
					}
				}
			}
		}
		public function quotationList() {
			$headers = apache_request_headers();
			if($headers['Accesstoken']){
				$sql = "select a.quot_id, a.enq_id, a.quot_date, a.ref_no, b.client_id,b.ref_no as enq_ref,b.enq_date,b.status,c.org_name from quotaion_table a INNER JOIN enquiry_table b ON  a.enq_id = b.enq_id INNER JOIN client_table c ON b.client_id = c.client_id where a.is_deleted=0";
				$rows = $this->executeGenericDQLQuery($sql);
				$data = array();
				for($i = 0; $i < sizeof($rows); $i++){
					$data[$i]['quot_id'] = $rows[$i]['quot_id'];
					$data[$i]['enq_id'] = $rows[$i]['enq_id'];
					$data[$i]['quot_date'] = $rows[$i]['quot_date'];
					$data[$i]['ref_no'] = $rows[$i]['ref_no'];
					$data[$i]['client_id'] = $rows[$i]['client_id'];
					$data[$i]['enq_ref'] = $rows[$i]['enq_ref'];
					$data[$i]['org_name'] = $rows[$i]['org_name'];
					$data[$i]['enq_date'] = $rows[$i]['enq_date'];
					$data[$i]['status'] = $rows[$i]['status'];
				}
				$this->sendResponse(200,'success',$this->messages['dataFetched'],$data);
			}
		}
		public function quotationDetails(){
			$headers = apache_request_headers();
			if($headers['Accesstoken']){
				$id = $this->_request['id'];
				$sql = "select a.quot_id, a.enq_id, a.quot_date, a.ref_no as quot_ref, b.client_id, b.enq_date, b.ref_no as enq_ref,b.status,b.remarks,c.org_name from quotaion_table a INNER JOIN enquiry_table b ON a.enq_id=b.enq_id INNER JOIN  client_table c ON b.client_id=c.client_id where a.is_deleted=0 AND a.quot_id=".$id;
				$rows = $this->executeGenericDQLQuery($sql);
				$data = array();
				$data['quot_id'] = $rows[0]['quot_id'];
				$data['enq_id'] = $rows[0]['enq_id'];
				$data['quot_date'] = $rows[0]['quot_date'];
				$data['quot_ref'] = $rows[0]['quot_ref'];
				$data['client_id'] = $rows[0]['client_id'];
				$data['enq_date'] = $rows[0]['enq_date'];
				$data['enq_ref'] = $rows[0]['enq_ref'];
				$data['org_name'] = $rows[0]['org_name'];
				$data['status'] = $rows[0]['status'];
				$data['remarks'] = $rows[0]['remarks'];
				if(sizeof($rows)){
					$query = "select a.quot_det_id, a.quot_id, a.item_id,a.price,a.discount,a.tot_price,a.description,a.hsn_no,a.tax,a.tax_price,a.valid_upto,b.item_name,b.quantity,b.uom from quotaion_details_table a INNER JOIN  enquiry_item_table b ON a.item_id=b.item_id where a.is_deleted=0 AND a.quot_id=".$id;
					$result = $this->executeGenericDQLQuery($query);
					$item = array();
					$total_amount = 0;
					$tax_amount = 0;
					for($i = 0; $i < sizeof($result); $i++){
						$item[$i]['quot_det_id'] = $result[$i]['quot_det_id'];
						$item[$i]['quot_id'] = $result[$i]['quot_id'];
						$item[$i]['item_id'] = $result[$i]['item_id'];
						$item[$i]['price'] = (float)$result[$i]['price'];
						$item[$i]['discount'] = (float)$result[$i]['discount'];
						$item[$i]['tot_price'] = (float)$result[$i]['tot_price'];
						$item[$i]['description'] = $result[$i]['description'];
						$item[$i]['item_name'] = $result[$i]['item_name'];
						$item[$i]['quantity'] = (float)$result[$i]['quantity'];
						$item[$i]['uom'] = $result[$i]['uom'];
						$item[$i]['hsn_no'] = $result[$i]['hsn_no'];
						$item[$i]['tax_price'] = (float)$result[$i]['tax_price'];
						$item[$i]['tax'] = (float)$result[$i]['tax'];
						$item[$i]['valid_upto'] = $result[$i]['valid_upto'];
						$total_amount = $total_amount + ((float)$result[$i]['tot_price']);
						$tax_amount = $tax_amount + ((float)$result[$i]['tax_price']);
					}
					$history  = "SELECT * FROM ".self::quotHisTable." where quot_id=".$id;
					$rows = $this->executeGenericDQLQuery($history);
					$his_data = array();
					if(sizeof($rows)){
						for($i = 0; $i < sizeof($rows); $i++){
							$his_data[$i]['quot_his_id'] = $rows[$i]['quot_his_id'];
							$his_data[$i]['quot_date'] = $rows[$i]['quot_date'];
						}
					}
				}
				$data['itemList'] = $item;
				$data['history'] = $his_data;
				$data['total_amount'] = $total_amount;
				$data['tax_amount'] = $tax_amount;
				$data['grand_total'] = $tax_amount+$total_amount;
				$this->sendResponse(200,'success',$this->messages['dataFetched'],$data);
			}
		}
		public function createPurchaseOrder(){
			$headers = apache_request_headers();
			if($headers['Accesstoken']){
				$po_data = $this->_request['po_data'];
				$quot_id = $po_data['quot_id'];
				$po_no = $po_data['po_no'];
				$po_date = $po_data['po_date'];
				$enq_id = $po_data['enq_id'];
				$sql = "INSERT INTO ".self::poTable."(quot_id, po_no,po_date) VALUES ('$quot_id','$po_no','$po_date')";
				$result = $this->executeGenericDMLQuery($sql);
				if($result){
					$po_id = mysql_insert_id();
					$count = sizeof($po_data['itemList']);
					if($count > 0){
						$item_list = $po_data['itemList'];
						$query = "INSERT INTO ".self::poDetTable."(po_id, quot_det_id, delivery_date, description, quantity, price, hsn_no, tax, tax_price, net_amount, item_code,unbilled_qty, po_sl_no) VALUES ";
						$comma = ', ';
						for ($i = 0; $i < $count; $i++) {
							if($i == $count-1)
								$comma = '';
							$description  = (isset($item_list[$i]["description"])) ? $item_list[$i]["description"] : '';
							$item_code  = (isset($item_list[$i]["item_code"])) ? $item_list[$i]["item_code"] : '';
							$hsn_no  = (isset($item_list[$i]["hsn_no"])) ? $item_list[$i]["hsn_no"] : null;
							$tax  = (isset($item_list[$i]["tax"])) ? $item_list[$i]["tax"] : 0;
							$tax_price  = (isset($item_list[$i]["tax_price"])) ? $item_list[$i]["tax_price"] : 0;

							$query.="('$po_id','".$item_list[$i]["quot_det_id"]."','".$item_list[$i]["delivery_date"]."','".$description."','".$item_list[$i]["quantity"]."','".$item_list[$i]["price"]."','".$hsn_no."','".$tax."','".$tax_price."','".$item_list[$i]["tot_price"]."',$item_code,'".$item_list[$i]["quantity"]."','".$item_list[$i]["po_sl_no"]."')".$comma;
						}
						$this->executeGenericDMLQuery($query);
						$sql_query = "UPDATE ".self::enqTable." set status='Purchase order added' where enq_id=".$enq_id;
						$result = $this->executeGenericDMLQuery($sql_query);
						$this->sendResponse(200,'success','Purchase order added successfully');
					}
				}
			}
		}
		public function quotaionDelete(){
			$headers = apache_request_headers();
			if($headers['Accesstoken']){
				$quot_id = $this->_request['id'];
				if(isset($this->_request['id'])){
					$sql = "update ".self::quotTable." set is_deleted=1 where quot_id=".$quot_id;
					$this->executeGenericDMLQuery($sql);
					$this->sendResponse(200,'success','Quotation deleted successfully');
				}
				else{
					$this->sendResponse(201,'failure','Quotation not found');
				}	
			}
			else{
				$this->sendResponse(404,'failure','Unauthorized User');
			}
		}
		public function getQuotHistoryDetails(){
			$headers = apache_request_headers();
			if($headers['Accesstoken']){
				$id = $this->_request['id'];
				$sql = "select * from ".self::quotHisTable." where quot_his_id=".$id;
				$rows = $this->executeGenericDQLQuery($sql);
				$data = array();
				$data['quot_id'] = $rows[0]['quot_id'];
				$data['quot_date'] = $rows[0]['quot_date'];
				$data['quot_his_id'] = $rows[0]['quot_his_id'];

				 $query = "select a.quot_det_his_id, a.history_id,a.quot_id, a.item_id, a.price, a.discount, a.total_price, a.tax, a.tax_price,a.hsn_no, a.description, a.valid_upto, b.item_name, b.quantity,b.uom from quotation_details_history_table a INNER JOIN  enquiry_item_table b ON a.item_id = b.item_id where a.history_id=".$id;
				$rows = $this->executeGenericDQLQuery($query);
				if(sizeof($rows)){
					$total_amount = 0;
					$tax_amount = 0;
					$item = array();
					for($i = 0; $i < sizeof($rows); $i++){
						$item[$i]['quot_det_his_id'] = $rows[$i]['quot_det_his_id'];
						$item[$i]['history_id'] = $rows[$i]['history_id'];
						$item[$i]['item_id'] = $rows[$i]['item_id'];
						$item[$i]['price'] = (float)$rows[$i]['price'];
						$item[$i]['discount'] = (float)$rows[$i]['discount'];
						$item[$i]['tot_price'] = (float)$rows[$i]['total_price'];
						$item[$i]['description'] = $rows[$i]['description'];
						$item[$i]['valid_upto'] = $rows[$i]['valid_upto'];
						$item[$i]['item_name'] = $rows[$i]['item_name'];
						$item[$i]['quantity'] = $rows[$i]['quantity'];
						$item[$i]['uom'] = $rows[$i]['uom'];
						$item[$i]['tax'] = (float)$rows[$i]['tax'];
						$item[$i]['tax_price'] = (float)$rows[$i]['tax_price'];
						$item[$i]['hsn_no'] = $rows[$i]['hsn_no'];
						$total_amount = $total_amount + ((float)$rows[$i]['total_price']);
						$tax_amount = $tax_amount + ((float)$rows[$i]['tax_price']);
					}
					$data['total_amount'] =  $total_amount;
					$data['tax_amount'] = $tax_amount;
					$data['grand_total'] = $tax_amount + $total_amount;
					$data['itemList'] =  $item;
					$this->sendResponse(200,'success',$this->messages['dataFetched'],$data);
				}

			}
		}
		public function clientDetails(){
			$headers = apache_request_headers();
			if($headers['Accesstoken']){
				$client_id = $this->_request['id'];
				$query = "select a.client_id, a.org_name, a.mobile, a.phone, a.email, a.contact_person, a.GSTN_No,a.web,a.address from client_table a where a.is_deleted =0 AND a.client_id=".$client_id;
				$rows = $this->executeGenericDQLQuery($query);
				$data = array();
				if($rows){
					$data['client_id'] = $rows[0]['client_id'];
					$data['org_name'] = $rows[0]['org_name'];
					$data['mobile'] = $rows[0]['mobile'];
					$data['phone'] = $rows[0]['phone'];
					$data['email'] = $rows[0]['email'];
					$data['contact_person'] = $rows[0]['contact_person'];
					$data['gstn_no'] = $rows[0]['GSTN_No'];
					$data['web'] = $rows[0]['web'];
					$data['address'] = $rows[0]['address'];
				}
				$enq = array();
				$query = "select * from ".self::enqTable." where is_deleted=0 AND client_id=".$client_id;
				$enq_result = $this->executeGenericDQLQuery($query);
				if($enq_result){
					for($i = 0; $i < sizeof($enq_result); $i++){
						$enq[$i]['enq_id'] = $enq_result[$i]['enq_id'];
						$enq[$i]['enq_date'] = $enq_result[$i]['enq_date'];
						$enq[$i]['due_date'] = $enq_result[$i]['due_date'];
						$enq[$i]['ref_no'] = $enq_result[$i]['ref_no'];
						$enq[$i]['status'] = $enq_result[$i]['status'];
						$enq[$i]['remarks'] = $enq_result[$i]['remarks'];
						$enq_id = $enq_result[$i]['enq_id'];
						$item_list = "select * from ".self::enqItemTable." where enq_id=".$enq_id;
						$item_result = $this->executeGenericDQLQuery($item_list);
						if($item_result){
							$item = array();
							for($j = 0; $j < sizeof($item_result); $j++){
								$item[$i]['item_id'] = $item_result[$i]['item_id'];
								$item[$i]['enq_id'] = $item_result[$i]['enq_id'];
								$item[$i]['item_name'] = $item_result[$i]['item_name'];
								$item[$i]['description'] = $item_result[$i]['description'];
								$item[$i]['quantity'] = $item_result[$i]['quantity'];
								$item[$i]['uom'] = $item_result[$i]['uom'];
							}
							$enq[$i]['itemList'] = $item;
						}
						$quot = "select * from ".self::quotTable. " where is_deleted = 0 AND enq_id=".$enq_id;
						$quot_result = $this->executeGenericDQLQuery($quot);

						$quotation = array();
						if($quot_result){
							$quotation['quot_id'] = $quot_result[0]['quot_id'];
							$quotation['enq_id'] = $quot_result[0]['enq_id'];
							$quotation['quot_date'] = $quot_result[0]['quot_date'];
							$quotation['ref_no'] = $quot_result[0]['ref_no'];
							$quot_id = $quot_result[0]['quot_id'];
							$po_query = "select * from ".self::poTable." where quot_id=".$quot_id;
							$po_result = $this->executeGenericDQLQuery($po_query);

							$po = array();
							if($po_result){
								$po['po_id'] = $po_result[0]['po_id'];
								$po['quot_id'] = $po_result[0]['quot_id'];
								$po['po_no'] = $po_result[0]['po_no'];
								$po['po_date'] = $po_result[0]['po_date'];
							}
							$enq[$i]['po'] = $po;
						}
						$enq[$i]['quotation'] = $quotation;
					}
					$data['enquiry'] = $enq;
					
				}
				$this->sendResponse(200,'success',$this->messages['dataFetched'],$data);
			}
		}
		public function getPoDetails(){
			$headers = apache_request_headers();
			if($headers['Accesstoken']){
				$po_id = $this->_request['id'];
				$sql = "select a.po_id, a.quot_id, a.po_no, a.po_date, b.enq_id, b.quot_date, b.ref_no as quot_ref, c.client_id, c.enq_date, c.due_date, c.ref_no as enq_ref, c.status, c.remarks, d.org_name,d.address, d.mobile, d.phone, d.email, d.contact_person, d.GSTN_No, d.web from purchase_order_table a INNER JOIN quotaion_table b ON a.quot_id = b.quot_id INNER JOIN enquiry_table c ON b.enq_id = c.enq_id INNER JOIN client_table d ON c.client_id = d.client_id where a.po_id=".$po_id;
				$rows = $this->executeGenericDQLQuery($sql);
				$data = array();
				if($rows){
					$data['po_id'] = $rows[0]['po_id'];
					$data['quot_id'] = $rows[0]['quot_id'];
					$data['po_no'] = $rows[0]['po_no'];
					$data['po_date'] = $rows[0]['po_date'];
					$data['enq_id'] = $rows[0]['enq_id'];
					$data['quot_date'] = $rows[0]['quot_date'];
					$data['quot_ref'] = $rows[0]['quot_ref'];
					$data['client_id'] = $rows[0]['client_id'];
					$data['enq_date'] = $rows[0]['enq_date'];
					$data['due_date'] = $rows[0]['due_date'];
					$data['enq_ref'] = $rows[0]['enq_ref'];
					$data['status'] = $rows[0]['status'];
					$data['remarks'] = $rows[0]['remarks'];
					$data['org_name'] = $rows[0]['org_name'];
					$data['mobile'] = $rows[0]['mobile'];
					$data['phone'] = $rows[0]['phone'];
					$data['email'] = $rows[0]['email'];
					$data['contact_person'] = $rows[0]['contact_person'];
					$data['gstn_no'] = $rows[0]['GSTN_No'];
					$data['web'] = $rows[0]['web'];
					$data['address'] = $rows[0]['address'];
					$query = "select a.po_details_id, a.po_id, a.quot_det_id, a.delivery_date, a.description, a.quantity, a.price, a.hsn_no, a.tax, a.tax_price, a.net_amount,a.item_code, a.unbilled_qty, a.po_sl_no, b.item_id, b.discount, c.item_name,c.uom from purchase_order_details_table a INNER JOIN  quotaion_details_table b ON a.quot_det_id = b.quot_det_id INNER JOIN enquiry_item_table c ON b.item_id = c.item_id where a.po_id=".$po_id;
					$result = $this->executeGenericDQLQuery($query);
					if($result){
						$po_item = array();
						$total_amount = 0;
						$tax_amount = 0;
						for($i = 0; $i < sizeof($result); $i++){
							$po_item[$i]['po_details_id'] = $result[$i]['po_details_id'];
							$po_item[$i]['quot_det_id'] = $result[$i]['quot_det_id'];
							$po_item[$i]['delivery_date'] = $result[$i]['delivery_date'];
							$po_item[$i]['description'] = $result[$i]['description'];
							$po_item[$i]['quantity'] = $result[$i]['quantity'];
							$po_item[$i]['price'] = (float)$result[$i]['price'];
							$po_item[$i]['hsn_no'] = $result[$i]['hsn_no'];
							$po_item[$i]['tax'] = (float)$result[$i]['tax'];
							$po_item[$i]['tax_price'] = (float)$result[$i]['tax_price'];
							$po_item[$i]['net_amount'] = (float)$result[$i]['net_amount'];
							$po_item[$i]['item_code'] = $result[$i]['item_code'];
							$po_item[$i]['item_id'] = $result[$i]['item_id'];
							$po_item[$i]['discount'] = (float)$result[$i]['discount'];
							$po_item[$i]['item_name'] = $result[$i]['item_name'];
							$po_item[$i]['unbilled_qty'] = $result[$i]['unbilled_qty'];
							$po_item[$i]['po_sl_no'] = $result[$i]['po_sl_no'];
							$po_item[$i]['uom'] = $result[$i]['uom'];
							$total_amount = $total_amount + ((float)$result[$i]['net_amount']);
							$tax_amount = $tax_amount + ((float)$result[$i]['tax_price']);
						}
						$data['itemList'] = $po_item;
						$data['total_ammount'] = $total_amount;
						$data['tax_ammount'] = $tax_amount;
						$data['grand_total'] = $tax_amount + $total_amount;
					}

					$this->sendResponse(200,'success',$this->messages['dataFetched'],$data);
				}
			}
		}
		public function poList(){
			$headers = apache_request_headers();
			if($headers['Accesstoken']){
				$query = "select a.po_id, a.quot_id, a.po_no, a.po_date, b.enq_id, b.quot_date, b.ref_no as quot_ref, c.client_id, c.enq_date, c.ref_no as enq_ref, c.status, c.remarks, d.org_name from purchase_order_table a INNER JOIN quotaion_table b ON a.quot_id = b.quot_id INNER JOIN enquiry_table c ON b.enq_id = c.enq_id INNER JOIN client_table d ON c.client_id = d.client_id";
				$rows = $this->executeGenericDQLQuery($query);
				$data = array();
				if($rows){
					for($i = 0; $i < sizeof($rows); $i++){
						$data[$i]['po_id'] = $rows[$i]['po_id'];
						$data[$i]['quot_id'] = $rows[$i]['quot_id'];
						$data[$i]['po_no'] = $rows[$i]['po_no'];
						$data[$i]['po_date'] = $rows[$i]['po_date'];
						$data[$i]['enq_id'] = $rows[$i]['enq_id'];
						$data[$i]['quot_date'] = $rows[$i]['quot_date'];
						$data[$i]['quot_ref'] = $rows[$i]['quot_ref'];
						$data[$i]['client_id'] = $rows[$i]['client_id'];
						$data[$i]['enq_date'] = $rows[$i]['enq_date'];
						$data[$i]['enq_ref'] = $rows[$i]['enq_ref'];
						$data[$i]['status'] = $rows[$i]['status'];
						$data[$i]['remarks'] = $rows[$i]['remarks'];
						$data[$i]['org_name'] = $rows[$i]['org_name'];
					}
					$this->sendResponse(200,'success',$this->messages['dataFetched'],$data);
				}
			}
			else{
				$this->sendResponse(404,'faild','Unauthorized user');
			}
		}
	}

	$api = new API;
	$api->processApi();
?>
