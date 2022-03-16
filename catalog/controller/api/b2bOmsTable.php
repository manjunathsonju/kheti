<?php
class ControllerApiB2bOmsTable extends Controller {
	public function index() {
        if($this->request->post['key']=='G3tT@8130ws'){
        $sql ="SELECT * FROM b2b_oms_tables WHERE customer_id='".$this->request->post[customer_id]."' AND status!=0"; //and status!=0
        $tablearr=$this->db->query($sql);
        foreach ($tablearr->rows as $result){
            $sql="SELECT * FROM b2b_oms_orders WHERE table_id='".$result['table_id']."' AND complete='0' AND customer_id='".$this->request->post[customer_id]."'";
            $engage=$this->db->query($sql);
            if($engage->num_rows>0){
                $table_engage=1;
            }else{
                $table_engage=0;
            }
            if($result['status']=='2'){
                $sql ="SELECT * FROM `b2b_oms_table_merge` WHERE table_id='".$result['table_id']."'";
            $mergetables=$this->db->query($sql);
            $sql ="SELECT * FROM b2b_oms_tables WHERE table_id='".$mergetables->row['merge_table_id']."'"; //and status!=0
            $table_cap_merge=$this->db->query($sql);
            $table_capacity=(int)$result['table_capacity']+(int)$table_cap_merge->row['table_capacity'];


            }else{
                $table_capacity=$result['table_capacity'];
            }

            // if condition if status=2 i.e merged
            $table_total=$engage->row[total];
            $tablearray[]=array(
                'table_id'=>$result['table_id'],
                'customer_id'=>$result['customer_id'],
                'table_name'=>$result['table_name'], //this also
                'table_capacity'=>$table_capacity, //this should come from if else condition
                'table_engage' =>$table_engage,
                'total' =>round($table_total,2)
            );
        
        }
        $json['tablearray']=$tablearray;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        $this->response->addHeader('Access-Control-Max-Age: 600');
        $this->response->setOutput(json_encode($json));
        }

    }
    public function addTable() {
         if($this->request->post['key'] == "1202@dDTb13#"){
            $sql1 ="SELECT * FROM b2b_oms_tables WHERE customer_id='".$this->request->post[customer_id]."' AND table_name='".$this->request->post[table_name]."'";
            $new_Name_exits=$this->db->query($sql1);
            if($new_Name_exits->num_rows!='0'){
             $json['error']='Name already exists!';
            }else{
                $sql ="INSERT INTO b2b_oms_tables (customer_id,table_name,table_capacity) VALUES ('".$this->request->post[customer_id]."','".$this->request->post[table_name]."','".$this->request->post[table_capacity]."')";
                $this->db->query($sql);
                $json['success']=1;
            }
          
            $this->response->addHeader('Content-Type: application/json');
            $this->response->addHeader('Access-Control-Allow-Origin: *');
            $this->response->addHeader('Access-Control-Allow-Headers: *');
            $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
            $this->response->addHeader('Access-Control-Max-Age: 600');
            $this->response->setOutput(json_encode($json));
         }
      
    }
    public function deleteTable() {
        if($this->request->post['key'] == "1202DlTTb13"){ 
           $sql ="DELETE FROM `b2b_oms_tables` WHERE table_id='".$this->request->post['table_id']."'";
           $this->db->query($sql);
           $json['success']=1;
           $this->response->addHeader('Content-Type: application/json');
           $this->response->addHeader('Access-Control-Allow-Origin: *');
           $this->response->addHeader('Access-Control-Allow-Headers: *');
           $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
           $this->response->addHeader('Access-Control-Max-Age: 600');
           $this->response->setOutput(json_encode($json));
   
        }
     
   }

