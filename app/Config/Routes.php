<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Login::index');
$routes->post('/login', 'Login::login');
$routes->get('/logout', 'Login::logout');
$routes->get('/forgot_password', 'Login::forgot_password');
$routes->get('/forgot_password/(:num)', 'Login::forgot_password/$1');
$routes->post('/restore_password', 'Login::restore_password');
$routes->post('/login/save_feedback', 'Login::save_feedback');
$routes->get('/admin/feedback_manager', 'Admin::feedback_manager');

//Ruta del Modal
$routes->get('modal/popup/modal_interview_add/(:num)', 'Modal::popup/modal_interview_add/$1');
$routes->get('modal/popup/(:any)', 'Modal::popup/$1');
$routes->get('modal/popup_all/(:any)', 'Modal::popup_all/$1');

$routes->get('/inscripcion', 'Inscripciones::index');
$routes->post('/login_inscripcion', 'Login::login_inscripcion');
$routes->get('/logout_inscripcion', 'Login::logout_inscripcion');
$routes->get('/inscripcion_inicio', 'Inscripciones::inscripcion_inicio');
$routes->get('/inscripcion_error', 'Inscripciones::inscripcion_error');
$routes->post('/inscripcion_family', 'Inscripciones::inscripcion_family');
$routes->get('/inscripcion_rude/(:num)', 'Inscripciones::down_rude/$1');
$routes->get('/informe_family/(:num)', 'Inscripciones::informe_family/$1');

//***************************** ADMINISTRADOR ************************/
$routes->get('admin/dashboard', 'Admin::dashboard');
$routes->get('admin/section_bth', 'Admin::section_bth');
$routes->get('admin/create_notes_bth/(:any)', 'Admin::create_notes_bth/$1');
$routes->get('admin/centralize_notes_bth/(:any)', 'Admin::centralize_notes_bth/$1');
$routes->get('admin/centralizador_bth/(:any)', 'Admin::centralizador_bth/$1');
$routes->get('admin/test_email', 'Admin::test_email');
$routes->post('admin/send_mail_smtp', 'Admin::send_mail_smtp');
$routes->post('admin/send_mail_php', 'Admin::send_mail_php');
$routes->get('admin/sections_dir', 'Admin::sections_dir');
$routes->get('admin/subjects_section/(:any)', 'Admin::subjects_section/$1');
$routes->get('admin/dg_continuity_results', 'Admin::dg_continuity_results');
$routes->get('admin/update_tables', 'Admin::update_tables');
$routes->get('admin/update_student', 'Admin::update_student');
$routes->get('admin/nivel', 'Admin::nivel');
$routes->get('admin/nivel_get/(:num)', 'Admin::nivel_get/$1');
$routes->post('admin/nivel_create', 'Admin::nivel_create');
$routes->post('admin/nivel_update', 'Admin::nivel_update');
$routes->post('admin/nivel_delete', 'Admin::nivel_delete');

$routes->get('admin/periodo', 'Admin::periodo');
$routes->get('admin/periodo_get/(:num)', 'Admin::periodo_get/$1');
$routes->post('admin/periodo_create', 'Admin::periodo_create');
$routes->post('admin/periodo_update', 'Admin::periodo_update');
$routes->post('admin/periodo_delete', 'Admin::periodo_delete');

