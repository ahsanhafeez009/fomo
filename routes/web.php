<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerDemandController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserDemandController;
use App\Http\Controllers\SupplierRecordController;
use App\Http\Controllers\SupplierPriceAnalysisController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

    //Company Controller
    Route::get("/company-list-ajax", [CompanyController::class, 'index'])->name('company-list-ajax');
    Route::get("/company-list/{type?}", [CompanyController::class, 'index'])->name('company-list');
    Route::get("/create-company", [CompanyController::class, 'create_company'])->name('create-company');
    Route::get("/upload-bulk-companies", [CompanyController::class, 'upload_bulk_companies'])->name('upload-bulk-companies');
    Route::post("/get-all-companies", [CompanyController::class, 'get_all_companies'])->name('get-all-companies');
    Route::post("/register-company", [CompanyController::class, 'register_company']);
    Route::post("/import-companies", [CompanyController::class, 'import_companies'])->name('import-companies');
    Route::get("/download-company-sample", [CompanyController::class, 'download_company_sample']);
    Route::get("/delete-company/{id}", [CompanyController::class, 'delete_company']);
    Route::get("/delete-temp-company/{id}", [CompanyController::class, 'delete_temp_company']);
    Route::post("/delete-company-file", [CompanyController::class, 'delete_company_file'])->name('delete-company-file');
    Route::post("/accept-company-file", [CompanyController::class, 'accept_company_file'])->name('accept-company-file');
    Route::get("/company-data-cleaner", [CompanyController::class, 'company_data_cleaner'])->name('company-data-cleaner');
    Route::get("/company-dash", [CompanyController::class, 'company_dash']);
    Route::get("/view-temp-company/{id}", [CompanyController::class, 'view_temp_company']);
    Route::get("/view-company/{id}", [CompanyController::class, 'view_company']);
    Route::get("/edit-company/{id}", [CompanyController::class, 'edit_company']);
    Route::get("/edit-temp-company/{id}", [CompanyController::class, 'edit_temp_company']);
    Route::post("/update-company", [CompanyController::class, 'update_company']);
    Route::post("/update-temp-company", [CompanyController::class, 'update_temp_company']);
    Route::post("/company-export-excel", [CompanyController::class, 'company_export_excel'])->name('company-export-excel');
    Route::get("/approve-temp-company/{id}", [CompanyController::class, 'approve_company']);
    //End Company Controller

    //Home Controller
    Route::get('/admin-setting', [HomeController::class, 'staff_setting'])->name('admin-setting');
    Route::post('/save-basic-setup', [HomeController::class, 'save_basic_things']);
    Route::post('/save-admin-setting', [HomeController::class, 'save_staff_setting']);
    //End Home Controller

    //EmployeeController Controller
     Route::get("/employees-list/{type?}", [EmployeeController::class, 'employees'])->name('employees-list');
     Route::post("/create-employee", [EmployeeController::class, 'create_employee']);
     Route::get("/new-employee", [EmployeeController::class, 'new_employee']);
     Route::get("/delete-employee/{id}", [EmployeeController::class, 'delete_employee']);
     Route::post("/update-employee-profile", [EmployeeController::class, 'update_employee']);
     Route::get("/edit-employee/{id}", [EmployeeController::class, 'edit_employee']);
     Route::get("/view-employee/{id}", [EmployeeController::class, 'view_employee']);
     Route::post("/import-customers", [EmployeeController::class, 'import_customer']);
     Route::get("/assign-employee", [EmployeeController::class, 'assign_employee'])->name('assign-employee');
     Route::post("/assign-employee-to-company", [EmployeeController::class, 'assign_employee_to_company']);
     Route::get("/delete-assignemployees/{id}", [EmployeeController::class, 'edit_assignemployees']);
     Route::get("/edit-assignemployees/{id}", [EmployeeController::class, 'delete_assignemployees']);
     Route::post("/update-assign-employee-to-company", [EmployeeController::class, 'update_assign_employee_to_company']);
    //End EmployeeController Controller

    //Product Controller
    Route::get("/product-list-ajax", [ProductController::class, 'products'])->name('product-list-ajax');
    Route::get("/product-list/{type?}", [ProductController::class, 'products'])->name('product-list');
    Route::get("/create-product", [ProductController::class, 'create_product'])->name('create-product');
    Route::get("/download-product-sample", [ProductController::class, 'download_product_sample']);
    Route::get("/view-product/{id}", [ProductController::class, 'view_product']);
    Route::get("/view-temp-product/{id}", [ProductController::class, 'view_temp_product']);
    Route::get("/product-data-cleaner", [ProductController::class, 'product_data_cleaner'])->name('product-data-cleaner');
    Route::get("/product-dash", [ProductController::class, 'product_dash']);
    Route::get("/delete-product/{id}", [ProductController::class, 'delete_product']);
    Route::get("/delete-temp-product/{id}", [ProductController::class, 'delete_temp_product']);
    Route::get("/edit-product/{id}", [ProductController::class, 'edit_product']);
    Route::get("/edit-temp-product/{id}", [ProductController::class, 'edit_temp_product']);
    Route::get("/product-detail/{id}", [ProductController::class, 'product_detail']);
    Route::get("/approve-temp-product/{id}", [ProductController::class, 'approve_product']);
    Route::post("/save-product", [ProductController::class, 'save_product']);
    Route::post("/update-product", [ProductController::class, 'update_product']);
    Route::post("/update-temp-product", [ProductController::class, 'update_temp_product']);
    Route::post("/import-products", [ProductController::class, 'import_products']);
    Route::get("/brands-list", [ProductController::class, 'brands_list'])->name('brands-list');
    Route::get("/delete-brand/{id}", [ProductController::class, 'delete_brand']);
    Route::get('/products-export-excel', [ProductController::class, 'product_export_excel'])->name('products-export-excel');
    Route::get("/upload-bulk-products", [ProductController::class, 'upload_bulk_products'])->name('upload-bulk-products');
    Route::post("/get-all-products", [ProductController::class, 'get_all_products'])->name('get-all-products');
    Route::post("/delete-product-file", [ProductController::class, 'delete_product_file'])->name('delete-product-file');
    Route::post("/accept-product-file", [ProductController::class, 'accept_product_file'])->name('accept-product-file');
    //End Product Controller

    //Query Controller
    Route::get("/customer-demands", [CustomerDemandController::class, 'customer_demand_page'])->name('customer-demand-page');
    Route::get("/whatsapp-query", [CustomerDemandController::class, 'whatsapp_query']);
    Route::post("/customer-demand-ajax", [CustomerDemandController::class, 'product_demand_page_ajax']);
    Route::get("/customer-demand-uploading", [CustomerDemandController::class, 'customer_demand_uploading']);
    Route::get("/customer-demand-files", [CustomerDemandController::class, 'customer_demand_files']);
    Route::post("/customer-file-uploading", [CustomerDemandController::class, 'customer_file_uploading']);
    Route::post("/update-customer-demand-page-data", [CustomerDemandController::class, 'update_customer_demand_page_data']);
    Route::post("/delete-customer-demand-page-data", [CustomerDemandController::class, 'delete_customer_demand_page_data']);
    Route::get("/view-company-customers-demands/{id}", [CustomerDemandController::class, 'view_company_customers_demands']);
    Route::get("/view-customers-demands/{id}", [CustomerDemandController::class, 'view_customers_demands']);
    Route::get("/view-all-company-customers-demands/{id}", [CustomerDemandController::class, 'view_all_company_customers_demands']);
    Route::get("/delete-customers-demands/{id}", [CustomerDemandController::class, 'delete_customers_demands']);
    Route::post("/get-all-customers-demands", [CustomerDemandController::class, 'get_all_customers_demands'])->name('get-all-customers-demands');
    Route::post("/delete-this-customers-demands", [CustomerDemandController::class, 'delete_this_customers_demands'])->name('delete-this-customers-demands');
    Route::post("/save-this-customers-demands", [CustomerDemandController::class, 'save_this_customers_demands'])->name('save-this-customers-demands');
    //End Query Controller

    //UserDemandController
    Route::get("/users-demands", [UserDemandController::class, 'users_demands'])->name('users-demands');
    Route::post("/import-user-demands", [UserDemandController::class, 'import_user_demands'])->name('import-user-demands');
    Route::post("/delete-user-Demands", [UserDemandController::class, 'delete_user_Demands'])->name('delete-user-Demands');
    Route::post("/get-all-user-demands", [UserDemandController::class, 'get_all_user_demands'])->name('get-all-user-demands');
    //End UserDemandController

    //SupplierRecordController
    Route::get("/supplier-record", [SupplierRecordController::class, 'supplier_record'])->name('supplier-record');
    Route::post("/import-supplier-record", [SupplierRecordController::class, 'import_supplier_record'])->name('import-supplier-record');
    Route::post("/delete-supplier-records", [SupplierRecordController::class, 'delete_supplier_records'])->name('delete-supplier-records');
    Route::post("/get-all-suppliers", [SupplierRecordController::class, 'get_all_suppliers'])->name('get-all-suppliers');
    //End SupplierRecordController

    //SupplierPriceAnalysisController
    Route::get("/supplier-price-analysis", [SupplierPriceAnalysisController::class, 'supplier_price_analysis'])->name('supplier-price-analysis');
    Route::get("/testing-supplier-price-analysis", [SupplierPriceAnalysisController::class, 'testing_supplier_price_analysis']);
    Route::get("/export-records", [SupplierPriceAnalysisController::class, 'export_records'])->name('export-records');
    //End SupplierPriceAnalysisController

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
