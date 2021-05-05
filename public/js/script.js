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
    srcList = sourceList;
    dstList = destinationList;
    let page = $("#page").val();
    let src = $("#src").val();
    let dst = $("#dst").val();
    if (src !== "") {
      if (!validInput(src, srcList)) {
        src = "";
      }
    }
    if (dst !== "") {
      if (!validInput(dst, dstList)) {
        dst = "";
      }
    }
    let filters = `?page=${page}&src=${src}&dst=${dst}`;
    execute(filters);
  });

  $(".changePage").on("click", function (e) {
    e.preventDefault();
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    let nextPage = $(this).attr("aria-label");
    let src = urlParams.get("src");
    if (src !== "") {
      if (!validInput(src, srcList)) {
        src = "";
      }
    }
    let dst = urlParams.get("dst");
    if (dst !== "") {
      if (!validInput(dst, dstList)) {
        dst = "";
      }
    }
    let filters = `?page=${nextPage}&src=${src}&dst=${dst}`;
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

  function execute(get) {
    window.location.replace(`http://liondy/status/public/${get}`);
  }
});