   public function editTable() {
    if($this->request->post['key'] == "12023D!TTb13"){ 
        $sql1 ="SELECT * FROM b2b_oms_tables WHERE customer_id='".$this->request->post[customer_id]."' AND table_name='".$this->request->post[new_name]."' AND table_id!='".$this->request->post[table_id]."'";
       $new_Name_exits=$this->db->query($sql1);
       if($new_Name_exits->num_rows!='0'){
        $json['error']='name already exists!';
       }else{
        //    var_dump('ok');
        if(isset($this->request->post[table_capacity])&&(!isset($this->request->post[new_name]))){
            $sql11 ="UPDATE b2b_oms_tables SET table_capacity='".$this->request->post[table_capacity]."' WHERE table_id='".$this->request->post[table_id]."'";
            
        }elseif(isset($this->request->post[new_name])&&(!isset($this->request->post[table_capacity]))){
            $sql11 ="UPDATE b2b_oms_tables SET table_name='".$this->request->post[new_name]."' WHERE table_id='".$this->request->post[table_id]."'";

        }elseif(isset($this->request->post[new_name])&&isset($this->request->post[table_capacity])){
            $sql11 ="UPDATE b2b_oms_tables SET table_name='".$this->request->post[new_name]."', table_capacity='".$this->request->post[table_capacity]."' WHERE table_id='".$this->request->post[table_id]."'";
        }

        $insertsql=$this->db->query($sql11);      
            $json['success']=1;
       }

      //     $sql ="UPDATE `b2b_oms_tables` SET	table_name='".$this->request->post['new_name']."' WHERE table_id='".$this->request->post['table_id']."'";
       //    $this->db->query($sql);
   
       $this->response->addHeader('Content-Type: application/json');
       $this->response->addHeader('Access-Control-Allow-Origin: *');
       $this->response->addHeader('Access-Control-Allow-Headers: *');
       $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
       $this->response->addHeader('Access-Control-Max-Age: 600');
       $this->response->setOutput(json_encode($json));
    }
 
}

public function getAvailableTable() {
    if($this->request->post['key'] == "@vA!la8l3t@8l3om3"){ 
        $sql ="SELECT * FROM `b2b_oms_orders` WHERE customer_id='".$this->request->post[customer_id]."' AND complete=0";
        $running_table=$this->db->query($sql);
        $running_tables=array();
        foreach ($running_table->rows as $result){
            array_push($running_tables, $result['table_id']);
            }

            $sql ="SELECT * FROM b2b_oms_tables WHERE customer_id='".$this->request->post[customer_id]."' AND status=0";
            $inactivetables=$this->db->query($sql);
            foreach ($inactivetables->rows as $result){
                array_push($running_tables, $result['table_id']);
                }
            
            // var_dump($running_tables);  

            // var_dump(sizeof($running_tables));
            if(sizeof($running_tables)==0){
                $sql ="SELECT * FROM `b2b_oms_tables` WHERE customer_id='".$this->request->post[customer_id]."'";

            }else{
                $sql ="SELECT * FROM `b2b_oms_tables` WHERE customer_id='".$this->request->post[customer_id]."' AND table_id NOT IN (".implode(',', $running_tables).")";

            }
        $available_table=$this->db->query($sql);  
        // var_dump($available_table);  
        if($available_table->num_rows==0){
            $json['available_tables']=[];
        }else{
            foreach ($available_table->rows as $result){
                $available_tables[]=array(
                    'table_id'=>$result['table_id'],
                    'table_name'=>$result['table_name'],
                    'table_capacity'=>$result['table_capacity']
                    
                );
            
                }
                $json['available_tables']=$available_tables;

        }

    }
    $this->response->addHeader('Content-Type: application/json');
    $this->response->addHeader('Access-Control-Allow-Origin: *');
    $this->response->addHeader('Access-Control-Allow-Headers: *');
    $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    $this->response->addHeader('Access-Control-Max-Age: 600');
    $this->response->setOutput(json_encode($json));
}

public function swipeTable() {
    if($this->request->post['key'] == "5w!p3t@8l3om3"){ 
        $sql="SELECT * FROM b2b_oms_orders WHERE table_id='".$this->request->post['table_id']."' AND complete=0";
        $runorder=$this->db->query($sql);

        if($runorder->num_rows!=0){
        $sql="UPDATE b2b_oms_orders SET table_id='".$this->request->post['new_table_id']."' WHERE table_id='".$this->request->post['table_id']."' AND complete=0";
        $this->db->query($sql);
        $json['success']='done';  
        }else{
            $json['error']='No Running order in this table';
        }

    }
    $this->response->addHeader('Content-Type: application/json');
    $this->response->addHeader('Access-Control-Allow-Origin: *');
    $this->response->addHeader('Access-Control-Allow-Headers: *');
    $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    $this->response->addHeader('Access-Control-Max-Age: 600');
    $this->response->setOutput(json_encode($json));
}


public function mergeTable() {

    if($this->request->post['key'] == "m3r93t@8l3om3"){ 
    
        // table id and merge_table_id
        // set status of table_id =2 i.e merged , status of merge_table_id=0 and  add table_id merged with merge_table_id in table oms_table_merge

        $sql="UPDATE `b2b_oms_tables` SET status=0 WHERE table_id='".$this->request->post['merge_table_id']."'"; 
        $this->db->query($sql);

        $sql="UPDATE `b2b_oms_tables` SET status=2 WHERE table_id='".$this->request->post['table_id']."'";
        $this->db->query($sql);

        $sql="INSERT INTO `b2b_oms_table_merge` (table_id,merge_table_id) VALUES ('".$this->request->post['table_id']."','".$this->request->post['merge_table_id']."')";
        $this->db->query($sql);
        $json['success']=1;


    }else{
        $json['error']='Invalid Api Key';

    }
    $this->response->addHeader('Content-Type: application/json');
    $this->response->addHeader('Access-Control-Allow-Origin: *');
    $this->response->addHeader('Access-Control-Allow-Headers: *');
    $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    $this->response->addHeader('Access-Control-Max-Age: 600');
    $this->response->setOutput(json_encode($json));
}

}