//***************************** MANAGER ************************/
$routes->get('manager/dashboard', 'Manager::dashboard');
$routes->get('manager/profile', 'Manager::profile');
$routes->get('manager/students_list', 'Manager::students_list');
$routes->post('manager/profile_update', 'Manager::profile_update');
$routes->post('manager/password_update', 'Manager::password_update');
$routes->get('manager/virtual_library_prim', 'Manager::virtual_library_prim');
$routes->get('manager/virtual_library_sec', 'Manager::virtual_library_sec');
$routes->get('manager/delays/(:num)', 'Manager::delays/$1');
$routes->get('manager/delay_get/(:num)', 'Manager::delay_get/$1');
$routes->post('manager/delay_create', 'Manager::delay_create');
$routes->post('manager/delay_update', 'Manager::delay_update');
$routes->post('manager/delay_delete', 'Manager::delay_delete');
$routes->get('manager/delay_xlsx/(:any)', 'Manager::delay_xlsx/$1');
$routes->get('manager/delays_day_new', 'Manager::delays_day_new');
$routes->post('manager/delays_day_new', 'Manager::delays_day_new');
$routes->get('manager/delay_score', 'Manager::delay_score');
$routes->get('manager/directivo', 'Manager::directivo');
$routes->get('manager/self_director', 'Manager::self_director');
$routes->get('manager/evaluation_planner', 'Manager::evaluation_planner');
$routes->post('manager/get_manager_calendar_events', 'Manager::get_manager_calendar_events');
$routes->post('manager/get_all_evaluations_manager', 'Manager::get_all_evaluations_manager');
$routes->get('manager/student_search_ajax/(:any)', 'Manager::student_search_ajax/$1');
$routes->get('manager/student_summary/(:num)', 'Manager::student_summary/$1');
$routes->get('manager/class_dir', 'Manager::class_dir');
$routes->get('manager/cartas_contenido', 'Manager::cartas_contenido');
$routes->get('manager/incidencias', 'Manager::incidencias');
$routes->get('manager/incidencias/seccion/(:num)', 'Manager::incidencias_seccion/$1');
$routes->get('manager/incidencias/search_students', 'Manager::incidencias_search_students');
$routes->get('manager/incidencias/student/(:num)', 'Manager::incidencias_student/$1');

$routes->get('manager/student_search/(:any)', 'Manager::student_search/$1');
$routes->get('manager/family_search/(:any)', 'Manager::family_search/$1');
$routes->get('manager/family_info/(:any)', 'Manager::family_info/$1');
$routes->get('manager/student_attendance/(:any)', 'Manager::student_attendance/$1');
$routes->get('manager/student_licenses/(:any)', 'Manager::student_licenses/$1');
$routes->get('manager/student_infractions/(:any)', 'Manager::student_infractions/$1');
$routes->get('manager/student_absences/(:any)', 'Manager::student_absences/$1');
$routes->get('manager/student_delays/(:any)', 'Manager::student_delays/$1');
$routes->get('manager/notes_half_student/(:any)', 'Manager::notes_half_student/$1');
$routes->get('manager/student_notes/(:any)', 'Manager::student_notes/$1');
$routes->post('manager/student_all', 'Manager::student_all');
$routes->get('manager/ecategoria', 'Manager::ecategoria');
$routes->get('manager/ecategoria_get/(:num)', 'Manager::ecategoria_get/$1');
$routes->post('manager/ecategoria_all', 'Manager::ecategoria_all');
$routes->post('manager/ecategoria_create', 'Manager::ecategoria_create');
$routes->post('manager/ecategoria_update', 'Manager::ecategoria_update');
$routes->post('manager/ecategoria_delete', 'Manager::ecategoria_delete');
$routes->get('manager/esintoma', 'Manager::esintoma');
$routes->get('manager/esintoma_get/(:num)', 'Manager::esintoma_get/$1');
$routes->post('manager/esintoma_all', 'Manager::esintoma_all');
$routes->post('manager/esintoma_create', 'Manager::esintoma_create');
$routes->post('manager/esintoma_update', 'Manager::esintoma_update');
$routes->post('manager/esintoma_delete', 'Manager::esintoma_delete');
$routes->get('manager/emedicamento', 'Manager::emedicamento');
$routes->get('manager/emedicamento_get/(:num)', 'Manager::emedicamento_get/$1');
$routes->post('manager/emedicamento_all', 'Manager::emedicamento_all');
$routes->post('manager/emedicamento_create', 'Manager::emedicamento_create');
$routes->post('manager/emedicamento_update', 'Manager::emedicamento_update');
$routes->post('manager/emedicamento_delete', 'Manager::emedicamento_delete');
$routes->get('manager/ehc', 'Manager::ehc');
$routes->get('manager/ehc_get/(:num)', 'Manager::ehc_get/$1');
$routes->post('manager/ehc_create', 'Manager::ehc_create');
$routes->post('manager/ehc_update', 'Manager::ehc_update');
$routes->post('manager/ehc_delete', 'Manager::ehc_delete');
$routes->get('manager/etipodatomedico', 'Manager::etipodatomedico');
$routes->get('manager/etipodatomedico_get/(:num)', 'Manager::etipodatomedico_get/$1');
$routes->post('manager/etipodatomedico_all', 'Manager::etipodatomedico_all');
$routes->post('manager/etipodatomedico_create', 'Manager::etipodatomedico_create');
$routes->post('manager/etipodatomedico_update', 'Manager::etipodatomedico_update');
$routes->post('manager/etipodatomedico_delete', 'Manager::etipodatomedico_delete');
$routes->get('manager/edatomedico', 'Manager::edatomedico');
$routes->get('manager/edatomedico_get/(:num)', 'Manager::edatomedico_get/$1');
$routes->post('manager/edatomedico_all', 'Manager::edatomedico_all');
$routes->post('manager/edatomedico_create', 'Manager::edatomedico_create');
$routes->post('manager/edatomedico_update', 'Manager::edatomedico_update');
$routes->post('manager/edatomedico_delete', 'Manager::edatomedico_delete');

