let isFilter = false;
let filters = {};
$(document).ready(function () {
  function getData(queryFilters = []) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    $.ajax({
      type: "POST",
      url: "log.php",
      data: {
        halaman: urlParams.get("page"),
        filter: queryFilters,
        src: queryFilters["source"],
        dst: queryFilters["destination"],
      },
      success: function (data) {
        $("#output").html(data);
      },
    });
  }

  if (isFilter) {
    getData(filters);
  } else {
    getData();
  }

  setInterval(function () {
    if (isFilter) {
      getData(filters);
    } else {
      getData();
    }
  }, 60000); // it will refresh your data every 10 sec

  $(".filter").on("submit", function (e) {
    e.preventDefault();
    isFilter = true;
    let src = $("#src").val();
    let dst = $("#dst").val();
    filters["source"] = src;
    filters["destination"] = dst;
    getData(filters);
  });

  $(".changePage").on("click", function (e) {
    e.preventDefault();
    let pageTo = $(this).attr("href");
    console.log(pageTo);
    console.log("changePage");
    console.log(filters["source"]);
    $.post("pagination.php", {
      src: filters["source"],
      dst: filters["destination"],
    });
    // window.location.href = pageTo;
  });
});
