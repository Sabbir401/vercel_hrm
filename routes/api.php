<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ScaleController;
use App\Http\Controllers\DegreeController;
use App\Http\Controllers\EmpImgController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\NomineeController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OfficialController;
use App\Http\Controllers\ReligionController;
use App\Http\Controllers\AttendenceController;
use App\Http\Controllers\BloodGroupController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\AcademicInfoController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\EmployeeTypeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\TrainingInfoController;
use App\Http\Controllers\WorkExperienceController;
use App\Http\Controllers\LevelOfEducationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\TerritoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/link-storage', function () {
    $targetFolder = $_SERVER['DOCUMENT_ROOT'] . '/storage/app/public';
    $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/public/storage';
    symlink($targetFolder, $linkFolder);
});


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/emp/{id}', [EmployeeController::class, 'find']);
    Route::get('/empimg/{id}', [EmpImgController::class, 'edit']);
    Route::post('/employee/{id}', [EmployeeController::class, 'update']);
    Route::get('/employee/allemp', [EmployeeController::class, 'allemp']);
    Route::get('/employee/search', [EmployeeController::class, 'search']);
    Route::get('/branch', [BranchController::class, 'index']);
    Route::get('/leave-type', [LeaveTypeController::class, 'index']);
    Route::get('/leave-summery/{id}', [LeaveController::class, 'leaveSummery']);

    Route::put('/nominee/{id}', [NomineeController::class, 'update']);

    Route::resource('/blood', BloodGroupController::class);
    Route::resource('/religion', ReligionController::class);
    Route::resource('/company', CompanyController::class);
    Route::resource('/phone', CountryController::class);
    Route::resource('/employee', EmployeeController::class);
    Route::resource('/nominee', NomineeController::class);
    Route::resource('/child', ChildController::class);
    Route::resource('/education', LevelOfEducationController::class);
    Route::resource('/board', BoardController::class);
    Route::resource('/degree', DegreeController::class);
    Route::resource('/scale', ScaleController::class);
    Route::resource('/leave', LeaveController::class);

    Route::resource('/academic', AcademicInfoController::class);
    Route::resource('/training', TrainingInfoController::class);
    Route::resource('/work', WorkExperienceController::class);

    Route::resource('/department', DepartmentController::class);
    Route::resource('/designation', DesignationController::class);
    Route::resource('/empType', EmployeeTypeController::class);
    Route::resource('/area', AreaController::class);
    Route::resource('/territory', TerritoryController::class);
    Route::resource('/official', OfficialController::class);

    Route::post('/attendence', [AttendenceController::class, 'store']);
    Route::post('/attendence/edit', [AttendenceController::class, 'edit']);
    Route::get('/attendence', [AttendenceController::class, 'getAttendance']);

    Route::get('/emp-attendence', [EmployeeController::class, 'attendenceEmployee']);
    Route::get('/fetch-attendence', [AttendenceController::class, 'fetchAttendence']);
    Route::get('/fetch-zkt-attendence', [AttendenceController::class, 'fetchZktAttendence']);


    Route::get('/generate-pdf', [AttendenceController::class, 'generatePdf']);
    Route::get('/cv-pdf/{id}', [EmployeeController::class, 'generatePdf']);

    //role and permissions
    Route::get('/permission', [PermissionController::class, 'index']);
});
