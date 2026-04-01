<script type="text/javascript">
function cargar(sel){
    window.location.assign('<?php echo base_url(); ?>index.php/teacher/behaviors/' + sel )
}
function confirmar()
{
    //alert('hola');
    /* Para obtener el valor */
    var subject_id = document.getElementById("subject_id").value;
    //alert(subject_id);
    var student = $("#kt_select2_3 :selected").length;
    //alert(student);
    var tipo = document.getElementById("tipo").value;
    //alert(tipo);
    var behavior = document.getElementById("behavior").value;
    //alert(behavior);
    //alert(cod);
    
    if (subject_id == '') {
        alert('Por favor debe seleccionar la Materia');
        document.getElementById("subject").focus();
    } else if (student == 0) {
        alert('Por favor debe seleccionar un Estudiante');
        document.getElementById("student").focus();
    } else if (tipo == '') {
        alert('Por favor debe seleccionar el Tipo de Conducta');
        document.getElementById("tipo").focus();
    } else if (behavior == '') {
        alert('Por favor debe ingresar la descripción del reporte');
        document.getElementById("behavior").focus();
    } else {
        var respuesta = confirm("¿Esta seguro en notificar los reportes conductuales?");
        if (respuesta == true){
            document.form_behavior.submit(); 
        }else{
            return false;
        }
    }
    
   
}
function imprim1(imp1){
    var printContents = document.getElementById('imp1').innerHTML;
    w = window.open();
    w.document.write(printContents);
    w.document.close(); // necessary for IE >= 10
    w.focus(); // necessary for IE >= 10
    w.print();
    w.close();
    return true;
}
function enviacheck(){
    //alert('hola');
    /*
    $('input[type=checkbox]:checked').each(function() {
    //console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") Seleccionado");
    alert("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") Seleccionado");
    });
    */
    
}


// Class definition
var KTSelect2 = function() {
 // Private functions
 var demos = function() {
  // basic
  $('#kt_select2_1').select2({
   placeholder: "Select a state"
  });

  // nested
  $('#kt_select2_2').select2({
   placeholder: "Select a state"
  });

  // multi select
  $('#kt_select2_3').select2({
   placeholder: "Seleccione Estudiante(s)",
  });

  // basic
  $('#kt_select2_4').select2({
   placeholder: "Select a state",
   allowClear: true
  });

  // loading data from array
  var data = [{
   id: 0,
   text: 'Enhancement'
  }, {
   id: 1,
   text: 'Bug'
  }, {
   id: 2,
   text: 'Duplicate'
  }, {
   id: 3,
   text: 'Invalid'
  }, {
   id: 4,
   text: 'Wontfix'
  }];

  $('#kt_select2_5').select2({
   placeholder: "Select a value",
   data: data
  });

  // loading remote data

  function formatRepo(repo) {
   if (repo.loading) return repo.text;
   var markup = "<div class='select2-result-repository clearfix'>" +
    "<div class='select2-result-repository__meta'>" +
    "<div class='select2-result-repository__title'>" + repo.full_name + "</div>";
   if (repo.description) {
    markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
   }
   markup += "<div class='select2-result-repository__statistics'>" +
    "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.forks_count + " Forks</div>" +
    "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.stargazers_count + " Stars</div>" +
    "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.watchers_count + " Watchers</div>" +
    "</div>" +
    "</div></div>";
   return markup;
  }

  function formatRepoSelection(repo) {
   return repo.full_name || repo.text;
  }

  $("#kt_select2_6").select2({
   placeholder: "Search for git repositories",
   allowClear: true,
   ajax: {
    url: "https://api.github.com/search/repositories",
    dataType: 'json',
    delay: 250,
    data: function(params) {
     return {
      q: params.term, // search term
      page: params.page
     };
    },
    processResults: function(data, params) {
     // parse the results into the format expected by Select2
     // since we are using custom formatting functions we do not need to
     // alter the remote JSON data, except to indicate that infinite
     // scrolling can be used
     params.page = params.page || 1;

     return {
      results: data.items,
      pagination: {
       more: (params.page * 30) < data.total_count
      }
     };
    },
    cache: true
   },
   escapeMarkup: function(markup) {
    return markup;
   }, // let our custom formatter work
   minimumInputLength: 1,
   templateResult: formatRepo, // omitted for brevity, see the source of this page
   templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
  });

  // custom styles

  // tagging support
  $('#kt_select2_12_1, #kt_select2_12_2, #kt_select2_12_3, #kt_select2_12_4').select2({
   placeholder: "Select an option",
  });

  // disabled mode
  $('#kt_select2_7').select2({
   placeholder: "Select an option"
  });

  // disabled results
  $('#kt_select2_8').select2({
   placeholder: "Select an option"
  });

  // limiting the number of selections
  $('#kt_select2_9').select2({
   placeholder: "Select an option",
   maximumSelectionLength: 2
  });

  // hiding the search box
  $('#kt_select2_10').select2({
   placeholder: "Select an option",
   minimumResultsForSearch: Infinity
  });

  // tagging support
  $('#kt_select2_11').select2({
   placeholder: "Add a tag",
   tags: true
  });

  // disabled results
  $('.kt-select2-general').select2({
   placeholder: "Select an option"
  });
 }

 var modalDemos = function() {
  $('#kt_select2_modal').on('shown.bs.modal', function () {
   // basic
   $('#kt_select2_1_modal').select2({
    placeholder: "Select a state"
   });

   // nested
   $('#kt_select2_2_modal').select2({
    placeholder: "Select a state"
   });

   // multi select
   $('#kt_select2_3_modal').select2({
    placeholder: "Select a Student",
   });

   // basic
   $('#kt_select2_4_modal').select2({
    placeholder: "Select a state",
    allowClear: true
   });
  });
 }

 // Public functions
 return {
  init: function() {
   demos();
   modalDemos();
  }
 };
}();

