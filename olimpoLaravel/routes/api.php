<?php

    use App\Http\Controllers\CustomerController;
    use App\Http\Controllers\PassportAuthCustomersController;
    use App\Http\Controllers\PassportAuthTrainersController;
    use App\Http\Controllers\TrainerController;
    use App\Http\Controllers\PaymentController;
    use App\Http\Controllers\TrainingController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;

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

    /*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });*/

    Route::prefix('/customers')->group(function () {
        Route::get('/search', [CustomerController::class, 'search']);
        Route::get('', [CustomerController::class, 'getAll']);
        Route::post('', [CustomerController::class, 'create']);
        Route::middleware('validaId')->delete('/{id}', [CustomerController::class, 'delete']);
        // Se utilizan llaves {} para indicar que la ruta puede recibir un parámetro
        Route::middleware('validaId')->get('/{id}', [CustomerController::class, 'getById']);
        Route::middleware('validaId')->patch('/{id}', [CustomerController::class, 'update']);
        Route::middleware('validaId')->get('/{id}/payments', [CustomerController::class, 'payments']);
        Route::middleware('validaId')->get('/{id}/trainers', [CustomerController::class, 'trainers']);
        Route::middleware('validaId')->get('/{id}/trainings', [CustomerController::class, 'getTrainings']);
        //Parte de autentificación con Passport:
        Route::post('/login', [PassportAuthCustomersController::class, 'login']);
        Route::get('/logout', [PassportAuthCustomersController::class, 'logout']);
        Route::get('/isLogin', [PassportAuthCustomersController::class, 'isLogin']);
        Route::middleware('authCustomers')->get('/me', [PassportAuthCustomersController::class, 'me']);
    });

    Route::post('/savePdf', [TrainingController::class, 'saveTraining']);
    Route::middleware('validaId')->get('/trainings/{id}/customers', [TrainingController::class, 'getCustomer']);


    Route::prefix('/trainers')->group(function () {
        Route::get('', [TrainerController::class, 'getAll']);
        Route::post('', [TrainerController::class, 'create']);
        Route::middleware('validaId')->delete('/{id}', [TrainerController::class, 'delete']);
        // Se utilizan llaves {} para indicar que la ruta puede recibir un parámetro
        Route::middleware('validaId')->get('/{id}', [TrainerController::class, 'getById']);
        Route::middleware('validaId')->get('/{id}/customers', [TrainerController::class, 'customers']);
        //Parte de autentificación con Passport:
        Route::post('/login', [PassportAuthTrainersController::class, 'login']);
        Route::get('/logout', [PassportAuthTrainersController::class, 'logout']);
        Route::get('/isLogin', [PassportAuthTrainersController::class, 'isLogin']);
        Route::middleware('authTrainers')->get('/me', [PassportAuthTrainersController::class, 'me']);
    });

    Route::prefix('/payments')->group(function () {
        Route::get('', [PaymentController::class, 'getAll']);
        Route::post('', [PaymentController::class, 'create']);
        Route::middleware('validaId')->delete('/{id}', [PaymentController::class, 'delete']);
        // Se utilizan llaves {} para indicar que la ruta puede recibir un parámetro
        Route::middleware('validaId')->get('/{id}', [PaymentController::class, 'getById']);
        Route::middleware('validaId')->get('/{id}/customers', [PaymentController::class, 'customers']);
    });

