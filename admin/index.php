<?php
require_once 'config/auth.php';
require_once 'config/db.php';

redirect_if_not_logged_in();

if (!is_admin()) {
    header("Location: ../login.php");
    exit();
}

// Ambil notifikasi jika ada
$message = isset($_GET['message']) ? $_GET['message'] : "";

// Query untuk mendapatkan daftar postingan yang perlu diapprove, direvisi, atau ada permintaan penghapusan
$query_posts = "SELECT * FROM posts WHERE status='pending' OR status='revision' OR status='request_delete' ORDER BY id DESC";
$result_posts = $mysqli->query($query_posts);
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Atlantis Lite - Bootstrap 4 Admin Dashboard</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="assets/img/icon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ['assets/css/fonts.min.css']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/atlantis.min.css">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css">
</head>

<body>
    <div class="wrapper">
        <?php include 'components/navigation.php'; ?>
        <div class="main-panel">
            <div class="content">
                <div class="panel-header bg-primary-gradient">
                    <div class="page-inner py-5">
                        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                            <div>
                                <h2 class="text-white pb-2 fw-bold">Dashboard Admin</h2>
                                <h5 class="text-white op-7 mb-2">Home</h5>
                            </div>
                            <div class="ml-md-auto py-2 py-md-0">
                                <a href="components/logout.php" class="btn btn-secondary btn-round">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="page-inner mt--5">
                    <div class="row mt--2">
                        <div class="col-md-12">
                            <div class="card full-height">
                                <div class="card-body">
                                    <div class="card-title">Overall statistics</div>
                                    <div class="card-category">Daily information about statistics in system</div>
                                    <div class="d-flex flex-wrap justify-content-around pb-2 pt-4">
                                        <div class="px-2 pb-2 pb-md-0 text-center">
                                            <div id="circles-1"></div>
                                            <h6 class="fw-bold mt-3 mb-0">Approve</h6>
                                        </div>
                                        <div class="px-2 pb-2 pb-md-0 text-center">
                                            <div id="circles-2"></div>
                                            <h6 class="fw-bold mt-3 mb-0">Pending</h6>
                                        </div>
                                        <div class="px-2 pb-2 pb-md-0 text-center">
                                            <div id="circles-3"></div>
                                            <h6 class="fw-bold mt-3 mb-0">Revition</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Permission</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Judul</th>
                                                    <th style="width:25%">Isi Konten</th>
                                                    <!-- <th>Referensi</th> -->
                                                    <th>Status</th>
                                                    <!-- <th>Catatan Judul</th>
                                                <th>Catatan Isi</th>
                                                <th>Catatan Referensi</th> -->
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result_posts->fetch_assoc()) : ?>
                                                    <tr>
                                                        <!-- <td><?= $row['id'] ?></td> -->
                                                        <td><?= $row['judul'] ?></td>
                                                        <td><?= $row['isi'] ?></td>
                                                        <!-- <td>
                                                            <?php
                                                            $referensi_array = explode(',', $row['referensi']);
                                                            echo '<ul>';
                                                            foreach ($referensi_array as $referensi) {
                                                                echo '<li>' . $referensi . '</li>';
                                                            }
                                                            echo '</ul>';
                                                            ?>
                                                        </td> -->
                                                        <td style="text-transform: capitalize;"><?= $row['status'] ?></td>
                                                        <!-- <td><?= $row['catatan_judul'] ?></td> -->
                                                        <!-- <td><?= $row['catatan_isi'] ?></td> -->
                                                        <!-- <td>
                                                    <?php
                                                    $catatan_referensi_array = explode(',', $row['catatan_referensi']);
                                                    echo '<ul>';
                                                    foreach ($catatan_referensi_array as $catatan_referensi) {
                                                        echo '<li>' . $catatan_referensi . '</li>';
                                                    }
                                                    echo '</ul>';
                                                    ?>
                                                     </td> -->

                                                        <td>
                                                            <?php if ($row['status'] === 'request_delete') : ?>
                                                                <a class="btn btn-danger" href="delete_post.php?id=<?= $row['id'] ?>">Delete</a>
                                                            <?php else : ?>
                                                                <a class="btn btn-success" href="approve.php?id=<?= $row['id'] ?>">Approve</a>
                                                                <a class="btn btn-primary" href="revisi.php?id=<?= $row['id'] ?>">View Detail</a>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="copyright ml-auto">
                        2024, made with <i class="fa fa-heart heart text-danger"></i> Bootstrap
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery UI -->
    <script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>


    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
    <script src="assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Atlantis JS -->
    <script src="assets/js/atlantis.min.js"></script>

    <!-- Atlantis DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
    <script src="assets/js/demo.js"></script>
    <script >
		$(document).ready(function() {
			$('#basic-datatables').DataTable({
			});

			$('#multi-filter-select').DataTable( {
				"pageLength": 5,
				initComplete: function () {
					this.api().columns().every( function () {
						var column = this;
						var select = $('<select class="form-control"><option value=""></option></select>')
						.appendTo( $(column.footer()).empty() )
						.on( 'change', function () {
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
								);

							column
							.search( val ? '^'+val+'$' : '', true, false )
							.draw();
						} );

						column.data().unique().sort().each( function ( d, j ) {
							select.append( '<option value="'+d+'">'+d+'</option>' )
						} );
					} );
				}
			});

			// Add Row
			$('#add-row').DataTable({
				"pageLength": 5,
			});

			var action = '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

			$('#addRowButton').click(function() {
				$('#add-row').dataTable().fnAddData([
					$("#addName").val(),
					$("#addPosition").val(),
					$("#addOffice").val(),
					action
					]);
				$('#addRowModal').modal('hide');

			});
		});
	</script>
    <?php
    require_once 'config/auth.php';
    require_once 'config/db.php';

    function getTotalPostsByStatus($status) {
        global $mysqli;
        $query = "SELECT COUNT(*) as total FROM posts WHERE status = ?";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total'];
    }

    // Get total posts for each status
    $pendingTotal = getTotalPostsByStatus('pending');
    $approveTotal = getTotalPostsByStatus('approved');
    $revisiTotal = getTotalPostsByStatus('revision');
    ?>
    <script>
        Circles.create({
            id: 'circles-1',
            radius: 45,
            value:  <?php echo $approveTotal; ?>,
            maxValue: 100,
            width: 7,
            value:  <?php echo $approveTotal; ?>,
            colors: ['#f1f1f1', '#2BB930'],
            duration: 400,
            wrpClass: 'circles-wrp',
            textClass: 'circles-text',
            styleWrapper: true,
            styleText: true
        })

        Circles.create({
            id: 'circles-2',
            radius: 45,
            value: <?php echo $pendingTotal; ?>,
            maxValue: 100,
            width: 7,
            value: <?php echo $pendingTotal; ?>,
            colors:  ['#f1f1f1', '#FF9E27'],
            duration: 400,
            wrpClass: 'circles-wrp',
            textClass: 'circles-text',
            styleWrapper: true,
            styleText: true
        })

        Circles.create({
            id: 'circles-3',
            radius: 45,
            value: <?php echo $revisiTotal; ?>,
            maxValue: 100,
            width: 7,
            value: <?php echo $revisiTotal; ?>,
            colors: ['#f1f1f1', '#F25961'],
            duration: 400,
            wrpClass: 'circles-wrp',
            textClass: 'circles-text',
            styleWrapper: true,
            styleText: true
        })
    </script>
</body>

</html>