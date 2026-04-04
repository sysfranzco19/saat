<script type="text/javascript">
    function guardar()
    {
    <?php
        $array=['chk_ser_1','chk_ser_2','chk_ser_3','chk_ser_4','chk_ser_5','chk_decidir_1','chk_decidir_2','chk_decidir_3','chk_decidir_4','chk_decidir_5'];
        foreach($array as $row):
    ?>
            opciones<?php echo $row;?> = document.form_self.<?php echo $row;?>;
            var seleccionado = false;
            for(var i=0; i<opciones<?php echo $row;?>.length; i++) {
              if(opciones<?php echo $row;?>[i].checked) {
                seleccionado = true;
                break;
              }
            }
            //alert(seleccionado)
            if(!seleccionado) {
                alert("Autoevaluación Incompleta");
                return false;
            }
    <?php
        endforeach;
    ?>
        var falta_des = false;
        //SER
        var txt_ser = document.form_self.text_ser;
        if (txt_ser.value!="") {
        	falta_des = true;
        }else{
        	alert("Escribe las razones por las cuales mereces esta calificación del SER.");
        	document.getElementById('text_ser').focus();
            return false;
        }

        //DECIDIR
        var txt_decidir = document.form_self.text_decidir;
        if (txt_decidir.value!="") {
        	falta_des = true;
        }else{
        	alert("Escribe las razones por las cuales mereces esta calificación del DECIDIR.");
        	document.getElementById('text_decidir').focus();
            return false;
        }

        //Envia Form
        if(falta_des){
        	document.form_self.submit(); 
        }
        
    }

    function SumarAutomatico()
    {
        var resultInput2 = document.getElementById('TotalSer5');
        var chk1 = document.form_self.chk_ser_1;
        var chk2 = document.form_self.chk_ser_2;
        var chk3 = document.form_self.chk_ser_3;
        var chk4 = document.form_self.chk_ser_4;
        var chk5 = document.form_self.chk_ser_5;
        var suma = parseInt(chk1.value || '0', 10) + parseInt(chk2.value || '0', 10) + parseInt(chk3.value || '0', 10) + parseInt(chk4.value || '0', 10) + parseInt(chk5.value || '0', 10);
        resultInput2.value = suma;
    }
    function SumarAutomatico2()
    {
        var rInput2 = document.getElementById('TotalDecidir5');
        var chk1 = document.form_self.chk_decidir_1;
        var chk2 = document.form_self.chk_decidir_2;
        var chk3 = document.form_self.chk_decidir_3;
        var chk4 = document.form_self.chk_decidir_4;
        var chk5 = document.form_self.chk_decidir_5;
        var suma = parseInt(chk1.value || '0', 10) + parseInt(chk2.value || '0', 10) + parseInt(chk3.value || '0', 10) + parseInt(chk4.value || '0', 10) + parseInt(chk5.value || '0', 10);
        rInput2.value = suma;
    }
</script>
<?php
	$reg = false;
	$ser = array(0, 0, 0, 0, 0);
	$ser5 = 0;
	$serd = "";
	$dec = array(0, 0, 0, 0, 0);
	$dec5 = 0;
	$decd = "";
	//$sql1 = "SELECT * FROM self_appraisal WHERE student_id=".$student_id." AND phase_id=2";
    //$autos = $this->db->query($sql1)->result_array();
    if(count($autos)>=1) {
    	$reg = true;
		foreach ($autos as $auto):
			$ser = explode("-",$auto['ser']);
			$ser5 = $auto['ser5'];
			$serd = $auto['ser_descripcion'];
			$dec = explode("-",$auto['dec']);
			$dec5 = $auto['dec5'];
			$decd = $auto['dec_descripcion'];
			break;
		endforeach;    	
    }