// Initialization
jQuery(document).ready(function() {
 KTSelect2.init();
});

</script>

<style type="text/css" media="screen">
.select2-search--inline {
    display: contents; /*this will make the container disappear, making the child the one who sets the width of the element*/
}
.select2-search__field:placeholder-shown {
    width: 100% !important; /*makes the placeholder to be 100% of the width while there are no options selected*/
}
</style>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Reporte Conductual - <?php echo $curso ?> - <?php echo $materia ?>
                    <span class="d-block text-muted pt-2 font-size-sm">Listado de Reportes Conductuales</span></h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <a href="" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#modal_teacher_edit">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" cx="9" cy="15" r="6" />
                                <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>Nuevo Reporte</a>
                    <!--end::Button-->
                    <!--begin::Dropdown-->
                    <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3" />
                                <path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>Acción</button>
                    <!--begin::Dropdown Menu-->
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                        <!--begin::Navigation-->
                        <ul class="navi flex-column navi-hover py-2">
                            <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Seleccione una opción:</li>
                            <li class="navi-item">
                                <a href="javascript:imprim1(imp1);" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="la la-print"></i>
                                    </span>
                                    <span class="navi-text">Imprimir</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="#" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="la la-file-excel-o"></i>
                                    </span>
                                    <span class="navi-text">Descargar Excel</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="#" class="navi-link" target="_blank">
                                    <span class="navi-icon">
                                        <i class="la la-file-pdf-o"></i>
                                    </span>
                                    <span class="navi-text">Descargar PDF</span>
                                </a>
                            </li>
                        </ul>
                        <!--end::Navigation-->
                    </div>
                    <!--end::Dropdown Menu-->
                <!--end::Dropdown-->
                </div>
            </div>
            <div class="card-body" id="imp1">
                <!--begin: Datatable-->
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Fecha</th>
                            <th>Estudiante</th>
                            <th>Reporte</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count=0;
                        /*
                        $consulta='SELECT t1.behavior_id, t1.student_id, CONCAT(t2.lastname, " ", t2.lastname2," ", t2.name) as student, 
                                    t3.name as subject, t4.name as teacher, t1.behavior, t1.date, t1.type FROM behaviors as t1 
                                    INNER JOIN student as t2 ON(t1.student_id=t2.student_id)
                                    INNER JOIN subject as t3 ON(t1.subject_id=t3.subject_id)
                                    INNER JOIN teacher as t4 ON(t3.teacher_id=t4.teacher_id)
                                    WHERE t3.section_id='.$section_id.' AND t3.teacher_id='.$teacher_id.' ORDER BY t1.date DESC';
                        //$students = $this->db->order_by('lastname', 'ASC')->get_where('student' , array('class_id'=>$class_id , 'section_id' => $row['section_id']))->result_array();
                        $students = $this->db->query($consulta)->result_array();
                        */
                        foreach($behaviors as $row):
                            setlocale(LC_ALL,"es_ES");
                            $newDate = date("d/m/Y", $row['date']);
                            $count+=1;
                        ?>
                        <tr class="<?php //if($row['type']==1){echo 'table-info';}else{echo'table-danger';} ?>" >
                            <th scope="row"><?php echo $newDate;?></th>
                            <td id="<?php echo $row['student_id'];?>" ><?php echo $row['student'];?></td>
                            <td><?php echo $row['behavior'];?></td>
                            <td><?php 
                            if ($row['viewed']==1) {
                                ?>
                                <span class="label label-lg label-light-success label-inline font-weight-bold py-4">Visto</span>
                                <?php
                            }else{
                                ?>
                                <span class="label label-lg label-light-danger label-inline font-weight-bold py-4">No visto</span>
                                <?php
                            }
                            ?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--begin::Modal-->
