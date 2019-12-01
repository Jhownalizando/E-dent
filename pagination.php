<nav aria-label="navigation">
  <ul class="pagination pull-right">
    <?php
      $filter_str = !empty($filter) ? "&filter={$filter}" : "";

      if ($page > 1) {
        ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page - 1 ?><?= $filter_str; ?>" aria-label="Anterior">
              <span aria-hidden="true">&laquo;</span>
              <span class="sr-only">Anterior</span>
            </a>
          </li>
        <?php
      }

      for ($i = 0; $i < $num_pages; $i ++) {
        if (($i + 1) == $page) {
          ?>
            <li class="page-item active"><a class="page-link" href="?page=<?= $i + 1 ?><?= $filter_str; ?>"><?= $i + 1; ?></a></li>
          <?php
        } else {
          ?>
            <li class="page-item"><a class="page-link" href="?page=<?= $i + 1 ?><?= $filter_str; ?>"><?= $i + 1; ?></a></li>
          <?php
        }
      }

      if ($page < $num_pages) {
        ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Próximo">
              <span aria-hidden="true">&raquo;</span>
              <span class="sr-only">Próximo</span>
            </a>
          </li>
        <?php
      }
    ?>
  </ul>
</nav>
