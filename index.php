<?php
include_once('check_session.php');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Home">
  <meta name="keyword" content="Web System, Odontologic System, Dentist">
  <title>Home</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap-theme.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet"/>
  <link rel="icon" type="image/png" href="images/icons/iconEdent.png"/>
</head>

<body>
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
                CONSULTAS
              </header>

              <div class="row" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                <div class="align-center">
                  <h2 style="text-align: center;">Bem Vindo <?= ucwords($usuario_nome); ?></h2>
                </div>
                <br>
                <br>
                <div id='calendar' style="max-width: 800px; max-height: 800px; width: 60%; height: auto; margin-bottom: 50px;"></div>
              </div>
            </section>
          </div>
        </div>
      </section>
    </section>
  </section>

  <div class="modal" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <div class="modal-header" style="min-height: 50px;">
          <div class="col-lg-10">
            <h4 class="modal-title" id="modalLabel" style="font-weight: bold; color: #000;">Adicionar consulta</h4>
          </div>

          <div class="col-lg-2">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>

        <div class="modal-body">
          <input id="data" name="data" type="hidden" value="" required="required"/>

          <div class="row">
            <div class="col-lg-12 form-group">
              <div class="form-group">
                <label for="paciente" class="control-label col-lg-2">Paciente<span class="required">*</span></label>
                <div class="col-lg-10" style="height: 34px !important; margin-bottom: 10px;">
                  <select id="paciente" name="paciente" class="form-control" required="required">
                    <option value="" selected>Selecione</option>
                    <?php
                      include_once('connection.php');

                      $query = "SELECT idPaciente, nome FROM paciente WHERE deleted_at IS NULL";

                      $result = mysqli_query($conn, $query);

                      if ($result) {
                        while ($data = mysqli_fetch_array($result)) {
                          ?>
                            <option value="<?= $data['idPaciente']; ?>">
                              <?= $data['nome']; ?>
                            </option>
                          <?php
                        }
                      }
                    ?>
                  </select>
                  <br>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 form-group">
              <label for="titulo" class="control-label col-lg-2">Titulo da consulta<span class="required">*</span></label>
              <div class="col-lg-10">
                <input class="form-control" id="titulo" name="titulo" placeholder="Digite o titulo" type="text" required="required" maxlength="100"/>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 form-group">
              <label for="hora" class="control-label col-lg-2">Hora da Consulta<span class="required">*</span></label>
              <div class="col-lg-10">
                <input class="form-control" id="hora" name="hora" placeholder="Digite a hora da consulta" type="time" required="required"/>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 form-group">
              <div class="form-group">
                <label for="status" class="control-label col-lg-2">Status<span class="required">*</span></label>
                <div class="col-lg-10" style="height: 34px !important; margin-bottom: 10px;">
                  <select id="status" name="status" class="form-control" required="required">
                    <option value="agendada" selected>Agendada</option>
                    <option value="finalizada">Finalizada</option>
                    <option value="cancelada">Cancelada</option>
                  </select>
                  <br>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 form-group">
              <label for="observacao" class="control-label col-lg-2">Observação<span class="required">*</span></label>
              <div class="col-lg-10">
                <textarea class="form-control" id="observacao" name="observacao" style="resize: vertical;" required="required" placeholder="Se não tiver observações escreva que não possui."></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          <button type="button" class="btn btn-primary" onclick="submitForm()">Salvar</button>
        </div>

      </div>
    </div>
  </div>

  <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
  <script src="js/jquery-ui-1.10.4.min.js"></script>
  <script src="js/jquery-1.8.3.min.js"></script>
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="js/jquery.customSelect.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
  <script src="js/scripts.js"></script>

  <script src="js/moment.min.js"></script>
  <link href="assets/fullcalendar/packages/core/main.css" rel="stylesheet"/>
  <link href="assets/fullcalendar/packages/daygrid/main.css" rel="stylesheet"/>
  <link href="assets/fullcalendar/packages/timegrid/main.css" rel="stylesheet"/>
  <link href="assets/fullcalendar/packages/list/main.css" rel="stylesheet"/>
  <script src="assets/fullcalendar/packages/core/main.js"></script>
  <script src="assets/fullcalendar/packages/core/locales-all.js"></script>
  <script src="assets/fullcalendar/packages/interaction/main.js"></script>
  <script src="assets/fullcalendar/packages/daygrid/main.js"></script>
  <script src="assets/fullcalendar/packages/timegrid/main.js"></script>

  <script>
    const initialLocaleCode = 'pt-br';
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth, timeGridWeek, timeGridDay',
      },
      defaultDate: moment().format('YYYY-MM-DD'),
      locale: initialLocaleCode,
      buttonIcons: false,
      weekNumbers: false,
      navLinks: true,
      selectable: true,
      selectMirror: true,
      editable: false,
      eventLimit: true,
      eventColor: 'gray',
      select: function(arg) {
        if (moment(arg.start).isValid()) {
          $('#data').val(moment(arg.start).format('YYYY-MM-DD'));
          $('#modal').modal('show');

          calendar.unselect();
        }
      },
      events: {
        url: './api/v1/lista_consulta.php',
        method: 'GET',
      },
      eventTimeFormat: {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
      },
    });

    calendar.render();

    async function submitForm() {
      const paciente = $('#paciente').val();
      const titulo = $('#titulo').val().toString().trim();
      const observacao = $('#observacao').val().toString().trim();
      const status = $('#status').val();
      if (status !== 'agendada' && status !== 'finalizada' && status !== 'cancelada') {
        alert('Status invalido');
        return;
      }
      const data = $('#data').val();
      const hora = $('#hora').val();
      const data_hora = moment(`${data} ${hora}`).format('YYYY-MM-DD HH:mm:ss');
      if (!moment(`${data} ${hora}`).isValid()) {
        alert('Data/Hora invalida');
        return;
      }

      const formData = new FormData();
      formData.append('paciente', paciente);
      formData.append('titulo', titulo);
      formData.append('observacao', observacao);
      formData.append('status', status);
      formData.append('data_hora', data_hora);

      const response = await fetch('./api/v1/cadastrar_consulta.php', {
        method: 'POST',
        body: formData,
      });
      let result;
      try {
        result = await response.json();

        if (result && result.id && result.message) {
          $('#modal').modal('hide');

          alert(result.message);

          calendar.addEvent({
            id: result.id,
            title: titulo,
            start: data_hora,
            end: data_hora,
            color: status === 'finalizada' ? '#068900' : (status === 'cancelada' ? '#F40000' : '#3891FF'),
            url: `./editar_consulta.php?id=${result.id}`,
            allDay: false,
            editable: false,
          });
        } else {
          alert(result.message || 'Houve um erro');
        }
      } catch (e) {
        alert('Houve um erro');
      }
    }

    $('#modal').on('hide.bs.modal', function (e) {
      // Clear fields
      $('#paciente').val('');
      $('#titulo').val('');
      $('#observacao').val('');
      $('#data').val('');
      $('#hora').val('');
    });
  </script>
</body>

</html>
