$(document).ready(function () {
  function getData() {
    $.ajax({
      type: "POST",
      url: "log.php",
      success: function (data) {
        $("#output").html(data);
      },
    });
  }
  getData();
  setInterval(function () {
    getData();
  }, 1000); // it will refresh your data every 1 sec
});
