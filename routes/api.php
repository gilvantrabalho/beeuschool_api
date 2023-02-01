<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Visitor\ContactController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Student\AudioController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Teacher\LinkController;
use App\Http\Controllers\Teacher\VideoController;
use App\Http\Controllers\Teacher\AudioController as TeacherAudioController;
use App\Http\Controllers\OneHundredTextsController;
use App\Http\Controllers\Teacher\StudentsAudioCorrectionsController;
use App\Http\Controllers\Master\StudentController as MasterStudentController;
use App\Http\Controllers\Master\TeacherController as MasterTeacherController;
use App\Http\Controllers\Master\PlanController;
use App\Http\Controllers\Student\ChangePassword;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\Student\TicketController AS TicketStudentController;
use App\Http\Controllers\Master\TicketMasterController;
use App\Http\Controllers\Master\LinkController AS LinkMasterController;
use App\Http\Controllers\Dashboard\StudentsDashboardController;
use App\Http\Controllers\Dashboard\TeacherDashboardController;
use App\Http\Controllers\Student\LinkStudentController;
use App\Http\Controllers\CrudController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function (){

    Route::get('/teste_storage', function() {
        return storage_path();
    });

    Route::prefix('crud')->controller(CrudController::class)->group(function () {
        Route::get('read', 'index');
        Route::get('show-by-id/{id}', 'showById');
        Route::post('create', 'store');
        Route::put('update/{crud}', 'update');
        Route::delete('delete/{crud}', 'destroy');
    });

    Route::prefix('/visitor')->group(function (){
        Route::post('contact', [ContactController::class, 'store']);
    });

    Route::prefix('auth')->group(function () {
        Route::post('register', [RegisterController::class, 'store']);
        Route::post('login', [LoginController::class, 'index']);
        Route::get('logout', [LogoutController::class, 'index']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user', [\App\Http\Controllers\UserController::class, 'userByToken']);

        Route::prefix('one-hundred-texts')->controller(OneHundredTextsController::class)->group(function () {
            Route::get('get-all', 'index');
        });

        Route::prefix('students')->controller(StudentController::class)->group(function () {
            Route::get('all', 'index');
            Route::get('get-student/{student}', 'show');
            Route::post('register', 'store');
            Route::put('update/{id}', 'update');
            Route::delete('delete/{student}', 'delete');

            Route::prefix('dashboard')->controller(StudentsDashboardController::class)->group(function () {
                Route::get('showStudentPlan/{plan}', 'showStudentPlan');
            });

            Route::prefix('audio')->controller(AudioController::class)->group(function () {
                Route::post('send', 'store');
                Route::post('update', 'update');
                Route::get('get-by-user_id/{id}', 'show');
                Route::get('get-show-student-audio-id/{id}', 'showStudentsAudioId');
                Route::get('countAudios/{student}', 'countAudios');
            });

            Route::prefix('link')->controller(LinkStudentController::class)->group(function () {
                Route::get('testeAction', 'testeAction');
                Route::get('list/{id}', 'index');
                Route::post('create', 'store');
                Route::delete('delete/{studentLink}', 'destroy');
                Route::get('readLastLinksAccessed/{student}', 'readLastLinksAccessed');
                Route::post('lastLinksAccessed', 'lastLinksAccessed');
            });

            Route::prefix('ticket')->controller(TicketStudentController::class)->group(function () {
                Route::post('register', 'store');
                Route::get('messages-ticket/{token}', 'readMessagesTicket');
                Route::post('registerMessageTicket', 'registerMessageTicket');
                Route::get('show-student-id/{student}', 'show');
            });

            Route::prefix('change-password')->controller(ChangePassword::class)->group(function () {
                Route::post('change', 'index');
            });
        });

        Route::prefix('teachers')->controller(TeacherController::class)->group(function () {
            Route::get('all', 'index');
            Route::get('get-student/{teacher}', 'show');
            Route::post('register', 'store');
            Route::put('update/{id}', 'update');
            Route::delete('delete/{teacher}', 'delete');

            Route::prefix('audio')->controller(TeacherAudioController::class)->group(function () {
                Route::get('students-audio/{id}', 'studentsAudio');
            });

            Route::prefix('corrections')->controller(StudentsAudioCorrectionsController::class)->group(function () {
                Route::post('register', 'store');
            });
        });

        Route::prefix('links')->controller(LinkController::class)->group(function () {
            Route::get('all', 'index');
            Route::post('register', 'store');
            Route::delete('delete/{link}', 'destroy');
            Route::get('get-link/{link}', 'show');
            Route::put('update/{id}', 'update');

            Route::get('testeAction', 'testeAction');
        });

        Route::prefix('videos')->controller(VideoController::class)->group(function () {
            Route::get('all', 'index');
            Route::post('register', 'store');
            Route::delete('delete/{video}', 'destroy');
            Route::get('get-video/{video}', 'show');
            Route::put('update/{id}', 'update');
        });

        Route::prefix('master')->group(function () {
            Route::prefix('dashboard')->controller(StudentsDashboardController::class)->group(function () {
                Route::get('count-students', 'countStudents');
                Route::get('last-registered-students', 'lastRegisteredStudents');
            });

            Route::prefix('dashboard')->controller(TeacherDashboardController::class)->group(function () {
                Route::get('count-teachers', 'countTeachers');
            });

            Route::prefix('student')->controller(MasterStudentController::class)->group(function () {
                Route::get('list', 'index');
                Route::get('get-by-id/{student}', 'show');
                Route::post('register', 'store');
                Route::put('edit/{id}', 'edit');
                Route::delete('delete/{student}', 'destroy');
            });

            Route::prefix('teacher')->controller(MasterTeacherController::class)->group(function () {
                Route::get('get-by-id/{teacher}', 'show');
                Route::get('list', 'index');
                Route::post('register', 'store');
                Route::put('edit/{id}', 'edit');
                Route::delete('delete/{teacher}', 'destroy');
            });

            Route::prefix('plan')->controller(PlanController::class)->group(function () {
                Route::get('list', 'index');
                Route::post('create', 'store');
                Route::delete('delete/{plan}', 'destroy');
            });

            Route::prefix('link')->controller(LinkMasterController::class)->group(function () {
                Route::post('create', 'store');
                Route::get('list', 'index');
                Route::delete('delete/{link}', 'destroy');
            });

            Route::prefix('ticket')->controller(TicketMasterController::class)->group(function () {
                Route::get('get-tickets-master', 'index');
            });
        });

        Route::prefix('voucher')->controller(VoucherController::class)->group(function () {
            Route::get('read-by-student/{student}', 'readByStudentId');
            Route::post('create', 'store');
            Route::post('validate-update', 'validateUpdate');
            Route::post('invalidate-update', 'invalidateUpdate');
            Route::get('read-by-grid', 'readByGrid');
            Route::get('config-voucher/{student}', 'configVoucher');
            Route::get('get-by-status/{status}', 'readByStatus');
            Route::get('show/{voucher}', 'show');
        });
    });

});