//$routes->get('admin/dashboard', 'Admin::dashboard');
//$routes->get('parents/dashboard', 'Parents::dashboard');

//***************************** SECRETARY ************************/
$routes->get('secretary/dashboard', 'Secretary::dashboard');
$routes->get('secretary/enrolled_students', 'Secretary::enrolled_students');
$routes->get('secretary/profile', 'Secretary::profile');
$routes->post('secretary/profile_update', 'Secretary::profile_update');
$routes->post('secretary/password_update', 'Secretary::password_update');
$routes->get('secretary/kardex/(:num)', 'Secretary::kardex/$1');
$routes->get('secretary/kardex_student/(:num)', 'Secretary::kardex_student/$1');
$routes->get('secretary/kardex_family/(:num)', 'Secretary::kardex_family/$1');
$routes->post('secretary/student_update', 'Secretary::student_update');
$routes->post('secretary/family_update', 'Secretary::family_update');
$routes->get('secretary/kardex_student_pdf/(:num)', 'Secretary::kardex_student_pdf/$1');
$routes->get('secretary/kardex_family_pdf/(:num)', 'Secretary::kardex_family_pdf/$1');
$routes->get('secretary/applicant/(:num)', 'Secretary::applicant/$1');
$routes->get('secretary/student_search/(:any)', 'Secretary::student_search/$1');
$routes->get('secretary/family_search/(:any)', 'Secretary::family_search/$1');
$routes->get('secretary/family_info/(:any)', 'Secretary::family_info/$1');
$routes->get('secretary/sections', 'Secretary::sections');
$routes->get('secretary/section_rudes/(:num)', 'Secretary::section_rudes/$1');
$routes->get('secretary/counselors', 'Secretary::counselors');
$routes->get('secretary/counselors_update/(:any)', 'Secretary::counselors_update/$1');
$routes->get('secretary/assistance', 'Secretary::assistance');
$routes->get('secretary/attendance_reports', 'Secretary::attendance_reports');
$routes->post('secretary/atte_report_student_xlsx', 'Secretary::atte_report_student_xlsx');
$routes->post('secretary/licenses_save', 'Secretary::licenses_save');
$routes->get('secretary/medio', 'Secretary::medios');
$routes->get('secretary/medio_get/(:num)', 'Secretary::medio_get/$1');
$routes->post('secretary/medio_create', 'Secretary::medio_create');
$routes->post('secretary/medio_update', 'Secretary::medio_update');
$routes->post('secretary/medio_delete', 'Secretary::medio_delete');
$routes->get('secretary/licenses', 'Secretary::licenses');
$routes->get('secretary/licenses_all', 'Secretary::licenses_all');
$routes->get('secretary/licenses_all_data', 'Secretary::licenses_all_data');
$routes->get('secretary/licenses_received', 'Secretary::licenses_received');
$routes->post('secretary/licenses_auth', 'Secretary::licenses_auth');
$routes->post('secretary/licenses_noauth', 'Secretary::licenses_noauth');
$routes->get('secretary/licenses_add', 'Secretary::licenses_add');
$routes->get('secretary/licenses_periodo_add', 'Secretary::licenses_periodo_add');
$routes->get('secretary/licencia_get/(:num)', 'Secretary::licencia_get/$1');
$routes->get('secretary/licenses_edit/(:num)', 'Secretary::licenses_edit/$1');
$routes->post('secretary/licencias_create', 'Secretary::licencias_create');
$routes->post('secretary/licencias_periodo_create', 'Secretary::licencias_periodo_create');
$routes->get('secretary/licenses_report_absence', 'Secretary::licenses_report_absence');
$routes->post('secretary/licenses_report_teacher', 'Secretary::licenses_report_teacher');
$routes->post('secretary/licenses_update', 'Secretary::licenses_update');
$routes->post('secretary/licencia_delete', 'Secretary::licencia_delete');
$routes->get('secretary/license_report/(:num)', 'Secretary::license_report/$1');
$routes->get('secretary/license_send/(:any)', 'Secretary::license_send/$1');
$routes->get('secretary/licenses_reports', 'Secretary::licenses_reports');
$routes->post('secretary/licenses_report_xlsx', 'Secretary::licenses_report_xlsx');
$routes->post('secretary/licenses_report_dates_xlsx', 'Secretary::licenses_report_dates_xlsx');
$routes->post('secretary/licenses_report_student_xlsx', 'Secretary::licenses_report_student_xlsx');
$routes->post('secretary/ausencias_suma_xlsx', 'Secretary::ausencias_suma_xlsx');

