<?php
Class ControllerApiViewClickTracks extends Controller{
    public function index(){
        if($_GET['secret'] == "U7sw3jr69r13"){   
            $tracks=$this->db->query("SELECT * FROM `click_tracker` where track_type='".$_GET['track_type']."'");

            foreach ($tracks->rows as $result){
                $tracks1[]=array(
                    'platform'=>$result['platform'],
                    'track_type'=>$result['track_type'],
                    'clicks'=>$result['clicks'],
                    'track_date'=>$result['track_date']
                );
            }
            
             

            echo('<!DOCTYPE html>
            <html lang="en">
            <head>
              <title>Clicks</title>
              <meta charset="utf-8">
              <meta name="viewport" content="width=device-width, initial-scale=1">
              <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
              <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
              <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
            </head>
            <body>
            <a href="https://khetifood.com/track.html"><button type="button" class="btn btn-danger">Back</button></a>
            
            <div class="container"><h2>Clicks/visits of '.$tracks1[0]['track_type'].'</h2>
  
  
            
             <div id="buttons">  
              </div>
          <br> <hr>
              <table class="table table-striped" id="clicktable">
                  <thead>
                    <tr>
                      <th scope="col">Platform</th>
                      <th scope="col">Module name</th>
                      <th scope="col">Clicks/Visits</th>
                      <th scope="col">Track Date</th>

                    </tr>
                  </thead>
                  <tbody>');

                  for($j = 0; $j<sizeof($tracks1) ;$j++){
                    echo ('<tr>
                   
                    <td>'.$tracks1[$j]['platform'].'</td>
                    <td>'.$tracks1[$j]['track_type'].'</td>
                    <td>'.$tracks1[$j]['clicks'].'</td>
                    <td>'.$tracks1[$j]['track_date'].'</td>
                  </tr>');
                  }
                    
                    echo(' 
                    </tbody>
                  </table>
            
            </body>
            </html>');
                

            // $json['tracks1']= $tracks1;
            // $this->response->addHeader('Content-Type: application/json');
            // $this->response->addHeader('Access-Control-Allow-Origin: *');
            // $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
            //  $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
            // $this->response->addHeader('Access-Control-Max-Age: 600');
            // $this->response->setOutput(json_encode($json));



        }

    }
    public function getbtns(){
        $tracksbtns=$this->db->query("SELECT DISTINCT track_type FROM click_tracker where platform ='".'web'."'");
        //  var_dump($tracksbtns);

        foreach ($tracksbtns->rows as $result){
            $tracksbtns1[]=array(
                'track_type'=>$result['track_type']
            );
        }
        // var_dump($tracksbtns1);

            $json['tracksbtns1']= $tracksbtns1;
            $this->response->addHeader('Content-Type: application/json');
            $this->response->addHeader('Access-Control-Allow-Origin: *');
            $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
            $this->response->addHeader('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
            $this->response->addHeader('Access-Control-Max-Age: 600');
            $this->response->setOutput(json_encode($json));

   
    }
}