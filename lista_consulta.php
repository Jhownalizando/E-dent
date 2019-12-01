<?php
include_once('check_session.php');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Lista de Consultas">
  <meta name="keyword" content="Web System, Odontologic System, Dentist">
  <title>Lista de Consultas</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap-theme.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet"/>
  <link rel="icon" type="image/png" href="images/icons/iconEdent.png"/>
</head>

<body>
  <?php
    include_once('connection.php');

    // handle query parameters
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    // Sanitize query param
    $search = trim(htmlspecialchars(filter_var($search, FILTER_SANITIZE_STRING)));

    $all_filters = [
      ['value' => 'all', 'name' => 'Todos'],
      ['value' => 'agendada', 'name' => 'Agendadas'],
      ['value' => 'finalizada', 'name' => 'Finalizadas'],
      ['value' => 'cancelada', 'name' => 'Canceladas'],
    ];
    $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
    // Sanitize query param
    $filter = trim(htmlspecialchars(filter_var($filter, FILTER_SANITIZE_STRING)));
    $filter = in_array($filter, ['agendada', 'finalizada', 'cancelada', 'all']) ? $filter : 'all';

    // validation to force some query parameters
    $page_tmp = !empty($_GET['page']) ? intval($_GET['page']) : 1;
    $page_tmp = $page_tmp <= 1 ? 1 : $page_tmp;

    if (empty($_GET['page']) || empty($_GET['filter'])) {
      if ($search) {
        header("location: lista_consulta.php?page={$page_tmp}&filter={$filter}&search={$search}");
      } else {
        header("location: lista_consulta.php?page={$page_tmp}&filter={$filter}");
      }
    }
  ?>
  <section id="container">
    <header class="header" style="background-color: #009788; border-bottom: #fff 1px solid;">
      <div class="toggle-nav" style="margin-top: 15px;">
        <div class="icon-reorder tooltips" data-original-title="Menu lateral" data-placement="bottom">
        <i class="fas fa-bars" style="color: #fff;"></i>
      </div>
      </div>
      <a class="navbar-brand" href="login.php">
        <img src="images/icons/E-DENT-3.png" class="nav-item" alt="logo" style="width: 90px">
      </a>
    </header>

    <?php
      include('aside.php');
    ?>

    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                LISTA CONSULTAS
              </header>

              <div class="panel-body">
                <div class="form">
                  <label for="search" class="control-label col-lg-2 cold-md-2 col-sm-12 col-xs-12">Pesquise a consulta: <span class="required">*</span></label>
                  <div class="col-lg-6 cold-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="search" name="search" class="form-control" placeholder="Busque pelo titulo, observação ou nome do paciente" required autofocus value="<?= $search ? $search : ''; ?>">
                  </div>
                  <div class="col-lg-4 cold-md-4 col-sm-6 col-xs-12">
                    <input class="btn btn-primary" type="button" value="Pesquisar" onclick="window.location.href = `${window.location.origin}${window.location.pathname}?filter=<?= $filter; ?>&search=${$('#search').val()}`">
                    <input class="btn btn-secondary" type="button" value="Limpar pesquisa" onclick="window.location.href = `${window.location.origin}${window.location.pathname}?filter=<?= $filter; ?>`">
                  </div>
                </div>
              </div>

              <div class="panel-body">
                <div class="row">
                  <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 form-group pull-right">
                    <select id="select_filter" name="select_filter" class="form-control">
                      <?php
                        foreach ($all_filters as $f) {
                          if ($f['value'] == $filter) {
                            ?>
                              <option value="<?= $f['value']; ?>" selected><?= $f['name']; ?></option>
                            <?php
                          } else {
                            ?>
                              <option value="<?= $f['value']; ?>"><?= $f['name']; ?></option>
                            <?php
                          }
                        }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="col-lg-12">
                  <section class="panel">
                    <table class="table table-striped table-advance table-hover">
                      <tbody>
                        <tr>
                          <th style="text-align: center;">Titulo</th>
                          <th style="text-align: center;">Observação</th>
                          <th style="text-align: center;">Data/Hora</th>
                          <th style="text-align: center;">Status</th>
                          <th style="text-align: center;">Paciente</th>
                          <th style="text-align: center;">Ações</th>
                        </tr>
                        <?php
                          if (empty($search)) {
                            // pagination logic
                            $where_count = '';
                            if ($filter === 'agendada') {
                              $where_count = "status = 'agendada'";
                            } else if ($filter === 'finalizada') {
                              $where_count = "status = 'finalizada'";
                            } else if ($filter === 'cancelada') {
                              $where_count = "status = 'cancelada'";
                            }
                            $where_count = !empty($where_count) ? "WHERE {$where_count}" : "";

                            $count_query = mysqli_query($conn, "SELECT COUNT(idConsulta) as COUNTER FROM consulta {$where_count}");
                            $fetch_rows = mysqli_fetch_assoc($count_query);
                            $total_rows = $fetch_rows['COUNTER'] ? (int) $fetch_rows['COUNTER'] : 0;

                            $limit = 10;
                            $num_pages = intval(ceil($total_rows / $limit));

                            $page = !empty($_GET['page']) ? intval($_GET['page']) : 1;
                            $page = $page <= 1 ? 1 : $page;
                            $page = $page >= $num_pages ? $num_pages : $page;
                            $page = (int) $page;

                            $offset = (int) ($page - 1) * $limit;
                          } else {
                            $limit = 1000;
                            $offset = 0;
                          }

                          // Build query
                          $fields = "consulta.idConsulta,
                                      consulta.titulo,
                                      consulta.observacao,
                                      date_format(consulta.data_hora, '%d/%m/%Y %H:%i') as dataHora,
                                      consulta.status,
                                      paciente.nome as pacienteNome,
                                      consulta.deleted_at";

                          $where = !empty($search) || (!empty($filter) && (in_array($filter, ['agendada', 'finalizada', 'cancelada']))) ? 'WHERE ' : '';

                          if (!empty($search)) {
                            $where .= "(consulta.titulo LIKE '%{$search}%' OR
                                        consulta.observacao LIKE '%{$search}%' OR
                                        paciente.nome LIKE '%{$search}%') ";

                            if ($filter === 'agendada') {
                              $where .= "AND consulta.status = 'agendada'";
                            } else if ($filter === 'finalizada') {
                              $where .= "AND consulta.status = 'finalizada'";
                            } else if ($filter === 'cancelada') {
                              $where .= "AND consulta.status = 'cancelada'";
                            }
                          } else {
                            if ($filter === 'agendada') {
                              $where .= "consulta.status = 'agendada'";
                            } else if ($filter === 'finalizada') {
                              $where .= "consulta.status = 'finalizada'";
                            } else if ($filter === 'cancelada') {
                              $where .= "consulta.status = 'cancelada'";
                            }
                          }

                          $sql_search = "SELECT
                                            {$fields}
                                          FROM
                                            consulta
                                          JOIN
                                            paciente on paciente.idPaciente = consulta.fk_idPaciente
                                          {$where}
                                          ORDER BY consulta.data_hora DESC
                                          LIMIT {$offset}, {$limit}";

                          $result  = mysqli_query($conn, $sql_search);

                          if ($result) {
                            while ($data = mysqli_fetch_array($result)) {
                              ?>
                                <tr>
                                  <td style="text-align: center;"><?= $data['titulo']; ?></td>
                                  <td style="text-align: center;"><?= $data['observacao']; ?></td>
                                  <td style="text-align: center;"><?= $data['dataHora']; ?></td>
                                  <td style="text-align: center;"><?= $data['status']; ?></td>
                                  <td style="text-align: center;"><?= $data['pacienteNome']; ?></td>
                                  <td style="text-align: center;">
                                    <?php
                                      if (empty($data['deleted_at'])) {
                                        ?>
                                          <a class="btn btn-sm btn-primary" title="Editar" href="editar_consulta.php?id=<?= $data['idConsulta']; ?>">
                                            <i class="fas fa-edit"></i>
                                          </a>
                                        <?php
                                      }
                                    ?>
                                  </td>
                                </tr>
                              <?php
                            }
                          }
                        ?>
                      </tbody>
                    </table>

                    <?php
                      if (empty($search)) {
                        include('pagination.php');
                      }
                    ?>
                  </section>
                </div>
              </div>
            </section>
          </div>
        </div>
      </section>
    </section>
  </section>

  <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
  <script src="js/jquery-ui-1.10.4.min.js"></script>
  <script src="js/jquery-1.8.3.min.js"></script>
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="js/jquery.customSelect.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
  <script src="js/scripts.js"></script>
  <script>
    $('#select_filter').change(function(e) {
      const filter = e.target.value || 'all';
      let query = '';
      if (window.location.toString().indexOf('search=') > 0) {
        const idx = window.location.toString().indexOf('search=');
        query = window.location.toString().substring(idx, 1000);
      }
      let url = window.location.origin + window.location.pathname + '?filter=' + filter;
      if (query) {
        url += '&' + query;
      }
      window.location.href = url;
    });
  </script>
</body>

</html>
