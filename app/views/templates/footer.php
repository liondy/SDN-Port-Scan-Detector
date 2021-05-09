</tbody>
</table>
</div>
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <li class="page-item <?= $data["halaman"] > 1 ? "" : "disabled"; ?>">
      <a class="page-link changePage" aria-label="<?= $data["previous"]; ?>">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <?php for ($x = 1; $x <= $data["total_halaman"]; $x++) : ?>
      <li class="page-item <?= $x == $data["halaman"] ? "active" : ""; ?>">
        <a class="page-link changePage" aria-label="<?= $x; ?>"><?= $x; ?></a>
      </li>
    <?php endfor; ?>
    <li class="page-item <?= $data["halaman"] == $data["total_halaman"] ? "disabled" : ""; ?>">
      <a class="page-link changePage" aria-label="<?= $data["next"]; ?>">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>
</div>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 5">
  <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <strong class="me-auto">SDN Controller</strong>
      <small>now</small>
      <button id="dismiss-toast" type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Controller mendeteksi adanya aktivitas baru
    </div>
  </div>
</div>
</body>

</html>