<div class="modal fade" id="modal_teacher_edit" tabindex="-1" role="dialog" aria-labelledby="modal_teacher_edit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Formulario de registro de reporte conductual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <!--<form class="form" action="<?php echo base_url(); ?>index.php/teacher/profile_edit" method="POST" >-->
                <?php //echo form_open(base_url() . 'index.php/teacher/add_behavior/'.$teacher_id.'/'.$section_id , array('class' => 'form','name' => 'form_teacher')); ?>
                <form action="<?php echo base_url(); ?>teacher/behavior_save" method="post" name="form_behavior" class="form" >
                <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label">Curso:</label>
                    <div class="col-lg-9 col-xl-6">
                        <input type="hidden" name="subject_id" id="subject_id" value="<?php echo $subject_id ?>" >
                        <input type="hidden" name="section_id" id="section_id" value="<?php echo $section_id ?>" >
                        <input type="text" name="curso_materia" id="curso_materia" class="form-control" value="<?php echo $nick_name ?> - <?php echo $materia ?>" disabled >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label">Estudiante(s):</label>
                    <div class="col-lg-9 col-xl-6">
                        <select class="form-control select2 " id="kt_select2_3" name="students[]" multiple="multiple">
                            <?php foreach($students as $row): ?>
                                <option value="<?php echo $row['student_id'];?>"><?php echo $row['student'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label">Tipo de Conducta:</label>
                    <div class="col-lg-9 col-xl-6">
                        <select id="tipo" name="tipo" class="form-control form-control-lg form-control-solid" required >
                            <option value="" >Tipo de Conducta</option>
                            <option value=1 >Positiva</option>
                            <option value=2 >Negativa</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fecha" class="col-xl-3 col-lg-3 col-form-label">Fecha de reporte:</label>
                    <div class="col-lg-9 col-xl-6">
                        <input class="form-control" type="date" id="fecha" name="fecha" value="<?php echo date("Y-m-d"); ?>" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label">Descripción:</label>
                    <div class="col-lg-9 col-xl-6">
                        <textarea class="form-control" id="behavior" name="behavior" rows="7" spellcheck="false" data-gramm="false" required ></textarea>
                    </div>
                </div>


                </form> 
                                                                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal" >Cancelar</button>
                <button type="button" class="btn btn-primary font-weight-bold" onclick="confirmar()" >Guardar Cambios</button>
            </div>                       
        </div>
    </div>
</div>
<!--end::Modal-->



           