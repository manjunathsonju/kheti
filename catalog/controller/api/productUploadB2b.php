


<?php
class ControllerApiProductUploadB2b extends Controller
{
    public function index()
    {
        if($_FILES["photo"]){
            $target_dir = "/image/b2bProductsUpload/";
            $target_file = $target_dir . basename($_FILES["photo"]["name"]);

        if( move_uploaded_file($_FILES["photo"]["name"], $target_file)){
            var_dump('sucess');

        }
        // $filelocation = 'http://khetifood.com/image/b2bProductsUpload/'.$_FILES["photo"]["name"];
    }else{
        
        var_dump('no');

    }

    }
}




///
// <?php
//     $connect_to_db = mysqli_connect('localhost', 'user', 'pass', 'db');

//     $user = $_POST['user'];

//     $allowedExts = array("gif", "jpeg", "jpg", "png");
//     $temp = explode(".", $_FILES["file"]["name"]);
//     $extension = end($temp);

//     if ((($_FILES["file"]["type"] == "image/gif")
//     || ($_FILES["file"]["type"] == "image/jpeg")
//     || ($_FILES["file"]["type"] == "image/jpg")
//     || ($_FILES["file"]["type"] == "image/pjpeg")
//     || ($_FILES["file"]["type"] == "image/x-png")
//     || ($_FILES["file"]["type"] == "image/png"))
//     && in_array($extension, $allowedExts)) {

//       if ($_FILES["file"]["error"] > 0) {

//         echo json_encode(array('status' => 'error', 'msg' => 'File could not be uploaded.'));

//       } else {

//         //Move the file to the uploads folder
//         move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/" . $_FILES["file"]["name"]);

//         //Get the File Location
//         $filelocation = 'http://yourdomain.com/uploads/'.$_FILES["file"]["name"];

//         //Get the File Size
//         $size = ($_FILES["file"]["size"]/1024).' kB';
//          //Save to your Database
//          mysqli_query($connect_to_db, "INSERT INTO images (user, filelocation, size) VALUES ('$user', '$filelocation', '$size')");

//          //Return the data in JSON format
//          echo json_encode(array('status' => 'success', 'data' => array('filelocation' => $filelocation, 'size' => $size)));
//        }
//      } else {
//        //File type was invalid, so throw up a red flag!
//        echo json_encode(array('status' => 'error', 'msg' => 'Invalid File Format'));
//      }

// public static function processRequest()
// {
//     case 'post':
//        if(move_uploaded_file($_FILES["uploaded_file"]["tmp_name"] , "Images/" . $_FILES["uploaded_file"]["name"] )){
//                     echo "move_uploaded_file SUCCESS ";
//     ......................................                
// }
// ......................................           
// protected function executePost ($ch)
// {
//     $tmpfile = $_FILES['image1']['tmp_name'];
//     $filename = basename($_FILES['image1']['name']);

//     $data = array(
//         'uploaded_file' => '@' . $tmpfile . ';filename='.$filename,
//     );
//     curl_setopt($ch, CURLOPT_POST, 1);             
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
//     //no need httpheaders
//     $this->doExecute($ch); 
// } 