<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeCertController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeStatusController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ResearchCategoryController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\UserController;
use App\Models\Division;
use Illuminate\Support\Facades\Route;

// use PHPUnit\Event\Test\AfterTestMethodFinished;

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

Route::get('/', function () {
    return 'api is up!';
});

Route::group(['prefix' => 'auth', 'middleware' => 'auth:api'], function ($router) {
    Route::post('login', [AuthController::class, 'login'])->name('login')->withoutMiddleware('auth:api')->middleware('guest');
    Route::post('register', [AuthController::class, 'register'])->middleware('admin')->name('register');
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function ($router) {
    Route::get('/img/{fileName}', [UserController::class, 'img'])->name('user.img');
    Route::post('/img', [UserController::class, 'updateImg'])->middleware('manager:' . Division::HRD);
    Route::post('/password', [UserController::class, 'updatePassword']);
});

Route::group(['prefix' => 'employee', 'middleware' => 'auth:api'], function ($router) {
    Route::get('/', [EmployeeController::class, 'index'])->middleware('manager:' . Division::HRD);
    Route::get('/{id}', [EmployeeController::class, 'show']); // manager/admin/owner can access
    Route::post('/', [EmployeeController::class, 'register'])->middleware('manager:' . Division::HRD);
    Route::put('/', [EmployeeController::class, 'update'])->middleware('manager:' . Division::HRD);
    Route::delete('/{id}', [EmployeeController::class, 'destroy'])->middleware('manager:' . Division::HRD);

    Route::group(['prefix' => 'status', 'middleware' => 'auth:api'], function ($router) {
        Route::get('/', [EmployeeStatusController::class, 'index']);
        Route::get('/{id}', [EmployeeStatusController::class, 'show']);
        Route::post('/', [EmployeeStatusController::class, 'store'])->middleware('admin');
    });

    Route::group(['prefix' => 'certificate', 'middleware' => 'auth:api'], function ($router) {
        Route::get('/all', [EmployeeCertController::class, 'index']);
        Route::get('/{id}', [EmployeeCertController::class, 'show']); // manager/admin/owner can access
        Route::get('/user/{id}', [EmployeeCertController::class, 'showByUser']); // manager/admin/owner can access
        Route::post('/', [EmployeeCertController::class, 'store'])->middleware('manager:' . Division::HRD);
        Route::put('/', [EmployeeCertController::class, 'update'])->middleware('manager:' . Division::HRD);
        Route::delete('/{id}', [EmployeeCertController::class, 'destroy'])->middleware('manager:' . Division::HRD);

        Route::post("/file", [EmployeeCertController::class, 'updateFile'])->middleware('manager:' . Division::HRD);
        Route::get('/file/{fileName}', [EmployeeCertController::class, 'download'])->name('employee.cert.download'); // manager/admin/owner can access

    });
});

Route::group(['prefix' => 'division', 'middleware' => 'auth:api'], function ($router) {
    Route::get('/', [DivisionController::class, 'index']);
    Route::get('{id}', [DivisionController::class, 'show']);
    Route::get('/{id}/employees', [DivisionController::class, 'showWithEmployees']);
    Route::post('/', [DivisionController::class, 'store'])->middleware('admin');
});

Route::group(['prefix' => 'position', 'middleware' => 'auth:api'], function ($router) {
    Route::post('/', [PositionController::class, 'store'])->middleware('admin');
    Route::get('/', [PositionController::class, 'index']);
});

Route::group(['prefix' => 'research', 'middleware' => 'auth:api'], function ($router) {
    Route::get('/', [ResearchController::class, 'index']);
    // Route::get('/{id}', [ResearchController::class, 'show']);
    Route::post('/', [ResearchController::class, 'store'])->middleware('manager:' . Division::RND);
    Route::delete('/{id}', [ResearchController::class, 'destroy'])->middleware('manager:' . Division::RND);
    Route::get('/doc/{fileName}', [ResearchController::class, 'download'])->name('research.doc.download');
    Route::put('/', [ResearchController::class, 'update'])->middleware('manager:' . Division::RND);
    Route::post('/doc', [ResearchController::class, 'updateDoc'])->middleware('manager:' . Division::RND);

    Route::group(['prefix' => 'category', 'middleware' => 'auth:api'], function ($router) {
        Route::get('/', [ResearchCategoryController::class, 'index']);
        Route::post('/', [ResearchCategoryController::class, 'store'])->middleware('manager:' . Division::RND);
        Route::put('/', [ResearchCategoryController::class, 'update']);
        Route::delete('/{id}', [ResearchCategoryController::class, 'destroy'])->middleware('manager:' . Division::RND);
    });

});