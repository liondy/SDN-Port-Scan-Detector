let isFilter = false;
let srcList = [];
let dstList = [];
$(document).ready(function () {
  // function getData(queryFilters = []) {
  //   const queryString = window.location.search;
  //   const urlParams = new URLSearchParams(queryString);
  //   $.ajax({
  //     type: "POST",
  //     url: "log.php",
  //     data: {
  //       halaman: urlParams.get("page"),
  //       filter: queryFilters,
  //       src: queryFilters["source"],
  //       dst: queryFilters["destination"],
  //     },
  //     success: function (data) {
  //       $("#output").html(data);
  //     },
  //   });
  // }

  // if (isFilter) {
  //   getData(filters);
  // } else {
  //   getData();
  // }

  // setInterval(function () {
  //   if (isFilter) {
  //     getData(filters);
  //   } else {
  //     getData();
  //   }
  // }, 60000); // it will refresh your data every 10 sec

  $(".filter").on("submit", function (e) {
    e.preventDefault();
    let sourceList = Object.values(JSON.parse($("#srcLst").val()));
    let destinationList = Object.values(JSON.parse($("#dstLst").val()));
    let totalHalaman = Object.values(JSON.parse($("#total_halaman").val()));
    srcList = sourceList;
    dstList = destinationList;
    console.log(dstList);
    let page = $("#page").val();
    if (page > totalHalaman) {
      page = 1;
    }
    let src = $("#src").val();
    let dst = $("#dst").val();
    let filters = `?page=${page}`;
    if (src !== "") {
      if (validInput(src, srcList)) {
        filters += `&src=${src}`;
      }
    }
    if (dst !== "") {
      if (validInput(dst, dstList)) {
        filters += `&dst=${dst}`;
      }
    }
    execute(filters);
  });

  $(".changePage").on("click", function (e) {
    e.preventDefault();
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    let sourceList = Object.values(JSON.parse($("#srcLst").val()));
    let destinationList = Object.values(JSON.parse($("#dstLst").val()));
    let nextPage = $(this).attr("aria-label");
    let filters = `?page=${nextPage}`;
    let src = urlParams.get("src");
    if (src !== "") {
      if (validInput(src, sourceList)) {
        filters += `&src=${src}`;
      }
    }
    let dst = urlParams.get("dst");
    if (dst !== "") {
      if (validInput(dst, destinationList)) {
        filters += `&dst=${dst}`;
      }
    }
    execute(filters);
  });

  function validInput(input, list) {
    let valid = false;
    list.every((ip) => {
      if (ip === input) {
        valid = true;
        return false;
      }
      return true;
    });
    return valid;
  }

  function execute(filter) {
    window.location.replace(`http://liondy/status/public/${filter}`);
  }
});
