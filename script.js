$(document).ready(function () {
  function getData() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    $.ajax({
      type: "POST",
      url: "log.php",
      data: {
        halaman: urlParams.get("page"),
      },
      success: function (data) {
        $("#output").html(data);
      },
    });
  }
  getData();
  setInterval(function () {
    getData();
  }, 60000); // it will refresh your data every 10 sec
});
