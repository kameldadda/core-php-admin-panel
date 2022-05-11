<?php
session_start();
require_once './config/config.php';
require_once './includes/auth_validate.php';


//serve POST method, After successful insert, redirect to customers.php page.
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    //Mass Insert Data. Keep "name" attribute in html form same as column name in mysql table.
    $data_to_store = array_filter($_POST);
    unset($data_to_store['group']);
    
  // $columns = array('username','attribute','op','value');
   //$data_to_store = array($_POST['username'],'CleartextPassword',':=',$_POST['password']);
    //Insert timestamp
   // $data_to_store['created_at'] = date('Y-m-d H:i:s');
    $db = getDbInstance();
    
    $last_id1 = $db->insert('radcheck',$data_to_store);
    if (!($_POST['group']===" ")){
        $group_to_store['groupname']=$_POST['group'];
        $group_to_store['username']=$_POST['username'];
        $group_to_store['priority']=10;

        $last_id2 = $db->insert('radusergroup',$group_to_store);  
    }else{
        $last_id2= true ;
    }
    

    if($last_id1 && $last_id2)
    {
    	$_SESSION['success'] = "Customer added successfully!";
    	header('location: customers.php');
    	exit();
    }
    else
    {
        echo 'insert failed: ' . $db->getLastError();
        exit();
    }
}

//We are using same form for adding and editing. This is a create form so declare $edit = false.
$edit = false;

require_once 'includes/header.php'; 
?>
<div id="page-wrapper">
<div class="row">
     <div class="col-lg-12">
            <h2 class="page-header">Add</h2>
        </div>
        
</div>
    <form class="form" action="" method="post"  id="customer_form" enctype="multipart/form-data">
       <?php  include_once('./forms/customer_form.php'); ?>
    </form>
</div>


<script type="text/javascript">
$(document).ready(function(){
   $("#customer_form").validate({
       rules: {
            username: {
                required: true,
                minlength: 3
            },   
        }
    });
});
</script>

<?php include_once 'includes/footer.php'; ?>