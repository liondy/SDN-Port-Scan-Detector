</tbody>
</table>
</div>
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <li class="page-item <?= $halaman > 1 ? "" : "disabled"; ?>">
      <a class="page-link changePage" <?php if ($halaman > 1) {
                                        echo "href = '?page=" . $previous . "'";
                                      } ?> aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <?php echo "Total Halaman:" . $total_halaman; ?>
    <?php for ($x = 1; $x <= $total_halaman; $x++) : ?>
      <li class="page-item">
        <a class="page-link changePage" href="?page=<?= $x ?>"><?= $x; ?></a>
      </li>
    <?php endfor; ?>
    <li class="page-item <?= $halaman == $total_halaman ? "disabled" : ""; ?>">
      <a class="page-link changePage" <?php if ($halaman < $total_halaman) {
                                        echo "href = '?page=" . $next . "'";
                                      } ?> aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>
</div>
</body>

</html>