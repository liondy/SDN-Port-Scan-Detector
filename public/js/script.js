let isFilter = false;
let srcList = [];
let dstList = [];
const months = [
  "Jan",
  "Feb",
  "Mar",
  "Apr",
  "May",
  "Jun",
  "Jul",
  "Aug",
  "Sep",
  "Oct",
  "Nov",
  "Dec",
];
$(document).ready(function () {
  let jumlah_data = $("#jumlah_data").val();
  function getData() {
    $.ajax({
      method: "post",
      url: "http://liondy/status/public/",
      data: {
        controller: "home",
        method: "reload",
      },
      type: "text",
      success: function (data) {
        if (parseInt(data) > parseInt(jumlah_data)) {
          $("#liveToast").removeClass("hide");
          $("#liveToast").addClass("show");
        }
      },
    });
  }

  setInterval(function () {
    getData();
  }, 10000); // it will refresh your data every 10 sec

  $(".reload").on("click", function (e) {
    execute("");
  });

  $("#dismiss-toast").on("click", function (e) {
    $("#liveToast").removeClass("show");
    $("#liveToast").addClass("hide");
  });

  $("#tcpcheckbox").on("click", function (e) {
    if ($("#tcpcheckbox").prop("checked")) {
      if ($("#udpcheckbox").prop("checked")) {
        $(".protokol").html("TCP dan UDP");
      } else {
        $(".protokol").html("TCP");
      }
    } else {
      if ($("#udpcheckbox").prop("checked")) {
        $(".protokol").html("UDP");
      } else {
        $(".protokol").html("");
      }
    }
  });

  $("#udpcheckbox").on("click", function (e) {
    if ($("#udpcheckbox").prop("checked")) {
      if ($("#tcpcheckbox").prop("checked")) {
        $(".protokol").html("TCP dan UDP");
      } else {
        $(".protokol").html("UDP");
      }
    } else {
      if ($("#tcpcheckbox").prop("checked")) {
        $(".protokol").html("TCP");
      } else {
        $(".protokol").html("");
      }
    }
  });

  $(".filter").on("submit", function (e) {
    e.preventDefault();

    let totalHalaman = Object.values(JSON.parse($("#total_halaman").val()));
    let sourceList = Object.values(JSON.parse($("#srcLst").val()));
    let destinationList = Object.values(JSON.parse($("#dstLst").val()));

    let page = $("#page").val();
    if (page > totalHalaman) {
      page = 1;
    }

    let filters = `?page=${page}`;

    let src = $("#src").val();
    if (src !== "") {
      if (validInput(src, sourceList, "source")) {
        filters += `&src=${src}`;
      }
    }

    let dst = $("#dst").val();
    if (dst !== "") {
      if (validInput(dst, destinationList, "destination")) {
        filters += `&dst=${dst}`;
      }
    }

    let tcpChecked = $("#tcpcheckbox").prop("checked");
    if (tcpChecked) {
      filters += `&tcp=true`;
    }

    let udpChecked = $("#udpcheckbox").prop("checked");
    if (udpChecked) {
      filters += `&udp=true`;
    }

    let synChecked = $("#stealth_scan_checkbox").prop("checked");
    if (synChecked) {
      filters += `&syn=true`;
    }

    let conChecked = $("#connect_scan_checkbox").prop("checked");
    if (conChecked) {
      filters += `&con=true`;
    }

    let nullChecked = $("#null_scan_checkbox").prop("checked");
    if (nullChecked) {
      filters += `&null=true`;
    }

    let finChecked = $("#fin_scan_checkbox").prop("checked");
    if (finChecked) {
      filters += `&fin=true`;
    }

    let xmasChecked = $("#xmas_scan_checkbox").prop("checked");
    if (xmasChecked) {
      filters += `&xmas=true`;
    }

    let ackChecked = $("#ack_window_scan_checkbox").prop("checked");
    if (ackChecked) {
      filters += `&ack=true`;
    }

    let maimonChecked = $("#maimon_scan_checkbox").prop("checked");
    if (maimonChecked) {
      filters += `&maimon=true`;
    }

    let udpScanChecked = $("#udp_scan_checkbox").prop("checked");
    if (udpScanChecked) {
      filters += `&udpS=true`;
    }

    let datetime = $("#datetimes").val();
    if (datetime) {
      try {
        let timestamps = datetime.split("-");
        let start = timestamps[0].trim().split(" ");
        let finish = timestamps[1].trim().split(" ");
        console.log(start);
        let numS = months.indexOf(start[1]) + 1;
        if (numS < 10) {
          numS = "0" + numS;
        }
        let numF = months.indexOf(finish[1]) + 1;
        if (numF < 10) {
          numF = "0" + numF;
        }
        filters += `&ds=${start[0] + numS + start[2]}&ms=${start[3]}&df=${
          finish[0] + numF + finish[2]
        }&mf=${finish[3]}`;
      } catch (err) {}
    }
    console.log(filters);
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
      if (validInput(src, sourceList, "source")) {
        filters += `&src=${src}`;
      }
    }

    let dst = urlParams.get("dst");
    if (dst !== "") {
      if (validInput(dst, destinationList, "destination")) {
        filters += `&dst=${dst}`;
      }
    }

    let tcp = urlParams.get("tcp");
    if (tcp === "true") {
      filters += `&tcp=true`;
    }

    let udp = urlParams.get("udp");
    if (udp === "true") {
      filters += `&udp=true`;
    }

    let ds = urlParams.get("ds");
    if (ds) {
      filters += `&ds=${ds}`;
    }

    let ms = urlParams.get("ms");
    if (ms) {
      filters += `&ms=${ms}`;
    }

    let df = urlParams.get("df");
    if (df) {
      filters += `&df=${df}`;
    }

    let mf = urlParams.get("mf");
    if (mf) {
      filters += `&mf=${mf}`;
    }

    execute(filters);
  });

  $(".remove").on("click", function (e) {
    execute("");
  });

  function validInput(input, list, type) {
    let valid = false;
    list.every((ip) => {
      console.log(ip[type]);
      if (ip[type] === input) {
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

  $(function () {
    $('input[name="datetimes"]').daterangepicker({
      showDropdowns: true,
      autoUpdateInput: false,
      timePicker: true,
      timePicker24Hour: true,
      locale: {
        cancelLabel: "Clear",
      },
    });

    $('input[name="datetimes"]').on(
      "apply.daterangepicker",
      function (ev, picker) {
        $(this).val(
          picker.startDate.format("DD MMM YYYY HH:mm") +
            " - " +
            picker.endDate.format("DD MMM YYYY HH:mm")
        );
      }
    );

    $('input[name="datetimes"]').on(
      "cancel.daterangepicker",
      function (ev, picker) {
        $(this).val("");
      }
    );
  });
});
