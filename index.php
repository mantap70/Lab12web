<?php
include 'config/database.php';
$keyword = isset($_GET['search']) ? $_GET['search'] : '';

$perPage = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

// kondisi search
$where = "";
if (!empty($keyword)) {
    $where = "WHERE nama_produk LIKE '%$keyword%' 
              OR kategori LIKE '%$keyword%'";
}

// ambil data produk
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM produk $where LIMIT $start, $perPage"
);

// hitung total data
$totalData = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) as total FROM produk $where"
);
$data = mysqli_fetch_assoc($totalData);
$totalPage = ceil($data['total'] / $perPage);



?>

<!DOCTYPE html>
<html>
<head>
    <title>Toko Elektronik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container py-5">
    <h2 class="text-center mb-4 fw-bold">Toko Elektronik</h2>

    <form method="GET" class="mb-4">
        <div class="input-group input-group-lg shadow-sm">
            <span class="input-group-text bg-primary text-white">
                🔍
            </span>
            <input 
                type="text" 
                name="search"
                class="form-control"
                placeholder="Cari mouse atau keyboard..."
                value="<?= htmlspecialchars($keyword); ?>"
            >
            <button class="btn btn-primary" type="submit">
                Cari
            </button>
        </div>
    </form>

    <div class="row g-4">
        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
        <div class="col-md-3">
            <div class="card h-100 shadow-sm">
                <img src="assets/img/<?= $row['gambar']; ?>" class="card-img-top">
                <div class="card-body text-center">
                    <h6 class="fw-bold"><?= $row['nama_produk']; ?></h6>
                    <p class="text-muted"><?= $row['kategori']; ?></p>
                    <span class="badge bg-success">
                        Rp <?= number_format($row['harga']); ?>
                    </span>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    <!-- Pagination -->
    <nav class="mt-4">
        <ul class="pagination justify-content-center">

            <!-- Previous -->
            <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" 
                href="?page=<?= $page - 1; ?>&search=<?= $keyword; ?>">
                Previous
                </a>
            </li>

            <?php for ($i = 1; $i <= $totalPage; $i++) { ?>
                <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" 
                    href="?page=<?= $i; ?>&search=<?= $keyword; ?>">
                    <?= $i; ?>
                    </a>
                </li>
            <?php } ?>

            <!-- Next -->
            <li class="page-item <?= ($page >= $totalPage) ? 'disabled' : ''; ?>">
                <a class="page-link" 
                href="?page=<?= $page + 1; ?>&search=<?= $keyword; ?>">
                Next
                </a>
            </li>

        </ul>
    </nav>


</div>

</body>
</html>