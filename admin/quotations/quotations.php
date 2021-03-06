<!-- author: Teenus SAS, github: Teenus SAS -->
<!doctype html>
<html lang="es">
<?php
include("../partials/verify-session.php");
require dirname(dirname(__DIR__)) . "/dao/AdminDao.php";
require dirname(dirname(__DIR__)) . "/dao/QuotationDao.php";
$quotationDao = new QuotationDao();
$adminDao = new AdminDao();
$admin = unserialize($_SESSION["admin"]);
$quotations = $quotationDao->findAssignedTo($admin->getId());
?>

<head>
  <title>Cotizaciones | Greenpack </title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- Material Kit CSS -->
  <link href="../assets/css/material-dashboard.css?v=2.1.0" rel="stylesheet" />
  <link rel="stylesheet" href="/css/all.min.css">
  <!-- Page level plugin CSS-->
  <link href="/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/spinner.css">
  <link href="https://cdn.jsdelivr.net/npm/froala-editor@3.0.0/css/froala_editor.pkgd.min.css" rel="stylesheet" type='text/css' />
</head>

<body class="white-edition">
  <div class="wrapper ">
    <?php include("../partials/sidebar.php") ?>
    <div class="main-panel">
      <!-- Navbar -->
      <?php include("../partials/navbar.php"); ?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <!-- Breadcrumbs-->
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="#">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Cotizaciones</li>
          </ol>
          <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-table"></i>
              Cotizaciones Asignadas
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th class="text-center">Nombre</th>
                      <th class="text-center">Apellido</th>
                      <th class="text-center">Empresa</th>
                      <th class="text-center">Total de la cotización</th>
                      <th class="text-center">Fecha</th>
                      <th class="text-center">Ver Cotizacion</th>
                      <th class="text-center">Editar</th>
                      <th class="text-center">Descargar</th>
                      <th class="text-center">Enviar</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($quotations as $quotation) { ?> <tr>
                        <td><?php echo $quotation->getNameClient(); ?></td>
                        <td><?php echo $quotation->getLastNameClient(); ?></td>
                        <td class="text-center"><?php echo $quotation->getCompany() == "" ? "N/A" : $quotation->getCompany(); ?> </td>
                        <td class="text-center money"><?php echo $quotation->calculateTotal(); ?></td>
                        <td class="text-center"><?= date("d-m-Y", $quotation->getCreatedAt()); ?></td>
                        <td class="text-center"><a class="text-center" href="javascript:viewPdf(`<?= $quotation->getId(); ?>`)" title="Ver Aqui"><i class="material-icons">remove_red_eye</i> <a href="#" onclick="openWindow(`<?= $quotation->getId(); ?>`)" title="Ver en nueva Ventana"><i class="material-icons">featured_video</i></a></td>
                        <td class="text-center"><a href="edit-quotation.php?id=<?= $quotation->getId() ?>"><i class="material-icons">create</i></a></td>
                        <td class="text-center"><a class="text-center" target="_blank" title="descargar" href="/services/download-quotation.php?id=<?php echo $quotation->getId(); ?>"><i class="material-icons">cloud_download</a></td>
                        <td class="text-center"><a class="text-center" href="javascript:sentEmail(`<?php echo $quotation->getId(); ?>`)"><i class="material-icons">email</a></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div id="load_pdf">
        </div>
        <?php include("../partials/footer.html"); ?>
      </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modalContentEmail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cuerpo del Mensaje</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="content"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" onclick="send()" id="btn-send-email" class="btn btn-primary">Enviar</button>
          </div>
        </div>
      </div>
    </div>


    <!--   Core JS Files   -->
    <script src="/js/jquery-2.2.4.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap-material-design.min.js"></script>
    <script src="https://unpkg.com/default-passive-events"></script>
    <!-- <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script> -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="../assets/js/plugins/chartist.min.js"></script>
    <script src="../assets/js/plugins/bootstrap-notify.js"></script>
    <script src="../assets/js/material-dashboard.js?v=2.1.0"></script>
    <!-- <script src="../assets/demo/demo.js"></script> -->
    <script>
      $(() => {
        $('.sidebar div.sidebar-wrapper ul.nav li:first').removeClass('active')
        $('#quotations-item').addClass('active')
      })
    </script>
    <script src="../assets/js/script.js"></script>
    <script src="/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="/vendor/jquery.formatCurrency-1.4.0.min.js"></script>
    <script src="/vendor/jquery.formatCurrency.all.js"></script>
    <script src="/js/spinner.js"></script>
    <script src="/vendor/froala_editor.pkgd.min.js"></script>
    <script src="/js/es.js"></script>
    <script>
      // Call the dataTables jQuery plugin
      $(document).ready(function() {
        let table = $('#dataTable').DataTable({
          "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
              "sFirst": "Primero",
              "sLast": "Último",
              "sNext": "Siguiente",
              "sPrevious": "Anterior"
            },
            "oAria": {
              "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }

          }
        })
        table.on('draw', function() {
          $('.money').formatCurrency({
            region: 'es-CO'
          })
        })
        $('#dataTable tbody')
          .on('mouseenter', 'td', function() {
            var colIdx = table.cell(this).index().column;

            $(table.cells().nodes()).removeClass('highlight');
            $(table.column(colIdx).nodes()).addClass('highlight');
          });
      })
    </script>
    <script>
      $(() => {
        $('.money').formatCurrency({
          region: 'es-CO'
        })
        $('.sidebar div.sidebar-wrapper ul.nav li:first').removeClass('active')
        $('#quotations-item').addClass('active')
        var url = document.location.toString();
        if (url.match('#')) {
          $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
        }
      })

      var editor = new FroalaEditor('#content', {
        language: 'es',
        height: 300,
        imageUploadParam: 'photo',
        imageUploadURL: '/admin/upload.php',
        imageUploadMethod: 'POST',
        videoUploadParam: 'video',
        videoUploadURL: 'upload-video.php',
        imageUploadMethod: 'POST',
        fileUploadParam: 'file',
        fileUploadURL: '/admin/upload-file.php',
        fileUploadMethod: 'POST',
        events: {
          'image.removed': function($img) {
            img = $img[0]
            $.post('/admin/image_delete.php', {
              src: $img.attr('src')
            }, (data, status) => {
              if (status != "success") {
                alert("error")
              }
            })
          },
          'file.removed': function($file) {
            file = $file[0]
            $.post('/admin/file_delete.php', {
              src: $file.attr('src')
            }, (data, status) => {
              if (status != "success") {
                alert("error")
              }
            })
          },
          'keyup': function(keyupEvent) {
            if (document.domain != 'localhost') {
              $('.fr-wrapper>div:first-child').css('visibility', 'hidden')
            }
          }
        }
      }, () => {
        editor.html.set(`<html><body><p>Muy buen dia. Un gusto saludarlo y esperamos que se encuentre de maravilla. Para nosotros es un gusto enviarle su cotizacion. Le estaremos contactando en breve</p><p>Cordialmente</p></body></html>`)
        if (document.domain != 'localhost') {
          $('.fr-wrapper>div:first-child').css('visibility', 'hidden')
        }
      })
    </script>
    <script>
      function viewPdf(id) {
        $('#load_pdf').html(`<div class="wall-loading"><div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>`)
        $('.wall-loading').width($('#load_pdf').width())
        $('.wall-loading').height($('#load_pdf').height())
        $('#load_pdf').append(`<div class="card"></div>`)
        $('#load_pdf .card').append(`<embed  src="/services/view-quotation.php?id=${id}#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="600px" />`)
        $('#load_pdf .card embed')[0].onload = fadeSpinner()
        location.href = "#load_pdf"
      }

      function fadeSpinner() {
        $('.wall-loading').delay(100).fadeOut('slow')
        $('.spinner').delay(100).fadeOut('slow')
        $('.wall-loading').css('z-index', -20)
      }

      function sentEmail(id) {
        $('#btn-send-email').attr('data-id-quotation', id)
        $('#modalContentEmail').modal()
      }

      function send() {
        $.notify({
          message: 'Enviando Correo',
          title: 'Procesando',
          icon: 'email'
        }, {
          type: 'info'
        })
        $('#modalContentEmail').modal('hide')
        $.post('api/sent_email.php', {
          id: $('#btn-send-email').attr('data-id-quotation'),
          content: editor.html.get()
        }, (data, status, xhr) => {
          if (status == 'success' && xhr.readyState == 4) {
            location.href = '#no-solved'
            location.reload()
          }
        })
      }

      function openWindow(id) {
        window.open(`/services/view-quotation.php?id=${id}`, "Cotizacion No " + id, "width=600, height=800")
      }
    </script>
</body>

</html>