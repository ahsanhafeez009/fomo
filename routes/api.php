<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyApiController;
use App\Http\Controllers\Api\ProductApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//CompanyApiController Controller
Route::get("/check_company_type_cron_job"  , [CompanyApiController::class, 'check_company_type_cron_job']);
Route::get("/check_company_status_cron_job", [CompanyApiController::class, 'check_company_status_cron_job']);
Route::get("/check_company_fomo_incharge_cron_job", [CompanyApiController::class, 'check_company_fomo_incharge_cron_job']);
Route::get("/company_payment_terms_cron_job", [CompanyApiController::class, 'company_payment_terms_cron_job']);
Route::get("/company_country_cron_job", [CompanyApiController::class, 'company_country_cron_job']);
Route::get("/company_state_cron_job", [CompanyApiController::class, 'company_state_cron_job']);
Route::get("/company_city_cron_job", [CompanyApiController::class, 'company_city_cron_job']);
//CompanyApiController Controller

//ProductApiController Controller
Route::get("/check_product_brand_names_cron_job", [ProductApiController::class, 'check_product_brand_names_cron_job']);
Route::get("/check_product_gender_cron_job"     , [ProductApiController::class, 'check_product_gender_cron_job']);
Route::get("/check_product_name_cron_job"       , [ProductApiController::class, 'check_product_name_cron_job']);
//ProductApiController Controller