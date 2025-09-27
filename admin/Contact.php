<?php
$title = "Contacts";
ob_start();
require_once('../database/DbConnection.php');

// Fetch contact data
$selectContact = "SELECT * FROM contacts ORDER BY ContactID DESC";
$contactSelectRes = $connection -> prepare($selectContact);
$contactSelectRes -> execute();
$contacts = $contactSelectRes -> fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid overflow-auto container-scroll">
    <div class="row mt-4 align-items-start mb-4">
        <div class="col">
            <div class="card mt-2 table-contact table-container">
                    <div class="card-body">
                        <h5>Contact List</h5>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php

                                foreach($contacts as $item){
                                    $name = $item['Name'];
                                    $email = $item['Email'];
                                    $message = $item['Message'];
                                    echo "
                                        <tr>
                                            <td>$name</td>
                                            <td>$email</td>
                                            <td>$message</td>
                                        </tr>";
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
    </div>
</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>