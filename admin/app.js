// review image
function loadFile(event, outputID){
    var reader = new FileReader();

    reader.onload = function(){
        var output = document.getElementById(outputID);
        output.src = reader.result;
    }

    reader.readAsDataURL(event.target.files[0]);
}

// pagination
$(document).ready(function () {
    $(".table-container").each(function () {
        var container = $(this);
        var rowsPerPage = 6;
        var rows = container.find("table tbody tr");
        var rowsCount = rows.length;
        var pagesCount = Math.ceil(rowsCount / rowsPerPage);
        var currentPage = 1;

        function showPage(page) {
            var start = (page - 1) * rowsPerPage;
            var end = start + rowsPerPage;
            rows.hide().slice(start, end).show();

            var pagination = container.find(".pagination");
            pagination.html('<li class="page-item"><a class="page-link" href="#"><</a></li>');

            for (var i = 1; i <= pagesCount; i++) {
                pagination.append(
                    '<li class="page-item ' + (i === page ? "active" : "") + '">' +
                    '<a class="page-link" href="#">' + i + '</a></li>'
                );
            }

            pagination.append('<li class="page-item"><a class="page-link" href="#">></a></li>');
        }

        container.on("click", ".pagination a", function (e) {
            e.preventDefault();
            var pageText = $(this).text().trim();
            var page = pageText === ">" ? currentPage + 1 :
                pageText === "<" ? currentPage - 1 :
                parseInt(pageText);

            if (page < 1) page = 1;
            if (page > pagesCount) page = pagesCount;

            currentPage = page;
            showPage(page);
        });

        showPage(currentPage);
    });
});

// date picker
$(document).ready(function(){
    $(".datepicker").datepicker({
        dateFormat: "yy-mm-dd" // Customize format (YYYY-MM-DD)
    });
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