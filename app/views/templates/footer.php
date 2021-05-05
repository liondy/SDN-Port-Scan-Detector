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
</body>

</html>