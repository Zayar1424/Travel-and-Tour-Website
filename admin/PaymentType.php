<?php
$title = "Tour Guides";
ob_start();
session_start();
require_once('../database/DbConnection.php');

if(isset($_POST['btnAddPaymentType'])){
$validation = [
    'typeNameStatus' => false,
    'duplicateTypeNameStatus' => false,
    'accountNameStatus' => false,
    'accountNumberStatus' => false,
    'accountNumberCheck' => false,
];

$validation['typeNameStatus'] = $_POST['typeName'] == "" ? true:false;
$validation['accountNameStatus'] = $_POST['accountName'] == "" ? true:false;
$validation['accountNumberStatus'] = $_POST['accountNumber'] == "" ? true:false;
$validation['accountNumberCheck'] = $_POST['accountNumber'] < 0 || !ctype_digit($_POST['accountNumber']) ? true:false;

$completeAccountNumber = false;

$typeName = $_POST['typeName'];
    
$checkTypeName = "SELECT COUNT(*) FROM payment_types WHERE TypeName=?";
$checkRes = $connection -> prepare($checkTypeName);
$checkRes -> execute([$typeName]);
$typeNameCount = $checkRes -> fetchColumn();

$validation['duplicateTypeNameStatus'] = $typeNameCount>0 ? true:false;
}
?>

<div class="container-fluid overflow-auto container-scroll">
    <div class="row align-items-start mb-4">
        <!-- Left Form Start -->
        <div class="col-5">
            <div class="card mt-5 shadow-sm input-form">
                <div class="card-body">
                    <form action="PaymentType.php" method="POST">
                        <div class="row mt-2">
                            <div class="col-5">
                                <p>Payment Type</p>
                            </div>
                            <div class="col">
                                <input type="text" name="typeName" id="" class="form-control bg-light" placeholder="Enter Payment Type" value="<?php echo $_POST['typeName'] ?? ""; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPaymentType'])){
                                if($validation['typeNameStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-5"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Payment type is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                    
    
                                if($validation['duplicateTypeNameStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-5"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Payment type already exists!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                
                            }
                        ?>
                        
        
                        <div class="row mt-2">
                            <div class="col-5">
                                <p>Account Name</p>
                            </div>
                            <div class="col">
                            <input type="text" name="accountName" id="" class="form-control bg-light" placeholder="Enter Account Name" value="<?php echo $_POST['accountName'] ?? ""; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPaymentType'])){
                                if($validation['accountNameStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-5"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Account name is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-5">
                                <p>Account Number</p>
                            </div>
                            <div class="col">
                            <input type="text" name="accountNumber" id="" class="form-control bg-light" placeholder="Enter Account Number" value="<?php echo $_POST['accountNumber'] ?? ""; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPaymentType'])){
                                if($validation['accountNumberStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-5"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Account number is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                else if($validation['accountNumberCheck']){
                                    echo '
                                    <div class="row">
                                        <div class="col-5"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Please enter a valid number!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                else{
                                    $completeAccountNumber = true;
                                }
                            }
                        ?>


                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" name="btnAddPaymentType" class="btn btn-primary btn-sm fs-6" style="width: 20%;">Add</button>
                        </div>

                        <?php
                        if(isset($_POST['btnAddPaymentType'])){
                            if(!$validation['accountNameStatus'] && !$validation['accountNumberStatus'] && !$validation['accountNumberCheck'] && !$validation['duplicateTypeNameStatus'] && $completeAccountNumber){
                                $typeName = $_POST['typeName'];
                                $accountName = $_POST['accountName'];
                                $accountNumber = $_POST['accountNumber'];
                                
                                $insertAccountType = "INSERT INTO payment_types(TypeName,AccountName,AccountNumber) VALUE(?,?,?)";
                                $insertRes = $connection -> prepare($insertAccountType);
                                $insertRes -> execute([$typeName,$accountName,$accountNumber]);
                            
                                echo "<script>
                                            Swal.fire({
                                                title: 'Done!',
                                                text: 'New payment type is added successfully.',
                                                icon: 'success',
                                                confirmButtonText: 'OK'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href = './PaymentType.php';
                                                }
                                            });
                                        </script>";
                            }
                        }
                        ?>
                        
                    </form>
                    
                </div>
            </div>
        </div>
        <!-- Left Form End -->
        <!-- Right Table Start -->
        <div class="col d-flex justify-content-end">
            <div class="card mt-4 table-type table-container">
                <div class="card-body">
                    <h5>Payment Type List</h5>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Account Name</th>
                                <th>Account Number</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $selectPaymentType = "SELECT * FROM payment_types";
                            $selectRes = $connection -> prepare($selectPaymentType);
                            $selectRes -> execute();
                            $paymentTypes = $selectRes->fetchAll(PDO::FETCH_ASSOC);

                            foreach($paymentTypes as $item){
                                $ID = $item['PaymentTypeID'];
                                $paymentTypeName = $item['TypeName'];
                                $accName = $item['AccountName'];
                                $accNumber = $item['AccountNumber'];
                                echo "
                                    <tr>
                                        <td>$paymentTypeName</td>
                                        <td>$accName</td>
                                        <td>$accNumber</td>
                                        <td class='text-end'><a href='DeletePaymentType.php?paymentTypeID=$ID' class='btn btn-sm btn-danger'><i class='fa-solid fa-trash'></i></a></td>
                                    </tr>
                                ";
                            }
                        ?>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="#"><</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Right Table End -->
    </div>
</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>