$routes->get('secretary/absences', 'Secretary::absences');
$routes->get('secretary/absence_add', 'Secretary::absence_add');
$routes->get('secretary/absence_get/(:num)', 'Secretary::absence_get/$1');
$routes->get('secretary/absence_edit/(:num)', 'Secretary::absence_edit/$1');
$routes->post('secretary/absence_create', 'Secretary::absence_create');
$routes->post('secretary/absence_update', 'Secretary::absence_update');
$routes->post('secretary/absence_delete', 'Secretary::absence_delete');
$routes->get('secretary/absence_report/(:num)', 'Secretary::absence_report/$1');
$routes->get('secretary/absence_send/(:any)', 'Secretary::absence_send/$1');

$routes->get('secretary/delays/(:num)', 'Secretary::delays/$1');
$routes->get('secretary/delay_get/(:num)', 'Secretary::delay_get/$1');
$routes->post('secretary/delay_create', 'Secretary::delay_create');
$routes->post('secretary/delay_update', 'Secretary::delay_update');
$routes->post('secretary/delay_delete', 'Secretary::delay_delete');
$routes->get('secretary/delay_xlsx/(:any)', 'Secretary::delay_xlsx/$1');
$routes->get('secretary/infractions', 'Secretary::infractions');
$routes->get('secretary/infraction_letter/(:num)', 'Secretary::infraction_letter/$1');
$routes->get('secretary/infractions_student/(:num)', 'Secretary::infractions_student/$1');
$routes->get('secretary/infractions_section/(:any)', 'Secretary::infractions_section/$1');

$routes->get('secretary/student_attendance/(:any)', 'Secretary::student_attendance/$1');
$routes->get('secretary/student_licenses/(:any)', 'Secretary::student_licenses/$1');
$routes->get('secretary/student_infractions/(:any)', 'Secretary::student_infractions/$1');
$routes->get('secretary/student_absences/(:any)', 'Secretary::student_absences/$1');
$routes->get('secretary/student_delays/(:any)', 'Secretary::student_delays/$1');

$routes->get('secretary/ehc', 'Secretary::ehc');
$routes->get('secretary/contacts', 'Secretary::contacts');
$routes->get('secretary/payment_methods', 'Secretary::payment_methods');

