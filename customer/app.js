// slider
document.addEventListener("DOMContentLoaded", function () {
    const sliderTrack = document.querySelector(".slider-track");
    const slides = document.querySelectorAll(".slide");
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");

    let index = 0;
    let itemsPerView = getItemsPerView();
    
    function getItemsPerView() {
        if (window.innerWidth >= 1024) return 3;
        if (window.innerWidth >= 576) return 2;
        return 1;
    }

    function updateSlider() {
        const slideWidth = slides[0].offsetWidth;
        sliderTrack.style.transform = `translateX(-${index * slideWidth}px)`;
    }

    nextBtn.addEventListener("click", function () {
        if (index < slides.length - itemsPerView) {
            index++;
            updateSlider();
        }
    });

    prevBtn.addEventListener("click", function () {
        if (index > 0) {
            index--;
            updateSlider();
        }
    });

    window.addEventListener("resize", function () {
        itemsPerView = getItemsPerView();
        index = 0; // Reset position on resize
        updateSlider();
    });
});

// pagination
$(document).ready(function () {
    var cardsPerPage = 6;
    var cards = $(".package-card");
    var cardsCount = cards.length;
    var pagesCount = Math.ceil(cardsCount / cardsPerPage);
    var currentPage = 1;

    cards.hide();
    showPage(currentPage);

    $(".pagination").on("click", "a", function (e) {
        e.preventDefault();
        var page = $(this).text();

        if (page === ">") {
            page = currentPage + 1;
        } else if (page === "<") {
            page = currentPage - 1;
        } else {
            page = parseInt(page);
        }

        // Prevent going out of range
        if (page < 1) page = 1;
        if (page > pagesCount) page = pagesCount;

        currentPage = page;
        showPage(page);
    });

    function showPage(page) {
        var startCard = (page - 1) * cardsPerPage;
        var endCard = startCard + cardsPerPage;

        // Hide all cards and only show the cards for current page
        cards.hide().slice(startCard, endCard).show();

        // Generate pagination links
        var paginationHtml = '<li class="page-item"><a class="page-link" href="#">&lt;</a></li>'; // Previous button
        for (var i = 1; i <= pagesCount; i++) {
            paginationHtml += '<li class="page-item ' + (i === page ? "active" : "") + '"><a class="page-link" href="#">' + i + '</a></li>';
        }
        paginationHtml += '<li class="page-item"><a class="page-link" href="#">&gt;</a></li>'; // Next button

        // Insert pagination links into the pagination container
        $(".pagination").html(paginationHtml);
    }
});

$(document).ready(function () {
    var cardsPerPage = 8;
    var cards = $(".package-card-1");
    var cardsCount = cards.length;
    var pagesCount = Math.ceil(cardsCount / cardsPerPage);
    var currentPage = 1;

    cards.hide();
    showPage(currentPage);

    $(".pagination1").on("click", "a", function (e) {
        e.preventDefault();
        var page = $(this).text();

        if (page === ">") {
            page = currentPage + 1;
        } else if (page === "<") {
            page = currentPage - 1;
        } else {
            page = parseInt(page);
        }

        // Prevent going out of range
        if (page < 1) page = 1;
        if (page > pagesCount) page = pagesCount;

        currentPage = page;
        showPage(page);
    });

    function showPage(page) {
        var startCard = (page - 1) * cardsPerPage;
        var endCard = startCard + cardsPerPage;

        // Hide all cards and only show the cards for current page
        cards.hide().slice(startCard, endCard).show();

        // Generate pagination links
        var paginationHtml = '<li class="page-item"><a class="page-link" href="#">&lt;</a></li>'; // Previous button
        for (var i = 1; i <= pagesCount; i++) {
            paginationHtml += '<li class="page-item ' + (i === page ? "active" : "") + '"><a class="page-link" href="#">' + i + '</a></li>';
        }
        paginationHtml += '<li class="page-item"><a class="page-link" href="#">&gt;</a></li>'; // Next button

        // Insert pagination links into the pagination container
        $(".pagination1").html(paginationHtml);
    }
});