?>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">
                AUTOEVALUACIÓN (Secundaria) - <?php echo $student;?>
                </h3>
                <p><?php echo $phase_name;?></p>
            </div>
            <!--begin::Form <form name="form_assists"> -->
                <form action="<?php echo base_url() . 'index.php/student/self_appraisal_save'; ?>" method="post" class="form" name="form_self" >
                <div class="card-body">
                    <div class="form-group mb-8">
                        <div class="alert alert-custom alert-default" role="alert">
                            <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                            <div class="alert-text">
                                Lee con mucha atención cada dimensión, analiza, reflexiona y evalúate en base a los criterios correspondientes.<br />

                            </div>
                        </div>
                    </div>
                    <h3 class="font-size-lg text-dark font-weight-bold mb-6">DIMENSIÓN: SER</h3>
                    <div class="form-group row">
                        <label  class="col-6 col-form-label">Cumplo de manera correcta las normas y reglas del colegio.</label>
                        <div class="col-6 col-form-label">
                            <div class="checkbox-inline">
                                <label class="checkbox">
                                    <input type="radio" name="chk_ser_1" value="1" onchange="SumarAutomatico();" <?php if($reg){echo "disabled";}?> <?php if($ser[0]==1){echo "checked";}?>/><span></span>Sí
                                </label>
                                <label class="checkbox">
                                    <input type="radio" name="chk_ser_1" value="0" onchange="SumarAutomatico();" <?php if($reg){echo "disabled";}?> <?php if($ser[0]==0){echo "checked";}?>/><span></span>No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-6 col-form-label">Trabajo en todos los  espacios y momentos con respeto para una convivencia sana.</label>
                        <div class="col-6 col-form-label">
                            <div class="checkbox-inline">
                                <label class="checkbox">
                                    <input type="radio" name="chk_ser_2" value="1" onchange="SumarAutomatico();" <?php if($reg){echo "disabled";}?> <?php if($ser[0]==1){echo "checked";}?>/><span></span>Sí
                                </label>
                                <label class="checkbox">
                                    <input type="radio" name="chk_ser_2" value="0" onchange="SumarAutomatico();" <?php if($reg){echo "disabled";}?> <?php if($ser[0]==0){echo "checked";}?>/><span></span>No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-6 col-form-label">Practico los valores del colegio: Confiabilidad, Respeto, Responsabilidad, Justicia, Bondad, Ciudadanía.</label>
                        <div class="col-6 col-form-label">
                            <div class="checkbox-inline">
                                <label class="checkbox">
                                    <input type="radio" name="chk_ser_3" value="1" onchange="SumarAutomatico();" <?php if($reg){echo "disabled";}?> <?php if($ser[0]==1){echo "checked";}?>/><span></span>Sí
                                </label>
                                <label class="checkbox">
                                    <input type="radio" name="chk_ser_3" value="0" onchange="SumarAutomatico();" <?php if($reg){echo "disabled";}?> <?php if($ser[0]==0){echo "checked";}?>/><span></span>No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-6 col-form-label">Asumo con responsabilidad el cumplimiento de mis tareas y trabajos, los mismos que entrego en fecha establecida.</label>
                        <div class="col-6 col-form-label">
                            <div class="checkbox-inline">
                                <label class="checkbox">
                                    <input type="radio" name="chk_ser_4" value="1" onchange="SumarAutomatico();" <?php if($reg){echo "disabled";}?> <?php if($ser[0]==1){echo "checked";}?>/><span></span>Sí
                                </label>
                                <label class="checkbox">
                                    <input type="radio" name="chk_ser_4" value="0" onchange="SumarAutomatico();" <?php if($reg){echo "disabled";}?> <?php if($ser[0]==0){echo "checked";}?>/><span></span>No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-6 col-form-label">Soy ordenado y disciplinado durante mis clases con todo mi material necesario a mi alcance.</label>
                        <div class="col-6 col-form-label">
                            <div class="checkbox-inline">
                            <label class="checkbox">
                                    <input type="radio" name="chk_ser_5" value="1" onchange="SumarAutomatico();" <?php if($reg){echo "disabled";}?> <?php if($ser[0]==1){echo "checked";}?>/><span></span>Sí
                                </label>
                                <label class="checkbox">
                                    <input type="radio" name="chk_ser_5" value="0" onchange="SumarAutomatico();" <?php if($reg){echo "disabled";}?> <?php if($ser[0]==0){echo "checked";}?>/><span></span>No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-6 col-form-label">Total del SER 5 pts.:</label>
                        <div class="col-6 col-form-label">
                            <input class="form-control form-control-solid" id="TotalSer5" name="TotalSer5" type="text" readonly="true" value="<?php echo $ser5;?>" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-6 col-form-label">Escribe las razones por las cuales mereces esta calificación del SER:</label>
                        <div class="col-6 col-form-label">
                            <textarea class="form-control form-control-solid" rows="3" name="text_ser" id="text_ser" required <?php if($reg){echo "disabled";}?> ><?php echo $serd;?></textarea>
                        </div>
                    </div>
                    <h3 class="font-size-lg text-dark font-weight-bold mb-6">DIMENSIÓN: DECIDIR</h3>
                    <div class="form-group row">
                        <label  class="col-6 col-form-label">Practico la equidad de género respetando la dignidad y los derechos de todas las personas que me rodean.</label>
                        <div class="col-6 col-form-label">
                            <div class="checkbox-inline">
                                <label class="checkbox">
                                    <input type="radio" name="chk_decidir_1" value="1" onchange="SumarAutomatico2();" <?php if($reg){echo "disabled";}?> <?php if($dec[0]==1){echo "checked";}?> /><span></span>Sí
                                </label>
                                <label class="checkbox">
                                    <input type="radio" name="chk_decidir_1" value="0" onchange="SumarAutomatico2();" <?php if($reg){echo "disabled";}?> <?php if($dec[0]==0){echo "checked";}?> /><span></span>No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-6 col-form-label">Aplico conceptos y habilidades aprendidas para la solución de problemas.</label>
                        <div class="col-6 col-form-label">
                            <div class="checkbox-inline">
                                <label class="checkbox">
                                    <input type="radio" name="chk_decidir_2" value="1" onchange="SumarAutomatico2();" <?php if($reg){echo "disabled";}?> <?php if($dec[0]==1){echo "checked";}?> /><span></span>Sí
                                </label>
                                <label class="checkbox">
                                    <input type="radio" name="chk_decidir_2" value="0" onchange="SumarAutomatico2();" <?php if($reg){echo "disabled";}?> <?php if($dec[0]==0){echo "checked";}?> /><span></span>No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-6 col-form-label">Soy sensible a las emociones de los demás y trato de comprender sus puntos de vista.</label>
                        <div class="col-6 col-form-label">
                            <div class="checkbox-inline">
                                <label class="checkbox">
                                    <input type="radio" name="chk_decidir_3" value="1" onchange="SumarAutomatico2();" <?php if($reg){echo "disabled";}?> <?php if($dec[0]==1){echo "checked";}?> /><span></span>Sí
                                </label>
                                <label class="checkbox">
                                    <input type="radio" name="chk_decidir_3" value="0" onchange="SumarAutomatico2();" <?php if($reg){echo "disabled";}?> <?php if($dec[0]==0){echo "checked";}?> /><span></span>No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-6 col-form-label">Comprendo el propósito y la importancia de la lectura en mi vida diaria y en mi aprendizaje.</label>
                        <div class="col-6 col-form-label">
                            <div class="checkbox-inline">
                                <label class="checkbox">
                                    <input type="radio" name="chk_decidir_4" value="1" onchange="SumarAutomatico2();" <?php if($reg){echo "disabled";}?> <?php if($dec[0]==1){echo "checked";}?> /><span></span>Sí
                                </label>
                                <label class="checkbox">
                                    <input type="radio" name="chk_decidir_4" value="0" onchange="SumarAutomatico2();" <?php if($reg){echo "disabled";}?> <?php if($dec[0]==0){echo "checked";}?> /><span></span>No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-6 col-form-label">Demuestro comportamientos adecuados a las reglas establecidas en el aula.</label>
                        <div class="col-6 col-form-label">
                            <div class="checkbox-inline">
                            <label class="checkbox">
                                    <input type="radio" name="chk_decidir_5" value="1" onchange="SumarAutomatico2();" <?php if($reg){echo "disabled";}?> <?php if($dec[0]==1){echo "checked";}?> /><span></span>Sí
                                </label>
                                <label class="checkbox">
                                    <input type="radio" name="chk_decidir_5" value="0" onchange="SumarAutomatico2();" <?php if($reg){echo "disabled";}?> <?php if($dec[0]==0){echo "checked";}?> /><span></span>No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-6 col-form-label">Total del DECIDIR 5 pts.:</label>
                        <div class="col-6 col-form-label">
                            <input class="form-control form-control-solid" id="TotalDecidir5" name="TotalDecidir5" type="text" value="<?php echo $dec5;?>" readonly="true" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-6 col-form-label">Escribe las razones por las cuales mereces esta calificación del DECIDIR:</label>
                        <div class="col-6 col-form-label">
                            <textarea class="form-control form-control-solid" rows="3" name="text_decidir" id="text_decidir" required=true <?php if($reg){echo "disabled";}?> ><?php echo $decd;?></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-6">
                        </div>
                        <div class="col-6">
                        	<?php 
                        	if($reg){
                        		echo "<div class='alert alert-warning' role='alert'>Tu autoevaluación ya esta registrada.</div>";
                        	}else{
                        		?>

                            <input type="button" class="btn btn-success mr-2" onclick="guardar()" Value="Registrar">
                            <a href="<?php echo base_url(); ?>index.php/student/dashboard" class="btn btn-secondary">Cancelar</a>
                        	<?php 
                        	}
                        	?>
                            <!--<button type="reset" class="btn btn-secondary">Cancelar</button><a href="" onclick="guardar()" class="btn btn-success mr-2">Registrar</a>-->

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--end::Container-->
</div>
            