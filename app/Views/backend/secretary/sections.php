<?php
$session = session();
$name  = strtoupper($session->get('name'));
?>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <div class="text-center pt-15">
            <h1 class="h2 font-weight-bolder text-dark mb-6">Cursos</h1>
        </div>
        <table class="table">
            <thead class="thead-inverse">
                <tr>
                    <th><div>Cursos</div></th>
                    <th><div>Información</div></th>
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
                        <a href="<?php echo base_url(); ?><?php echo $account_type; ?>/section_rudes/<?php echo $row['section_id']; ?>" target="_blank" class="btn btn-success btn-sm" >Ver rudes</a>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>