$(document).ready(function () {
    var cardsPerPage = 5;
    var cards = $(".availability-card");
    var cardsCount = cards.length;
    var pagesCount = Math.ceil(cardsCount / cardsPerPage);
    var currentPage = 1;

    cards.hide();
    showPage(currentPage);

    $(".pagination2").on("click", "a", function (e) {
        e.preventDefault();
        var page = $(this).text();

        if (page === ">") {
            page = currentPage + 1;
        } else if (page === "<") {
            page = currentPage - 1;
        } else {
            page = parseInt(page);
        }

        // Prevent going out of range
        if (page < 1) page = 1;
        if (page > pagesCount) page = pagesCount;

        currentPage = page;
        showPage(page);
    });

    function showPage(page) {
        var startCard = (page - 1) * cardsPerPage;
        var endCard = startCard + cardsPerPage;

        // Hide all cards and only show the cards for current page
        cards.hide().slice(startCard, endCard).show();

        // Generate pagination links
        var paginationHtml = '<li class="page-item"><a class="page-link" href="#">&lt;</a></li>'; // Previous button
        for (var i = 1; i <= pagesCount; i++) {
            paginationHtml += '<li class="page-item ' + (i === page ? "active" : "") + '"><a class="page-link" href="#">' + i + '</a></li>';
        }
        paginationHtml += '<li class="page-item"><a class="page-link" href="#">&gt;</a></li>'; // Next button

        // Insert pagination links into the pagination container
        $(".pagination2").html(paginationHtml);
    }
});

// Sorting
$(document).ready(function () {
    $(".sort-option").click(function (e) {
        e.preventDefault(); // Prevent default link behavior

        var sortValue = $(this).data("sort"); // Get sorting type

        var url = new URL(window.location.href); // Get current URL
        url.searchParams.set("sort", sortValue); // Add/Update 'sort' parameter

        window.location.href = url.toString(); // Redirect to updated URL
    });
});

// Filters
$(document).ready(function () {
    // Update displayed range values
    $("#priceRange").on("input", function () {
        $("#priceValue").text($(this).val());
    });

    $("#durationRange").on("input", function () {
        $("#durationValue").text($(this).val());
    });

    $("#sizeRange").on("input", function () {
        $("#sizeValue").text($(this).val());
    });

    // Apply filters when button is clicked
    $("#applyFilters").click(function () {
        var destination = $("#destinationSelect").val();
        var duration = $("#durationRange").val();
        var price = $("#priceRange").val();
        var size = $("#sizeRange").val();
        var orderBy = $("#sortSelect").val();

        // Reload page with selected filters
        var queryParams = `?destination=${destination}&duration=${duration}&price=${price}&size=${size}&orderBy=${orderBy}`;
        window.location.href = "Package.php" + queryParams;
    });
});



// Date of Birth
const daySelect = document.getElementById('day');
const monthSelect = document.getElementById('month');
const yearSelect = document.getElementById('year');

// Populate day options (1-31)
for (let i = 1; i <= 31; i++) {
  const option = document.createElement('option');
  option.value = i < 10 ? '0' + i : i;
  option.textContent = i < 10 ? '0' + i : i;
  daySelect.appendChild(option);
}

// Populate month options (1-12)
const months = [
  "Jan", "Feb", "Mar", "Apr", "May", "Jun",
  "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
];
months.forEach((month, index) => {
  const option = document.createElement('option');
  option.value = index + 1;
  option.textContent = month;
  monthSelect.appendChild(option);
});

// Populate year options (1900 - current year)
const currentYear = new Date().getFullYear();
for (let i = currentYear; i >= 1990; i--) {
  const option = document.createElement('option');
  option.value = i;
  option.textContent = i;
  yearSelect.appendChild(option);
}

 
const paymentBoxes = document.querySelectorAll('.payment-option');


// Show selected payment option and details on page load (for POST back)
paymentBoxes.forEach(box => {
    const radio = box.querySelector('input[type="radio"]');
    const details = box.querySelector('.payment-details');
    if (radio.checked) {
        box.classList.add('selected');
        if (details) details.style.display = 'block';
    } else {
        box.classList.remove('selected');
        if (details) details.style.display = 'none';
    }

    box.addEventListener('click', () => {
        // Unselect all first
        paymentBoxes.forEach(b => {
            b.classList.remove('selected');
            b.querySelector('input[type="radio"]').checked = false;
            b.querySelector('.payment-details').style.display = 'none';
        });

        // Select current
        box.classList.add('selected');
        radio.checked = true;
        if (details) details.style.display = 'block';
    });
});

// review image
function loadFile(event, outputID){
    var output = document.getElementById(outputID);
    if (event.target.files && event.target.files[0]) {
        var reader = new FileReader();
        reader.onload = function(){
            output.src = reader.result;
            output.style.display = "block";
        }
        reader.readAsDataURL(event.target.files[0]);
    } else {
        output.src = "";
        output.style.display = "none";
    }
}

// booking code
$(document).ready(function(){
    var bookingCode = "WW"+Math.floor(Math.random() * 100000000);

    $("#bookingCode").val(bookingCode);
});

