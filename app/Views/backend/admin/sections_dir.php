<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <div class="text-center pt-15">
            <h1 class="h2 font-weight-bolder text-dark mb-6">Cursos</h1>
            <div class="h4 text-dark-50 font-weight-normal">Todos los Cursos Activos</div>
        </div>
        <table class="table">
            <thead class="thead-inverse">
                <tr>
                    <th><div>Docente</div></th>
                    <th><div>Lista</div></th>
                    <th><div>Planillas<br />de notas</div></th>
                    <th><div>Centralizador<br />Interno</div></th>
                    <th><div>Promedios<br />más bajos</div></th>
                    <th><div>Centralizador Final</div></th>
                    <th><div>Ranking</div></th>
                </tr>
            </thead>
            <tbody>
                <?php
                /*
                $sql1 = 'SELECT * FROM section WHERE secretary_id!=0 AND section_id>200 AND director_id = '.$this->session->userdata('teacher_id').' ORDER BY section_id';
                $sections = $this->db->query($sql1)->result_array();
                if (count($sections)==0) {
                    $sql1 = 'SELECT * FROM section WHERE secretary_id!=0 AND section_id>200 ORDER BY section_id';
                    $sections = $this->db->query($sql1)->result_array();
                }*/
                foreach($sections as $row):
                ?>
                <tr>
                    <td><?php echo $row['completo']; ?></td>
                    <td>
                        <a href="<?php echo base_url(); ?><?php echo $account_type; ?>/section_students/<?php echo $row['section_id']; ?>" target="_blank" class="btn btn-warning btn-sm" >Lista</a>
                    </td>
                    <td>
                        <a href="<?php echo base_url(); ?><?php echo $account_type; ?>/subjects_section/<?php echo $row['section_id']; ?>" target="_blank" class="btn btn-success btn-sm" >Ver Planillas</a>
                    </td>
                    <td>
                        <a href="<?php echo base_url(); ?><?php echo $account_type; ?>/section_notes/<?php echo $row['section_id']; ?>" target="_blank" class="btn btn-secondary btn-sm" >Notas Centralizadas</a>
                    </td>
                    <td><a href="<?php echo base_url(); ?>index.php/teacher/low_averages/<?php echo $row['section_id']; ?>" class="btn btn-light-danger font-weight-bold mr-2" target="_blank">Promedios Bajos</a></td>
                    <td>
                    <a href="<?php echo base_url(); ?><?php echo $account_type; ?>/generate_centralizer/<?php echo $row['section_id']; ?>" target="_blank" class="btn btn-primary btn-sm" >Descargar</a>
                    </td>
                    <td><a href="<?php echo base_url(); ?>index.php/teacher/generate_ranking/<?php echo $row['section_id']; ?>" class="btn btn-light-warning font-weight-bold mr-2">Ranking</a></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>