//***************************** PARENTS ************************/
$routes->get('parents/dashboard', 'Parents::dashboard');
$routes->get('parents/family_data', 'Parents::family_data');
$routes->get('parents/enrolled_children', 'Parents::enrolled_children');
$routes->get('parents/content_letter', 'Parents::content_letter');
$routes->get('parents/contacts', 'Parents::contacts');
$routes->get('parents/payment_methods', 'Parents::payment_methods');
$routes->get('parents/behaviors', 'Parents::behaviors');
$routes->get('parents/gamified_behavior', 'Parents::gamified_behavior');
$routes->get('parents/gamified_behavior/(:any)', 'Parents::gamified_behavior/$1');
$routes->get('parents/behaviors_child/(:any)', 'Parents::behaviors_child/$1');
$routes->get('parents/report_half/(:any)', 'Parents::report_half/$1');
$routes->get('parents/report_card/(:any)', 'Parents::report_card/$1');
$routes->get('parents/virtual_library_prim', 'Parents::virtual_library_prim');
$routes->get('parents/virtual_library_sec', 'Parents::virtual_library_sec');
$routes->get('parents/class_protocol', 'Parents::class_protocol');
$routes->get('parents/biosafety_protocol', 'Parents::biosafety_protocol');
$routes->get('parents/profile', 'Parents::profile');
$routes->get('parents/infractions', 'Parents::infractions');
$routes->get('parents/report_licenses/(:any)', 'Parents::report_licenses/$1');
$routes->get('parents/continuity_student', 'Parents::continuity_student');
$routes->post('parents/continuity_save', 'Parents::continuity_save');
$routes->get('parents/gallery', 'Parents::gallery');
$routes->get('parents/student_attendance/(:any)', 'Parents::student_attendance/$1');
$routes->get('parents/student_licenses/(:any)', 'Parents::student_licenses/$1');
$routes->get('parents/student_absences/(:any)', 'Parents::student_absences/$1');
$routes->get('parents/student_delays/(:any)', 'Parents::student_delays/$1');
$routes->get('parents/licenses', 'Parents::licenses');
$routes->post('parents/license_save', 'Parents::license_save');
$routes->post('parents/license_save_dia', 'Parents::license_save_dia');
$routes->post('parents/license_save_periodo', 'Parents::license_save_periodo');
$routes->get('parents/interviews', 'Parents::interviews');

//***************************** STUDENTS ************************/
$routes->get('student/dashboard', 'Student::dashboard');
$routes->get('student/family_data', 'Student::family_data');
$routes->get('student/content_letter', 'Student::content_letter');
$routes->get('student/behaviors', 'Student::behaviors');
$routes->get('student/gamified_behavior', 'Student::gamified_behavior');
$routes->get('student/virtual_library_prim', 'Student::virtual_library_prim');
$routes->get('student/virtual_library_sec', 'Student::virtual_library_sec');
$routes->get('student/class_protocol', 'Student::class_protocol');
$routes->get('student/biosafety_protocol', 'Student::biosafety_protocol');
$routes->get('student/evaluation_report', 'Student::evaluation_report');
$routes->get('student/self_appraisal', 'Student::self_appraisal');
$routes->post('student/self_appraisal_save', 'Student::self_appraisal_save');
$routes->get('student/report_card', 'Student::report_card');
$routes->get('student/infractions', 'Student::infractions');

//***************************** TEACHER ************************/
$routes->get('teacher/dashboard', 'Teacher::dashboard');
$routes->get('teacher/error', 'Teacher::error');
$routes->get('teacher/virtual_library_prim', 'Teacher::virtual_library_prim');
$routes->get('teacher/virtual_library_sec', 'Teacher::virtual_library_sec');
$routes->get('teacher/class_protocol', 'Teacher::class_protocol');

