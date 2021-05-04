</tbody>
</table>
</div>
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <li class="page-item <?= $data["halaman"] > 1 ? "" : "disabled"; ?>">
      <a class="page-link changePage" <?= $data["halaman"] > 1 ? "href=?page=" . $data["previous"] : "" ?> aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <?php for ($x = 1; $x <= $data["total_halaman"]; $x++) : ?>
      <li class="page-item <?= $x == $data["halaman"] ? "active" : ""; ?>">
        <a class="page-link" <?= $x == $data["halaman"] ? "" : "href = ?page=" . $x; ?>><?= $x; ?></a>
      </li>
    <?php endfor; ?>
    <li class="page-item <?= $data["halaman"] == $data["total_halaman"] ? "disabled" : ""; ?>">
      <a class="page-link changePage" <?= $data["halaman"] < $data["total_halaman"] ? "href=?page=" . $data["next"] : "" ?> aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>
</div>
</body>

</html>