<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailModel extends Model
{
    function generic_message($titulo = '', $cuerpo = '', $sender = '')
	{
		$msg = '';
        $msg .= $this->header();
        $msg .= '<p style="margin:0;">Estudiante: '.'</p>';
        $msg .= '<p style="margin:0;">Fecha: '.'</p>';
        $msg .= '<p style="margin:0;">Hora: '.'</p>';
        $msg .= '<p style="margin:0;">Detalle: '.'</p>';
        $msg .= $this->footer();
        $msg = str_replace("@title@", "Ausencia sin Licencia", $msg);
        return $msg;
	}
    function alert_3_parent($titulo = '', $student = '', $date = '')
	{
		$msg = '';
        $msg .= $this->header_generic();
        $msg .= '<p style="margin:0;">Estimados Padres de familia:'.'</p><br />';
        $msg .= '<p style="margin:0;">Este mensaje es una notificación automática del sistema SAAT del Colegio Tiquipaya. 
        Deseamos informarle que el estudiante: '.$student.', 
        matriculado en nuestra institución, ha acumulado un total de tres (3) faltas leves de indisciplina durante el presente ciclo escolar. '.'</p><br />';
        $msg .= '<p style="margin:0;">De acuerdo con nuestras políticas de disciplina escolar, 
        queremos recordarle que, en caso de que '.$student.' acumule una cuarta falta leve, 
        el estudiante cumplirá un periodo de retención en el colegio.
        Esta medida se toma con el objetivo de enfatizar la importancia del respeto a las normas y valores de nuestra institución y 
        asegurar un ambiente de aprendizaje adecuado para todos los estudiantes.'.'</p><br />';
        $msg .= '<p style="margin:0;">Para obtener más detalles y realizar el seguimiento de sus hij@s le aconsejamos ingresar a la plataforma en el enlace que se encuentra a continuación.'.'</p><br />';
        $msg .= '<p style="margin:0;">Estudiante: '.$student.'</p>';
        $msg .= '<p style="margin:0;">Fecha: '.$date.'</p>';
        $msg .= '<p style="margin:0;">Atentamente,</p>';
        $msg .= '<p style="margin:0;">Sistema SAAT - Colegio Tiquipaya</p><br />';
        $msg .= '<p style="margin:0;">Esta notificación es generada automáticamente por el sistema SAAT por favor no responder a este mensaje.</p>';
        
        $msg .= $this->footer_generic();
        $msg = str_replace("@title@", $titulo, $msg);
        return $msg;
	}
    function alert_3_director($titulo = '', $student = '', $curso = '')
	{
		$msg = '';
        $msg .= $this->header_generic();
        $msg .= '<p style="margin:0;">Estimado/a Director/a,</p><br />';
        $msg .= '<p style="margin:0;">Esta es una notificación del sistema SAAT alertando sobre la acumulación de 3 faltas leves del estudiante a continuación:</p><br />';
        $msg .= '<p style="margin:0;">Para obtener más detalles le aconsejamos ingresar a la plataforma en el enlace que se encuentra a continuación.'.'</p><br />';
        $msg .= '<p style="margin:0;">Estudiante: '.$student.'</p>';
        $msg .= '<p style="margin:0;">Curso: '.$curso.'</p>';
        $msg .= '<p style="margin:0;">Atentamente,</p>';
        $msg .= '<p style="margin:0;">Sistema SAAT - Colegio Tiquipaya</p>';
        $msg .= $this->footer_generic();
        $msg = str_replace("@title@", $titulo, $msg);
        return $msg;
	}
    function alert_4_parent($titulo = '', $student = '', $curso = '', $student_id = '')
	{
		$msg = '';
        $msg .= $this->header_generic();
        $msg .= '<p style="margin:0;">Estimado/a Padre o Tutor,'.'</p><br />';
        $msg .= '<p style="margin:0;">Esperamos que este mensaje le encuentre bien, , me veo en la necesidad de informarle que su hijo/a 
            <b>'.$student.' del '.$curso.'</b> ha acumulado 4 faltas leves sancionadas en mi asignatura y comunicadas de forma oportuna en nuestra plataforma SAAT, 
            y vía correo electrónico. A continuación adjuntamos el comunicado de detención con el detalle de las faltas: '.'</p><br />';
        $msg .= '<p style="margin:0;"><a href="'.base_url().'server/descargar_carta/'.$student_id.'/4">Descargar Carta de Detención</a>'.'</p><br />';
        $msg .= '<p style="margin:0;">Agradecemos su colaboración y comprensión en este asunto. <br />
        Estamos comprometidos en brindar el mejor apoyo a nuestros estudiantes y en asegurar que su experiencia educativa sea lo más enriquecedora posible.'.'</p><br />';
        $msg .= '<p style="margin:0;">Atentamente,</p>';
        $msg .= '<p style="margin:0;">Sistema SAAT - Colegio Tiquipaya</p>';
        $msg .= $this->footer_generic();
        $msg = str_replace("@title@", $titulo, $msg);
        return $msg;
	}
    function alert_4_director($titulo = '', $student = '', $curso = '', $student_id = '')
	{
		$msg = '';
        $msg .= $this->header_generic();
        $msg .= '<p style="margin:0;">Estimado/a Director/a,</p><br />';
        $msg .= '<p style="margin:0;">Esta es una notificación del sistema SAAT alertando sobre la acumulación de 4 faltas leves del estudiante a continuación:</p><br />';
        $msg .= '<p style="margin:0;">Para obtener más detalles le aconsejamos ingresar a la plataforma en el enlace que se encuentra a continuación.'.'</p><br />';
        $msg .= '<p style="margin:0;">Estudiante: '.$student.'</p>';
        $msg .= '<p style="margin:0;">Curso: '.$curso.'</p>';
        $msg .= '<p style="margin:0;"><a href="'.base_url().'server/descargar_carta/'.$student_id.'/4">Descargar Carta de Detención</a>'.'</p><br />';
        $msg .= '<p style="margin:0;">Los padres de familia fueron notificados sobre la DETENCIÓN de su hij@, 
        se adjunta la carta con el detalle de las faltas cometidas por el estudiante.'.'</p><br />';
        $msg .= '<p style="margin:0;">Atentamente,</p>';
        $msg .= '<p style="margin:0;">Sistema SAAT - Colegio Tiquipaya</p>';
        $msg .= $this->footer_generic();
        $msg = str_replace("@title@", $titulo, $msg);
        return $msg;
	}
    function alert_4_docentes($titulo = '', $student = '', $curso = '', $student_id = '')
	{
		$msg = '';
        $msg .= $this->header_generic();
        $msg .= '<p style="margin:0;">Estimado Profesor,</p><br />';
        $msg .= '<p style="margin:0;">Esta es una notificación del sistema SAAT alertando sobre la acumulación de 4 faltas leves del estudiante a continuación:</p><br />';
        $msg .= '<p style="margin:0;">Para obtener más detalles le aconsejamos ingresar a la plataforma en el enlace que se encuentra a continuación.'.'</p><br />';
        $msg .= '<p style="margin:0;">Estudiante: '.$student.'</p>';
        $msg .= '<p style="margin:0;">Curso: '.$curso.'</p>';
        $msg .= '<p style="margin:0;"><a href="'.base_url().'server/descargar_carta/'.$student_id.'/4">Descargar Carta de Detención</a>'.'</p><br />';
        $msg .= '<p style="margin:0;">Los padres de familia fueron notificados sobre la DETENCIÓN de su hij@, 
        se adjunta la carta con el detalle de las faltas cometidas por el estudiante.'.'</p><br />';
        $msg .= '<p style="margin:0;">Atentamente,</p>';
        $msg .= '<p style="margin:0;">Sistema SAAT - Colegio Tiquipaya</p>';
        $msg .= $this->footer_generic();
        $msg = str_replace("@title@", $titulo, $msg);
        return $msg;
	}
    function alert_5_parent($titulo = '', $student = '', $curso = '', $student_id = '')
	{
		$msg = '';
        $msg .= $this->header_generic();
        $msg .= '<p style="margin:0;">Estimado/a Padre o Tutor,'.'</p><br />';
        $msg .= '<p style="margin:0;">Esperamos que este mensaje le encuentre bien, , me veo en la necesidad de informarle que su hijo/a 
            <b>'.$student.' del '.$curso.'</b> ha acumulado 5 faltas leves sancionadas en mi asignatura y comunicadas de forma oportuna en nuestra plataforma SAAT, 
            y vía correo electrónico. A continuación adjuntamos el comunicado de detención con el detalle de las faltas: '.'</p><br />';
        $msg .= '<p style="margin:0;"><a href="'.base_url().'server/descargar_carta/'.$student_id.'/5">Descargar Carta de Detención</a>'.'</p><br />';
        $msg .= '<p style="margin:0;">Agradecemos su colaboración y comprensión en este asunto. <br />
        Estamos comprometidos en brindar el mejor apoyo a nuestros estudiantes y en asegurar que su experiencia educativa sea lo más enriquecedora posible.'.'</p><br />';
        $msg .= '<p style="margin:0;">Atentamente,</p>';
        $msg .= '<p style="margin:0;">Sistema SAAT - Colegio Tiquipaya</p>';
        $msg .= $this->footer_generic();
        $msg = str_replace("@title@", $titulo, $msg);
        return $msg;
	}
    function alert_7_parent($titulo = '', $student = '', $date = '')
	{
		$msg = '';
        $msg .= $this->header_generic();
        $msg .= '<p style="margin:0;">Estimados Padres de familia:'.'</p><br />';
        $msg .= '<p style="margin:0;">Este mensaje es una notificación automática del sistema Saat del Colegio Tiquipaya. Informamos que el estudiante: '.$student.', 
        matriculado en nuestra institución, ha acumulado un total de tres (7) faltas leves de indisciplina durante el presente ciclo escolar, lo que se traduce en una falta grave.</p><br />';
        $msg .= '<p style="margin:0;">De acuerdo con nuestras políticas de disciplina escolar, el estudiante recibirá una boleta, 
        misma que fue advertida en la reunión sostenida al llegar a las 4 faltas leves. 
        Esta medida se toma con el objetivo de enfatizar la importancia del respeto a las normas y valores de nuestra institución y 
        asegurar un ambiente de aprendizaje adecuado para todos los estudiantes.</p><br />';
        $msg .= '<p style="margin:0;">Para obtener más detalles y realizar el seguimiento de sus hij@s le aconsejamos ingresar a la plataforma en el enlace que se encuentra a continuación.'.'</p><br />';
        $msg .= '<p style="margin:0;">Estudiante: '.$student.'</p>';
        $msg .= '<p style="margin:0;">Fecha: '.$date.'</p>';
        $msg .= '<p style="margin:0;">Atentamente,</p>';
        $msg .= '<p style="margin:0;">Sistema SAAT - Colegio Tiquipaya</p>';
        $msg .= $this->footer_generic();
        $msg = str_replace("@title@", $titulo, $msg);
        return $msg;
	}
    function alert_7_director($titulo = '', $student = '', $curso = '')
	{
		$msg = '';
        $msg .= $this->header_generic();
        $msg .= '<p style="margin:0;">Estimado/a Director/a,</p><br />';
        $msg .= '<p style="margin:0;">Esta es una notificación del sistema Saat alertando sobre la acumulación de 7 faltas del estudiante a continuación,</p><br />';
        $msg .= '<p style="margin:0;">Para obtener más detalles le aconsejamos ingresar a la plataforma en el enlace que se encuentra a continuación.'.'</p><br />';
        $msg .= '<p style="margin:0;">Estudiante: '.$student.'</p>';
        $msg .= '<p style="margin:0;">Curso: '.$curso.'</p>';
        $msg .= '<p style="margin:0;">Se le solicita proceder con el protocolo de generación de boleta por acumulación de faltas leves.</p><br />';
        $msg .= '<p style="margin:0;">Atentamente,</p>';
        $msg .= '<p style="margin:0;">Sistema SAAT - Colegio Tiquipaya</p>';
        $msg .= $this->footer_generic();
        $msg = str_replace("@title@", $titulo, $msg);
        return $msg;
	}
    function alert_7_docentes($titulo = '', $student = '', $curso = '')
	{
		$msg = '';
        $msg .= $this->header_generic();
        $msg .= '<p style="margin:0;">Estimado Profesor,</p><br />';
        $msg .= '<p style="margin:0;">Esta es una notificación del sistema Saat informando sobre la acumulación de 7 faltas leves del estudiante a continuación:</p><br />';
        $msg .= '<p style="margin:0;">Para obtener más detalles le aconsejamos ingresar a la plataforma en el enlace que se encuentra a continuación.'.'</p><br />';
        $msg .= '<p style="margin:0;">Estudiante: '.$student.'</p>';
        $msg .= '<p style="margin:0;">Curso: '.$curso.'</p>';
        $msg .= '<p style="margin:0;">El director técnico del nivel activará el protocolo de generación de boleta.'.'</p><br />';
        $msg .= '<p style="margin:0;">Atentamente,</p>';
        $msg .= '<p style="margin:0;">Sistema SAAT - Colegio Tiquipaya</p>';
        $msg .= $this->footer_generic();
        $msg = str_replace("@title@", $titulo, $msg);
        return $msg;
	}
    function alert_8_parent($titulo = '', $student = '', $date = '')
	{
		$msg = '';
        $msg .= $this->header_generic();
        $msg .= '<p style="margin:0;">Estimados Padres de familia:'.'</p><br />';
        $msg .= '<p style="margin:0;">Este mensaje es una notificación automática del sistema Saat para informar sobre la reincidencia de faltas 
        y el incumplimiento del compromiso por parte de:</p><br />';
        $msg .= '<p style="margin:0;">Estudiante: '.$student.'</p>';
        $msg .= '<p style="margin:0;">Fecha: '.$date.'</p>';
        $msg .= '<p style="margin:0;">Atentamente,</p>';
        $msg .= '<p style="margin:0;">Sistema SAAT - Colegio Tiquipaya</p>';
        $msg .= $this->footer_generic();
        $msg = str_replace("@title@", $titulo, $msg);
        return $msg;
	}
    function alert_8_director($titulo = '', $student = '', $curso = '')
	{
		$msg = '';
        $msg .= $this->header_generic();
        $msg .= '<p style="margin:0;">Estimado/a Director/a,</p><br />';
        $msg .= '<p style="margin:0;">Este mensaje es una notificación automática del sistema Saat para informar sobre la reincidencia de faltas 
        y el incumplimiento del compromiso por parte de:</p><br />';
        $msg .= '<p style="margin:0;">Estudiante: '.$student.'</p>';
        $msg .= '<p style="margin:0;">Curso: '.$curso.'</p>';
        $msg .= '<p style="margin:0;">Por favor realizar el seguimiento correspondiente.</p><br />';
        $msg .= '<p style="margin:0;">Atentamente,</p>';
        $msg .= '<p style="margin:0;">Sistema SAAT - Colegio Tiquipaya</p>';
        $msg .= $this->footer_generic();
        $msg = str_replace("@title@", $titulo, $msg);
        return $msg;
	}
    function behavior_msg($titulo = '', $student = '', $materia = '', $behavior = '')
	{
		$msg = '';
        $msg .= $this->header_generic();
        $msg .= '<p style="margin:0;">Estimado Padre de Familia,</p><br />';
        $msg .= '<p style="margin:0;">Este mensaje es una notificación automática del sistema Saat, adjuntamos los detalles de la comunicación:</p><br />';
        $msg .= '<p style="margin:0;">Estudiante: '.$student.'</p>';
        $msg .= '<p style="margin:0;">Materia: '.$materia.'</p>';
        $msg .= '<p style="margin:0;">Comunicado: '.$behavior.'</p><br />';
        $msg .= '<p style="margin:0;">Agradecemos su atención.</p><br />';
        $msg .= '<p style="margin:0;">Atentamente,</p>';
        $msg .= '<p style="margin:0;">Sistema SAAT - Colegio Tiquipaya</p>';
        $msg .= $this->footer_generic();
        $msg = str_replace("@title@", $titulo, $msg);
        return $msg;
	}
	function behavior_email($student = '', $materia = '', $behavior = '', $email = '')
	{
		$email_msg = $this->behavior_html($student , $materia , $behavior);
        $num = rand(1,100); 
		$email_sub = 'ID:'.$num.' - Notificación U. E. Tiquipaya';
		$email_to	=	$email;
		//$email_to	=	'saat@tiquipaya.edu.bo';

        $headers  = "From: Saat Tiquipaya <saat@tiquipaya.edu.bo>;";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n"; 
		mail($email_to, $email_sub, $email_msg, $headers);
		return true;
	}
    function license_auth_email($student = '', $tipo = '', $inicio = '', $fin = '', $obs = '', $motivo = '', $solicitante = '', $fecha = '')
	{
        $titulo = $motivo;
        $resp = "Nos complace informarles que su solicitud de licencia ha sido aprobada.";
        $msg = '';
        $msg = $this->license_html_auth();
        $msg = str_replace("@respuesta@", $resp , $msg);
        $msg = str_replace("@student@", $student , $msg);
        $msg = str_replace("@solicitante@", $solicitante , $msg);
        $msg = str_replace("@fecha@", $fecha , $msg);
        $msg = str_replace("@inicio@", $inicio , $msg);
        $msg = str_replace("@fin@", $fin , $msg);
        $msg = str_replace("@licencia@", $motivo.' - '.$obs , $msg);
        $msg = str_replace("@title@", $titulo , $msg);
        return $msg;
    }
    function license_noauth_email($student = '', $tipo = '', $inicio = '', $fin = '', $obs = '', $motivo = '', $solicitante = '', $fecha = '')
	{
        $titulo = $motivo;
        $resp = "Lamentamos informarle que su solicitud de licencia no ha sido aprobada. Para más información, por favor, contacte con Secretaría de Nivel.";
        $msg = '';
        $msg = $this->license_html_auth();
        $msg = str_replace("@respuesta@", $resp , $msg);
        $msg = str_replace("@student@", $student , $msg);
        $msg = str_replace("@solicitante@", $solicitante , $msg);
        $msg = str_replace("@fecha@", $fecha , $msg);
        $msg = str_replace("@inicio@", $inicio , $msg);
        $msg = str_replace("@fin@", $fin , $msg);
        $msg = str_replace("@licencia@", $motivo.' - '.$obs , $msg);
        $msg = str_replace("@title@", $titulo , $msg);
        return $msg;
    }
    function assistance_email($student = '', $tipo = '', $inicio = '', $fin = '', $obs = '', $motivo = '')
	{
        $tipo_licencia = "";
        $titulo = $motivo;
        $msg = '';
        $msg .= $this->header();
        $msg .= '<p style="margin:0;">Estudiante: '.$student.'</p>';
        $msg .= '<p style="margin:0;">'.$tipo_licencia.' Inicio: '.$inicio.'</p>';
        $msg .= '<p style="margin:0;">'.$tipo_licencia.' Fin: '.$fin.'</p>';
        $msg .= '<p style="margin:0;">Detalle: '.$obs.'</p>';
        $msg .= $this->footer();
        $msg = str_replace("@title@", $titulo , $msg);
        return $msg;
    }
    function absence_email($student = '', $fecha = '', $hora = '', $detalle = '')
	{
        $msg = '';
        $msg .= $this->header();
        $msg .= '<p style="margin:0;">Estudiante: '.$student.'</p>';
        $msg .= '<p style="margin:0;">Fecha: '.$fecha.'</p>';
        $msg .= '<p style="margin:0;">Hora: '.$hora.'</p>';
        $msg .= '<p style="margin:0;">Detalle: '.$detalle.'</p>';
        $msg .= $this->footer();
        $msg = str_replace("@title@", "Ausencia sin Licencia", $msg);
        return $msg;
    }
    function behavior_html($student = '', $materia = '', $behavior = '')
	{
		$msg = '
		<body style="margin:0;padding:0;word-spacing:normal;background-color:#939297;">
			<style>
				table, td, div, h1, p {
					font-family: Arial, sans-serif;
				}
				@media screen and (max-width: 530px) {
					.unsub {
						display: block;
						padding: 8px;
						margin-top: 14px;
						border-radius: 6px;
						background-color: #555555;
						text-decoration: none !important;
						font-weight: bold;
					}
					.col-lge {
						max-width: 100% !important;
					}
				}
				@media screen and (min-width: 531px) {
					.col-sml {
						max-width: 27% !important;
					}
					.col-lge {
						max-width: 73% !important;
					}
				}
			</style>
			<div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#939297;">
				<table role="presentation" style="width:100%;border:none;border-spacing:0;">
					<tr>
						<td align="center" style="padding:0;">
							<table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
								<tr>
									<td style="padding:40px 30px 30px 30px;text-align:center;font-size:24px;font-weight:bold;">
		
									</td>
								</tr>
								<tr>
									<td style="padding:30px;background-color:#ffffff;">
										<h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">Plataforma SAAT</h1>
										<p style="margin:0;">Sistema Administrativo Académico Colegio Tiquipaya</p>
									</td>
								</tr>
								<tr>
		
								</tr>
								<tr>
									<td style="padding:35px 30px 11px 30px;font-size:0;background-color:#ffffff;border-bottom:1px solid #f0f0f5;border-color:rgba(201,201,207,.35);">
										<div class="col-sml" style="display:inline-block;width:100%;max-width:145px;vertical-align:top;text-align:left;font-family:Arial,sans-serif;font-size:14px;color:#363636;">
											<img src="https://home.tiquipaya.edu.bo/wp-content/uploads/2021/08/logo.png" width="115" alt="" style="width:115px;max-width:80%;margin-bottom:20px;">
										</div>
										<div class="col-lge" style="display:inline-block;width:100%;max-width:395px;vertical-align:top;padding-bottom:20px;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
											<p style="margin-top:0;margin-bottom:12px;">Estimados Padres de familia:</p>
											<p style="margin-top:0;margin-bottom:18px;">Mediante la presente le comunicamos que su hijo/a tiene un registro Académico-Conductual en la plataforma SAAT</p>
											<p style="margin:0;"><a href="https://tiquipaya.edu.bo/plataforma" target="_blank" style="background: #ff3884; text-decoration: none; padding: 10px 25px; color: #ffffff; border-radius: 4px; display:inline-block; mso-padding-alt:0;text-underline-color:#ff3884"><!--[if mso]><i style="letter-spacing: 25px;mso-font-width:-100%;mso-text-raise:20pt">&nbsp;</i><![endif]--><span style="mso-text-raise:10pt;font-weight:bold;">Acceder a la Plataforma</span><!--[if mso]><i style="letter-spacing: 25px;mso-font-width:-100%">&nbsp;</i><![endif]--></a></p>
										</div>
									</td>
								</tr>
								<tr>
									<td style="padding:10px;font-size:24px;line-height:28px;font-weight:bold;background-color:#ffffff;border-bottom:1px solid #f0f0f5;border-color:rgba(201,201,207,.35);">
									</td>
								</tr>
								<tr>
									<td style="padding:30px;background-color:#ffffff;">
										<p style="margin:0;">Detalle</p>
										<p style="margin:0;">Estudiante: @student@</p>
										<p style="margin:0;">Materia: @subject@</p>
										<p style="margin:0;">Detalle: @reporte@</p>
										<p style="margin:0 font-size:14px;line-height:20px;">Esta notificación es generada automáticamente por el sistema SAAT por favor no responder a este mensaje.</p>
										<p style="margin:0;">Agradecemos su atención</p>
									</td>
								</tr>
								<tr>
									<td style="padding:30px;text-align:center;font-size:12px;background-color:#404040;color:#cccccc;">
										<p style="margin:0 0 8px 0;">
											<a href="https://wa.me/59176965562" target="_blank" style="text-decoration:none;"><img src="http://assets.stickpng.com/images/580b57fcd9996e24bc43c543.png" width="40" height="40" alt="f" style="display:inline-block;color:#cccccc;"></a>
										</p>
										<p style="margin:0;font-size:14px;line-height:20px;">Soporte<br></p>
										<p style="margin:0;font-size:14px;line-height:20px;">&reg; Colegio Tiquipaya 2023 - Departamento Informático<br><!--<a class="unsub" href="http://www.example.com/" style="color:#cccccc;text-decoration:underline;">Dejar de recibir reportes</a>--></p>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</body>';
		$msg = str_replace("@student@", $student, $msg);
		$msg = str_replace("@subject@", $materia, $msg);
		$msg = str_replace("@reporte@", $behavior, $msg);
		return $msg;
	}
    function header()
    {
        $header = '
        <body style="margin:0;padding:0;word-spacing:normal;background-color:#939297;">
            <style>
                table, td, div, h1, p {
                    font-family: Arial, sans-serif;
                }
                @media screen and (max-width: 530px) {
                    .unsub {
                        display: block;
                        padding: 8px;
                        margin-top: 14px;
                        border-radius: 6px;
                        background-color: #555555;
                        text-decoration: none !important;
                        font-weight: bold;
                    }
                    .col-lge {
                        max-width: 100% !important;
                    }
                }
                @media screen and (min-width: 531px) {
                    .col-sml {
                        max-width: 27% !important;
                    }
                    .col-lge {
                        max-width: 73% !important;
                    }
                }
            </style>
            <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#939297;">
                <table role="presentation" style="width:100%;border:none;border-spacing:0;">
                    <tr>
                        <td align="center" style="padding:0;">
                            <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
                                <tr>
                                    <td style="padding:40px 30px 30px 30px;text-align:center;font-size:24px;font-weight:bold;">
        
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:30px;background-color:#ffffff;">
                                        <h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">Plataforma SAAT</h1>
                                        <p style="margin:0;">@title@</p>
                                    </td>
                                </tr>
                                <tr>
        
                                </tr>
                                <tr>
                                    <td style="padding:35px 30px 11px 30px;font-size:0;background-color:#ffffff;border-bottom:1px solid #f0f0f5;border-color:rgba(201,201,207,.35);">
                                        <div class="col-sml" style="display:inline-block;width:100%;max-width:145px;vertical-align:top;text-align:left;font-family:Arial,sans-serif;font-size:14px;color:#363636;">
                                            <img src="https://home.tiquipaya.edu.bo/wp-content/uploads/2021/08/logo.png" width="115" alt="" style="width:115px;max-width:80%;margin-bottom:20px;">
                                        </div>
                                        <div class="col-lge" style="display:inline-block;width:100%;max-width:395px;vertical-align:top;padding-bottom:20px;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
                                            <p style="margin-top:0;margin-bottom:12px;">Estimados Padres de familia:</p>
                                            <p style="margin-top:0;margin-bottom:18px;">Mediante la presente se notifica que su hijo/a tiene una notificación de la plataforma SAAT</p>
                                            <p style="margin:0;"><a href="https://tiquipaya.edu.bo/plataforma" target="_blank" style="background: #ff3884; text-decoration: none; padding: 10px 25px; color: #ffffff; border-radius: 4px; display:inline-block; mso-padding-alt:0;text-underline-color:#ff3884"><!--[if mso]><i style="letter-spacing: 25px;mso-font-width:-100%;mso-text-raise:20pt">&nbsp;</i><![endif]--><span style="mso-text-raise:10pt;font-weight:bold;">Acceder a la Plataforma</span><!--[if mso]><i style="letter-spacing: 25px;mso-font-width:-100%">&nbsp;</i><![endif]--></a></p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:10px;font-size:24px;line-height:28px;font-weight:bold;background-color:#ffffff;border-bottom:1px solid #f0f0f5;border-color:rgba(201,201,207,.35);">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:30px;background-color:#ffffff;">
                                        <div class="col-sml" style="display:inline-block;width:100%;max-width:145px;vertical-align:top;text-align:left;font-family:Arial,sans-serif;font-size:14px;color:#363636;">
                                            <img src="https://tiquipaya.edu.bo/logotiqui.png" width="115" alt="" style="width:115px;max-width:80%;margin-bottom:20px;">
                                        </div>';
                                    
        return $header;
    }
    function footer()
    {
        $footer = ('</td>
                </tr>
                <tr>
                    <td style="padding:30px;text-align:center;font-size:12px;background-color:#404040;color:#cccccc;">
                        <p style="margin:0 0 8px 0;">
                            <a href="https://wa.me/59176965562" target="_blank" style="text-decoration:none;"><img src="http://assets.stickpng.com/images/580b57fcd9996e24bc43c543.png" width="40" height="40" alt="f" style="display:inline-block;color:#cccccc;"></a>
                        </p>
                        <p style="margin:0;font-size:14px;line-height:20px;">Soporte<br></p>
                        <p style="margin:0;font-size:14px;line-height:20px;">&reg; Colegio Tiquipaya 2023 - Departamento Informático<br><!--<a class="unsub" href="http://www.example.com/" style="color:#cccccc;text-decoration:underline;">Dejar de recibir reportes</a>--></p>
                    </td>
                </tr>
            </table>
        </td>
        </tr>
        </table>
        </div>
        </body>');
        return $footer;
    }

    function header_generic()
    {
        $header = '
        <body style="margin:0;padding:0;word-spacing:normal;background-color:#939297;">
            <style>
                table, td, div, h1, p {
                    font-family: Arial, sans-serif;
                }
                @media screen and (max-width: 530px) {
                    .unsub {
                        display: block;
                        padding: 8px;
                        margin-top: 14px;
                        border-radius: 6px;
                        background-color: #555555;
                        text-decoration: none !important;
                        font-weight: bold;
                    }
                    .col-lge {
                        max-width: 100% !important;
                    }
                }
                @media screen and (min-width: 531px) {
                    .col-sml {
                        max-width: 27% !important;
                    }
                    .col-lge {
                        max-width: 73% !important;
                    }
                }
            </style>
            <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#939297;">
                <table role="presentation" style="width:100%;border:none;border-spacing:0;">
                    <tr>
                        <td align="center" style="padding:0;">
                            <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
                                <tr>
                                    <td style="padding:40px 30px 30px 30px;text-align:center;font-size:24px;font-weight:bold;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:30px;background-color:#ffffff;">
                                        <div class="col-sml" style="display:inline-block;width:100%;max-width:145px;vertical-align:top;text-align:left;font-family:Arial,sans-serif;font-size:14px;color:#363636;">
                                            <img src="https://tiquipaya.edu.bo/logotiqui.png" width="115" alt="" style="width:115px;max-width:80%;margin-bottom:20px;">
                                        </div>
                                        <h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">Plataforma SAAT</h1>
                                        <p style="margin:0;">@title@</p>
                                    </td>
                                </tr>
                        <td style="padding:30px;background-color:#ffffff;">';
        return $header;
    }
    function footer_generic()
    {
        $footer = ('
                        <div class="col-sml" style="display:inline-block;width:100%;max-width:145px;vertical-align:top;text-align:left;font-family:Arial,sans-serif;font-size:14px;color:#363636;">
                            <img src="https://home.tiquipaya.edu.bo/wp-content/uploads/2021/08/logo.png" width="115" alt="" style="width:115px;max-width:80%;margin-bottom:20px;">
                        </div>
                        <div class="col-lge" style="display:inline-block;width:100%;max-width:395px;vertical-align:top;padding-bottom:20px;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
                            <p style="margin:0;"><a href="https://tiquipaya.edu.bo/plataforma" target="_blank" style="background: #ff3884; text-decoration: none; padding: 10px 25px; color: #ffffff; border-radius: 4px; display:inline-block; mso-padding-alt:0;text-underline-color:#ff3884"><!--[if mso]><i style="letter-spacing: 25px;mso-font-width:-100%;mso-text-raise:20pt">&nbsp;</i><![endif]--><span style="mso-text-raise:10pt;font-weight:bold;">Acceder a la Plataforma</span><!--[if mso]><i style="letter-spacing: 25px;mso-font-width:-100%">&nbsp;</i><![endif]--></a></p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px;text-align:center;font-size:12px;background-color:#404040;color:#cccccc;">
                        <p style="margin:0 0 8px 0;">
                            <a href="https://wa.me/59176965562" target="_blank" style="text-decoration:none;"><img src="http://assets.stickpng.com/images/580b57fcd9996e24bc43c543.png" width="40" height="40" alt="f" style="display:inline-block;color:#cccccc;"></a>
                        </p>
                        <p style="margin:0;font-size:14px;line-height:20px;">Soporte<br></p>
                        <p style="margin:0;font-size:14px;line-height:20px;">&reg; Colegio Tiquipaya - Departamento Informático<br><!--<a class="unsub" href="http://www.example.com/" style="color:#cccccc;text-decoration:underline;">Dejar de recibir reportes</a>--></p>
                    </td>
                </tr>
            </table>
        </td>
        </tr>
        </table>
        </div>
        </body>');
        return $footer;
    }
    function license_html_auth()
    {
        $license_html_auth = ('
            <!DOCTYPE html>
            <html lang="es" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width,initial-scale=1">
                <meta name="x-apple-disable-message-reformatting">
                <title></title>
                <style>
                    table, td, div, h1, p {
                        font-family: Arial, sans-serif;
                    }
                    @media screen and (max-width: 530px) {
                        .unsub {
                            display: block;
                            padding: 8px;
                            margin-top: 14px;
                            border-radius: 6px;
                            background-color: #220e0e;
                            text-decoration: none !important;
                            font-weight: bold;
                        }
                        .col-lge {
                            max-width: 100% !important;
                        }
                    }
                    @media screen and (min-width: 531px) {
                        .col-sml {
                            max-width: 27% !important;
                        }
                        .col-lge {
                            max-width: 73% !important;
                        }
                    }
                </style>
            </head>

            <body style="margin:0;padding:0;word-spacing:normal;background-color:#40346e;">
                <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#bbb0e9;">
                    <table role="presentation" style="width:100%;border:none;border-spacing:0;">
                        <tr>
                            <td align="center" style="padding:0;">
                                <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
                                    <tr>
                                        <td style="padding:40px 30px 30px 30px;text-align:center;font-size:24px;font-weight:bold;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:30px;background-color:#f8f1f1;">
                                            <h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">Plataforma SAAT</h1>
                                            <p style="margin:0;">@title@</p>
                                        </td>
                                    </tr>
                                    <tr>
                                    </tr>
                                    <tr>
                                        <td style="padding:35px 30px 11px 30px;font-size:0;background-color:#ffffff;border-bottom:1px solid #f0f0f5;border-color:rgba(201,201,207,.35);">
                                            <div class="col-sml" style="display:inline-block;width:100%;max-width:145px;vertical-align:top;text-align:left;font-family:Arial,sans-serif;font-size:14px;color:#363636;">
                                                <img src="https://tiquipaya.edu.bo/logotiqui.png" width="115" alt="" style="width:115px;max-width:80%;margin-bottom:20px;">
                                            </div>
                                            <div class="col-lge" style="display:inline-block;width:100%;max-width:395px;vertical-align:top;padding-bottom:20px;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
                                                <p style="margin-top:0;margin-bottom:12px;text-align:justify;">Estimados Padres de familia:</p>
                                                <p style="margin-top:0;margin-bottom:18px;text-align:justify;">@respuesta@</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                    </tr>
                                    <tr>
                                        <td style="padding:30px;background-color:#f8f1f1;">
                                            <p style="margin:0;">Estudiante: @student@</p>
                                            <p style="margin:0;">Fecha: @fecha@</p>
                                            <p style="margin:0;">Solicitado por: @solicitante@</p>
                                            <p style="margin:0;">Inicio: @inicio@</p>
                                            <p style="margin:0;">Fin: @fin@</p>
                                            <p style="margin:0;">Detalle: @licencia@</p>
                                            <p style="margin:0 font-size:14px;line-height:20px;">Esta notificación es generada automáticamente por el sistema SAAT. Por favor, no responda a este mensaje.</p>
                                            <p style="margin:0; text-align:center;">Agradecemos su atención.</p>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td style="padding:30px;text-align:center;font-size:12px;background-color:#404040;color:#ffffff;">
                                            <p  style="margin:0;font-size:14px;line-height:20px;">Correo de Secretaria de Nivel Secundario: secretaria.sec@tiquipaya.edu.bo<br></p>
                                            <p  style="margin:0;font-size:14px;line-height:20px;">Número de Soporte Informático: 76965562 (solo mensajes de WhatsApp).<br></p>
                                            <p style="margin:0;font-size:14px;line-height:20px;">&reg; Colegio Tiquipaya - Departamento Informático<br></p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </body>
            </html>
            ');
        return $license_html_auth;
    }
}