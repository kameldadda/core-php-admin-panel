<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

// Costumers class
require_once BASE_PATH . '/lib/group/group.php';
$group = new groups();

// Get Input data from query string
$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');
$order_by = filter_input(INPUT_GET, 'order_by');

// Per page limit for pagination.
$pagelimit = 15;

// Get current page.
$page = filter_input(INPUT_GET, 'page');
if (!$page) {
	$page = 1;
}

// If filter types are not selected we show latest added data first
if (!$filter_col) {
	$filter_col = 'id';
}
if (!$order_by) {
	$order_by = 'Desc';
}

//Get DB instance. i.e instance of MYSQLiDB Library
$db = getDbInstance();

//Start building query according to input parameters.
// If search string
if ($search_string) {
	$db->where('groupname', '%' . $search_string . '%', 'like');
}

//If order by option selected
if ($order_by) {
	$db->orderBy($filter_col, $order_by);
}

// Set pagination limit
$db->pageLimit = $pagelimit;

// Get result of the query.
$db->setQueryOption('DISTINCT');
//$Grows = $db->arraybuilder()->paginate('radgroupcheck', $page);
//$total_pages = $db->totalPages;
$Grows = $db->arraybuilder()->get('radgroupcheck',null,'groupname');
$rows = array();
foreach ($Grows as $Grow) {
$G['groupname']= $Grow['groupname'];
    $db = getDbInstance();
$db->where('groupname', $G['groupname'], 'like');
$db->where('attribute', '%Total-Bytes%', 'like');
$res = $db->getOne('radgroupcheck');
$G['quota']= $res['value'];
$db = getDbInstance();
$db->where('groupname', $G['groupname'], 'like');
$db->where('attribute', '%Reset-Type%', 'like');
$res = $db->getOne('radgroupcheck');
$G['period']= $res['value'];
$rows[]=$G;
}


include BASE_PATH . '/includes/header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">accounts</h1>
        </div>
        <div class="col-lg-6">
            <div class="page-action-links text-right">
                <a href="add_customer.php?operation=create" class="btn btn-success"><i
                        class="glyphicon glyphicon-plus"></i> Add new</a>
            </div>
        </div>
    </div>
    <?php include BASE_PATH . '/includes/flash_messages.php';?>

    <!-- Filters 

    <div id="export-section">
        <a href="export_customers.php"><button class="btn btn-sm btn-primary">Export to CSV <i
                    class="glyphicon glyphicon-export"></i></button></a>
        <a href="export_PDF.php"><button class="btn btn-sm btn-primary">Export to PDF <i
                    class="glyphicon glyphicon-print"></i></button></a>
    </div> -->

    <!-- Table -->
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="45%">groupname</th>
                <th width="20%">Quota</th>
                <th width="20%">Reset period</th>
                <th width="15%">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo $row['groupname']; ?></td>
                <td><?php echo xss_clean($row['quota']) ; ?></td>
                <td><?php echo xss_clean($row['period']); ?></td>
                <td>
                    <a href="edit_customer.php?customer_id=<?php echo $row['groupname']; ?>&operation=edit"
                        class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i></a>
                    <a href="#" class="btn btn-danger delete_btn" data-toggle="modal"
                        data-target="#confirm-delete-<?php echo $row['groupname']; ?>"><i
                            class="glyphicon glyphicon-trash"></i></a>
                </td>
            </tr>
            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="confirm-delete-<?php echo $row['groupname']; ?>" role="dialog">
                <div class="modal-dialog">
                    <form action="delete_customer.php" method="POST">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Confirm</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="del_id" id="del_id" value="<?php echo $row['groupname']; ?>">
                                <p>Are you sure you want to delete: <?php echo $row['groupname']; ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-default pull-left">Yes</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- //Delete Confirmation Modal -->
            <?php endforeach;?>
        </tbody>
    </table>
    <!-- //Table -->

</div>
<!-- //Main container -->
<?php include BASE_PATH . '/includes/footer.php';?>