$routes->get('teacher/subjects', 'Teacher::subjects');
$routes->get('teacher/subject_notes/(:num)', 'Teacher::subject_notes/$1');
$routes->get('teacher/evaluation_planner', 'Teacher::evaluation_planner');
$routes->get('teacher/subject_sheet_create/(:num)', 'Teacher::subject_sheet_create/$1');

$routes->get('teacher/create_sheet/(:any)', 'Teacher::create_sheet/$1');
$routes->get('teacher/integrate_sheet/(:any)', 'Teacher::integrate_sheet/$1');
$routes->get('teacher/link_sheet/(:any)', 'Teacher::link_sheet/$1');
$routes->get('teacher/curricular_adaptations', 'Teacher::curricular_adaptations');
$routes->get('teacher/behaviors', 'Teacher::behaviors');
$routes->get('teacher/behavior_add/(:any)', 'Teacher::behavior_add/$1');
$routes->post('teacher/behavior_save', 'Teacher::behavior_save');
$routes->get('teacher/content_letter', 'Teacher::content_letter');
$routes->post('teacher/upfile_letter/(:num)', 'Teacher::upfile_letter/$1');
$routes->get('teacher/pdcs', 'Teacher::pdcs');
$routes->post('teacher/upfile_pdcs/(:num)', 'Teacher::upfile_pdcs/$1');

$routes->get('teacher/assistance', 'Teacher::assistance');
$routes->get('teacher/attendance/(:num)', 'Teacher::attendance/$1');
$routes->get('teacher/attendance_inicial/(:num)', 'Teacher::attendance_inicial/$1');
$routes->post('teacher/attendance_date', 'Teacher::attendance_date');
$routes->post('teacher/attendance_date_inicial', 'Teacher::attendance_date_inicial');
$routes->post('teacher/attendance_save', 'Teacher::attendance_save');
$routes->post('teacher/attendance_save_inicial', 'Teacher::attendance_save_inicial');
$routes->post('teacher/register_behavior', 'Teacher::register_behavior'); // New
$routes->post('teacher/update_behavior_observation_ajax', 'Teacher::update_behavior_observation_ajax'); // New
$routes->post('teacher/delete_behavior_ajax', 'Teacher::delete_behavior_ajax'); // New
$routes->post('teacher/update_attendance_ajax', 'Teacher::update_attendance_ajax'); // New
$routes->post('teacher/get_daily_log_ajax', 'Teacher::get_daily_log_ajax'); // New
$routes->get('teacher/session_keep_alive', 'Teacher::session_keep_alive');
$routes->post('teacher/attendance_auto_save_ajax', 'Teacher::attendance_auto_save_ajax');
$routes->get('teacher/students_list', 'Teacher::students_list');
$routes->get('teacher/update_db_structure', 'Teacher::update_db_structure'); // Temp Migration
$routes->get('teacher/history', 'Teacher::history'); // New
$routes->get('teacher/history_students/(:num)', 'Teacher::history_students/$1'); // New
$routes->get('teacher/history_students/(:num)/(:num)', 'Teacher::history_students/$1/$2'); // New
$routes->get('teacher/student_profile/(:num)/(:num)', 'Teacher::student_profile/$1/$2'); // New
$routes->get('teacher/incidence_register', 'Teacher::incidence_register');
$routes->get('teacher/search_students_incidence', 'Teacher::search_students_incidence');
$routes->post('teacher/resolve_date_id', 'Teacher::resolve_date_id');
$routes->get('teacher/attendance_report/(:num)', 'Teacher::attendance_report/$1');
$routes->post('teacher/assistance_edit/(:num)', 'Teacher::assistance_edit/$1');
$routes->post('teacher/attendance_date_edit/(:num)', 'Teacher::attendance_date_edit/$1');
$routes->get('teacher/assists_excel/(:any)', 'Teacher::assists_excel/$1');
$routes->post('teacher/assistance_del/(:num)', 'Teacher::assistance_del/$1');

