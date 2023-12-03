<!-- pagination.php -->
<ul class="pagination">
    <li class="page-item">
        <a class="page-link"
            href="?halaman=1<?php echo isset($_GET['kategori']) ? '&kategori=' . $_GET['kategori'] : ''; ?><?php echo isset($_GET['keyword']) ? '&keyword=' . $_GET['keyword'] : ''; ?>"
            tabindex="-1" aria-disabled="true">awal</a>
    </li>
    <?php if (isset($_GET['halaman']) && $_GET['halaman'] > 1) { ?>
        <li class="page-item">
            <a class="page-link"
                href="?halaman=<?php echo $_GET['halaman'] - 1; ?><?php echo isset($_GET['kategori']) ? '&kategori=' . $_GET['kategori'] : ''; ?><?php echo isset($_GET['keyword']) ? '&keyword=' . $_GET['keyword'] : ''; ?>"
                tabindex="-1" aria-disabled="false">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    <?php } else { ?>
        <li class="page-item disabled">
            <span class="page-link" aria-hidden="true">&laquo;</span>
        </li>
    <?php } ?>

    <?php
    $start = max(1, min($Pages - 2, isset($_GET['halaman']) ? $_GET['halaman'] - 1 : 1));
    $end = min($Pages, $start + 2);

    for ($counter = $start; $counter <= $end; $counter++) {
        ?>
        <li class="page-item <?php echo isset($_GET['halaman']) && $_GET['halaman'] == $counter ? 'active' : ''; ?>">
            <a class="page-link"
                href="?halaman=<?php echo $counter ?><?php echo isset($_GET['kategori']) ? '&kategori=' . $_GET['kategori'] : ''; ?><?php echo isset($_GET['keyword']) ? '&keyword=' . $_GET['keyword'] : ''; ?>">
                <?php echo $counter ?>
                <?php echo isset($_GET['halaman']) && $_GET['halaman'] == $counter ? '<span class="sr-only">(current)</span>' : ''; ?>
            </a>
        </li>
    <?php } ?>

    <?php if (isset($_GET['halaman'])) {
        if ($_GET['halaman'] >= $Pages) { ?>
            <li class="page-item disabled">
                <span class="page-link" aria-hidden="true">&raquo;</span>
            </li>
        <?php } else { ?>
            <li class="page-item">
                <a class="page-link"
                    href="?halaman=<?php echo $_GET['halaman'] + 1; ?><?php echo isset($_GET['kategori']) ? '&kategori=' . $_GET['kategori'] : ''; ?><?php echo isset($_GET['keyword']) ? '&keyword=' . $_GET['keyword'] : ''; ?>"
                    tabindex="-1" aria-disabled="false">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php }
    } else { ?>
        <li class="page-item">
            <a class="page-link"
                href="?halaman=2<?php echo isset($_GET['kategori']) ? '&kategori=' . $_GET['kategori'] : ''; ?><?php echo isset($_GET['keyword']) ? '&keyword=' . $_GET['keyword'] : ''; ?>"
                tabindex="-1" aria-disabled="false">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    <?php } ?>

    <li class="page-item">
        <a class="page-link"
            href="?halaman=<?php echo $Pages ?><?php echo isset($_GET['kategori']) ? '&kategori=' . $_GET['kategori'] : ''; ?><?php echo isset($_GET['keyword']) ? '&keyword=' . $_GET['keyword'] : ''; ?>">akhir</a>
    </li>
</ul>
