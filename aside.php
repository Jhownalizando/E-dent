<aside>
  <div id="sidebar" class="nav-collapse" style="background-color: #009788;">
    <ul class="sidebar-menu">
      <li>
        <a href="index.php">
          <i class="fas fa-home"></i>
          <span>Home</span>
        </a>
      </li>

      <li class="sub-menu">
        <a style="cursor: pointer;">
          <i class="fas fa-user-md"></i>
          <span> Pacientes</span>
        </a>
        <ul class="sub">
          <li><a href="lista_paciente.php"> Lista de Pacientes</a></li>
          <li><a href="cadastro_paciente.php"> Cadastrar Paciente</a></li>
        </ul>
      </li>

      <?php
        if (isset($usuario_tipo) && $usuario_tipo === 'coordenador') {
          ?>
            <li class="sub-menu">
              <a style="cursor: pointer;">
                <i class="fas fa-users"></i>
                <span> Usuários</span>
              </a>
              <ul class="sub">
                <li><a href="lista_usuario.php"> Lista de Usuários</a></li>
                <li><a href="cadastro_usuario.php"> Cadastrar Usuários</a></li>
              </ul>
            </li>
          <?php
        }
      ?>

      <li class="sub-menu">
        <a style="cursor: pointer;">
          <i class="fas fa-file-alt"></i>
          <span> Prontuarios</span>
        </a>
        <ul class="sub">
          <li><a href="prontuario_historia_medica.php"> Historia Médica</a></li>
          <li><a href="prontuario_higiene_oral.php"> Higiene Oral</a></li>
          <li><a href="prontuario_odontologico.php"> Odontológico</a></li>
        </ul>
      </li>

      <li>
        <a href="lista_consulta.php">
          <i class="fas fa-calendar-alt"></i>
          <span>Consultas</span>
        </a>
      </li>

      <li>
        <a href="logout.php">
          <i class="fas fa-sign-out-alt"></i>
          <span>Sair</span>
        </a>
      </li>

    </ul>
  </div>
</aside>