$routes->get('teacher/half_phase/(:num)', 'Teacher::half_phase/$1');
$routes->get('teacher/half_phase_save/(:num)', 'Teacher::half_phase_save/$1');
$routes->get('teacher/recover_self/(:any)', 'Teacher::recover_self/$1');
$routes->get('teacher/deliver_notes/(:num)', 'Teacher::deliver_notes/$1');
$routes->get('teacher/review_notes/(:num)', 'Teacher::review_notes/$1');
$routes->post('teacher/consolidate_notes', 'Teacher::consolidate_notes');
$routes->get('teacher/enable_sheet_phase/(:any)', 'Teacher::enable_sheet_phase/$1');
$routes->get('teacher/infractions', 'Teacher::infractions');
$routes->post('teacher/infraction_save', 'Teacher::infraction_save');
$routes->get('teacher/infractions_section/(:any)', 'Teacher::infractions_section/$1');
$routes->get('teacher/infractions_edit/(:num)', 'Teacher::infractions_edit/$1');
$routes->post('teacher/infraction_updated', 'Teacher::infraction_updated');
$routes->post('teacher/infraction_deleted', 'Teacher::infraction_deleted');
$routes->get('teacher/infraction_letter/(:num)', 'Teacher::infraction_letter/$1');
$routes->post('teacher/infraction_notify', 'Teacher::infraction_notify');
$routes->get('teacher/infractions_excel/(:num)', 'Teacher::infractions_excel/$1');
$routes->get('teacher/interviews', 'Teacher::interviews');
$routes->get('teacher/interviews/(:any)', 'Teacher::interviews/$1');
$routes->post('teacher/interview_save', 'Teacher::interview_save');
$routes->post('teacher/evaluation_check_date', 'Teacher::evaluation_check_date');
$routes->post('teacher/evaluation_save', 'Teacher::evaluation_save');
$routes->post('teacher/get_calendar_events', 'Teacher::get_calendar_events');
$routes->get('teacher/get_my_evaluations', 'Teacher::get_my_evaluations');
$routes->get('teacher/evaluation_delete/(:num)', 'Teacher::evaluation_delete/$1');
$routes->get('teacher/interview_delete/(:num)', 'Teacher::interview_delete/$1');
$routes->get('teacher/interview_get/(:num)', 'Teacher::interview_get/$1');
$routes->post('teacher/interview_create', 'Teacher::interview_create');
$routes->post('teacher/interview_update', 'Teacher::interview_update');
$routes->post('teacher/interview_delete', 'Teacher::interview_delete');
$routes->get('teacher/interview_xlsx/(:any)', 'Teacher::interview_xlsx/$1');
$routes->get('teacher/sections', 'Teacher::sections');
$routes->get('teacher/report_card/(:num)', 'Teacher::report_card/$1');

