<?php
$title = "Bookings";
ob_start();
require_once('../database/DbConnection.php');

$bookingID = isset($_GET['bookingID']) ? $_GET['bookingID'] : null;

$selectDetails = "SELECT p.PackageID, p.Title, a.StartDate, a.EndDate, b.BookingCode, b.TotalTraveller, b.BookingStatus, b.CreatedAt, pm.TotalPrice
                  FROM packages p
                  JOIN availability a ON p.PackageID = a.PackageID
                  JOIN bookings b ON a.AvailabilityID = b.AvailabilityID
                  JOIN payments pm ON b.BookingID = pm.BookingID
                  WHERE b.BookingID = ?";
$detailsRes = $connection->prepare($selectDetails);
$detailsRes->execute([$bookingID]);
$bookingDetails = $detailsRes->fetch(PDO::FETCH_ASSOC);
?>

<div class="container-fluid px-0">
    <div class="px-2 px-lg-5 mt-1">
        <div class="row justify-content-center gx-0">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="booking-receipt-card shadow-sm p-4 my-4 bg-white rounded border border-1" id="receiptCard">
                    <div class="text-center mb-3">
                        <h5 class="fw-bold mb-1">Booking Receipt</h5>
                        <span class="badge bg-primary text-white px-3 py-2" style="font-size:1em;">Show this receipt in person</span>
                    </div>
                    <div class="receipt-details">
                        <div class="mb-2"><span class="fw-semibold">Booking Code:</span> <span class="text-primary"><?= htmlspecialchars($bookingDetails['BookingCode'] ?? '-') ?></span></div>
                        <div class="mb-2"><span class="fw-semibold">Tour:</span> <a href="./PackageDetail.php?packageID=<?= $bookingDetails['PackageID'] ?>"><span class="text-black"><?= htmlspecialchars($bookingDetails['Title'] ?? '-') ?></span></a></div>
                        <div class="mb-2"><span class="fw-semibold">Start Date:</span> <span><?= htmlspecialchars(date('d-M-Y', strtotime($bookingDetails['StartDate'] ?? '-'))) ?></span></div>
                        <div class="mb-2"><span class="fw-semibold">End Date:</span> <span><?= htmlspecialchars(date('d-M-Y', strtotime($bookingDetails['EndDate'] ?? '-'))) ?></span></div>
                        <div class="mb-2"><span class="fw-semibold">Total Travellers:</span> <span><?= htmlspecialchars($bookingDetails['TotalTraveller'] ?? '-') ?></span></div>
                        <div class="mb-2"><span class="fw-semibold">Total Price:</span> <span class="text-success">฿<?= $bookingDetails['TotalPrice'] ?? 0 ?></span></div>
                        <div class="mb-2"><span class="fw-semibold">Booking Date:</span> <span><?= htmlspecialchars(date('d-M-Y', strtotime($bookingDetails['CreatedAt'] ?? '-'))) ?></span></div>
                        <div class="mb-2"><span class="fw-semibold">Booking Status:</span> <?php 
                        if($bookingDetails['BookingStatus'] == 'pending'){
                            echo '<span class="badge bg-primary">Pending</span>';
                        }
                        if($bookingDetails['BookingStatus'] == 'confirmed'){
                            echo '<span class="badge bg-success">Confirmed</span>';
                        }
                        if($bookingDetails['BookingStatus'] == 'cancelled'){
                            echo '<span class="badge bg-danger">Cancelled</span>';
                        }
                        ?> </div>
                    </div>
                    <hr>
                    <div class="text-center" style="font-size:0.95em;color:#888;">
                        Thank you for booking with WanderWay!<br>
                        Please present this receipt at check-in.<br>
                        (If your booking status is confirmed, please contact <span class="text-primary">+6665896411</span> for more details.)
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <button id="downloadReceiptBtn" class="btn btn-outline-primary download-receipt-btn" type="button">
                    Download Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<?php
// Add html2canvas for download functionality
echo '<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>';
echo '<script>
document.addEventListener("DOMContentLoaded", function() {
    var btn = document.getElementById("downloadReceiptBtn");
    if(btn) {
        btn.addEventListener("click", function() {
            var card = document.getElementById("receiptCard");
            html2canvas(card, { scale: 2 }).then(function(canvas) {
                var link = document.createElement("a");
                link.download = "booking-receipt.png";
                link.href = canvas.toDataURL();
                link.click();
            });
        });
    }
});
</script>';
$content = ob_get_clean();
include('./layout/master.php');
?>