//***************************** TEACHER CONSEJERO************************/
$routes->get('teacher/adviser', 'Teacher::adviser');
$routes->get('teacher/adviser_behavior_log/(:num)', 'Teacher::adviser_behavior_log/$1');
$routes->get('teacher/self_inicial/(:any)', 'Teacher::self_inicial/$1');
$routes->post('teacher/autoeval/(:any)', 'Teacher::autoeval/$1');
$routes->get('teacher/student_search/(:any)', 'Teacher::student_search/$1');
$routes->get('teacher/student_notes/(:any)', 'Teacher::student_notes/$1');
$routes->get('teacher/self_appraisal/(:any)', 'Teacher::self_appraisal/$1');
//********************************TEACHER DIRECTOR **************/
$routes->get('teacher/student_search/(:any)', 'Teacher::student_search/$1');
$routes->get('teacher/family_search/(:any)', 'Teacher::family_search/$1');
$routes->get('teacher/family_info/(:any)', 'Teacher::family_info/$1');
$routes->get('teacher/student_attendance/(:any)', 'Teacher::student_attendance/$1');
$routes->get('teacher/student_licenses/(:any)', 'Teacher::student_licenses/$1');
$routes->get('teacher/sections_dir', 'Teacher::sections_dir');
$routes->get('teacher/class_dir', 'Teacher::class_dir');
$routes->get('teacher/subjects_section/(:any)', 'Teacher::subjects_section/$1');
$routes->get('teacher/notes_half_student/(:any)', 'Teacher::notes_half_student/$1');
$routes->get('teacher/notes_half_student_xls/(:any)', 'Teacher::notes_half_student_xls/$1');
$routes->get('teacher/self_director', 'Teacher::self_director');
$routes->get('teacher/generate_centralizer/(:any)', 'Teacher::generate_centralizer/$1');
$routes->get('teacher/generate_ranking/(:any)', 'Teacher::generate_ranking/$1');
$routes->get('teacher/ranking_class/(:any)', 'Teacher::ranking_class/$1');
$routes->get('teacher/low_averages/(:any)', 'Teacher::low_averages/$1');
$routes->get('teacher/low_averages_xlsx/(:any)', 'Teacher::low_averages_xlsx/$1');
$routes->get('teacher/saber_hacer_xlsx/(:any)', 'Teacher::saber_hacer_xlsx/$1');
$routes->get('teacher/section_notes/(:any)', 'Teacher::section_notes/$1');
$routes->get('teacher/teacher_notes', 'Teacher::teacher_notes');
$routes->get('teacher/delays_student/(:any)', 'Teacher::delays_student/$1');
$routes->get('teacher/absences_student/(:any)', 'Teacher::absences_student/$1');
$routes->get('teacher/student_statistics/(:any)', 'Teacher::student_statistics/$1');
$routes->get('teacher/student_infractions/(:any)', 'Teacher::student_infractions/$1');
$routes->get('teacher/student_communications/(:any)', 'Teacher::student_communications/$1');
$routes->get('teacher/grade_averages/(:any)', 'Teacher::grade_averages/$1');
$routes->get('director/sections_statistics', 'Director::sections_statistics');
$routes->get('director/low_averages/(:any)', 'Director::low_averages/$1');


/*********************************SERVER ***************/
//Rutas para combobox
$routes->post('server/student_fill', 'Server::fillDatosStudent');
$routes->post('server/parents_fill', 'Server::fillDatosParent');
$routes->post('server/fill_subject_section', 'Server::fill_subject_section');
$routes->post('server/fill_section_subject', 'Server::fill_section_subject');
$routes->get('server/fill_motivos', 'Server::fill_motivos');
$routes->get('server/fill_periodos', 'Server::fill_periodos');
$routes->get('server/fill_periodos_section/(:num)', 'Server::fill_periodos_section/$1');
$routes->get('server/fill_parent_relationship/(:any)', 'Server::fill_parent_relationship/$1');
$routes->get('server/student_get/(:num)', 'Server::student_get/$1');
$routes->get('server/fill_type_foul', 'Server::fill_type_foul');
$routes->get('server/fill_criteria/(:num)', 'Server::fill_criteria/$1');
$routes->get('server/fill_subjects/(:num)', 'Server::fill_subjects/$1');
$routes->get('server/fill_students/(:num)', 'Server::fill_students/$1');
$routes->post('server/fill_infraction', 'Server::fill_infraction');
$routes->get('server/fill_infraction_emails/(:num)', 'Server::fill_infraction_emails/$1');
$routes->get('server/fill_delays_student/(:num)', 'Server::fill_delays_student/$1');
$routes->get('server/descargar_carta/(:any)', 'Server::descargar_carta/$1');
/********************************DIRECCION GENERAL************************* */
$routes->get('dirgeneral/dg_continuity_results', 'Dirgeneral::dg_continuity_results');
//***************************** API TIQUI ************************/
$routes->group('apitiqui', ['namespace' => 'App\Controllers\API'], function ($routes) {
    $routes->get('student/(:num)', 'Student::index/$1');
});
$routes->get('apitiqui/student_exists/(:num)', 'Apitiqui::student_exists/$1');
$routes->get('apitiqui/family_exists/(:num)', 'Apitiqui::family_exists/$1');
$routes->get('apitiqui/family/(:num)', 'Apitiqui::family/$1');
$routes->get('apitiqui/enroll/(:num)', 'Apitiqui::enroll/$1');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route file
 * s here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
