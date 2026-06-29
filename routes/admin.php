<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerUnitController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FreonController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\MaintenanceContractController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ServiceReportController;
use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\Admin\SparepartController;
use App\Http\Controllers\Admin\TechnicianController;
use App\Http\Controllers\Admin\WorkOrderController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {

    Route::get('/users/list', function () {
        return User::select('id', 'name', 'email')->get();
    })->name('users.list');

    // Dashboard — view permission
    Route::middleware('permission:dashboard.view')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });

    // Role & Permission Management
    Route::prefix('roles')->name('roles.')->middleware('permission:roles.view')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/data', [RoleController::class, 'data'])->name('data');
        Route::post('/', [RoleController::class, 'store'])->name('store')->middleware('permission:roles.create');
        Route::get('/{role}', [RoleController::class, 'show'])->name('show');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update')->middleware('permission:roles.edit');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy')->middleware('permission:roles.delete');
        Route::get('/{role}/permissions', [RoleController::class, 'permissions'])->name('permissions');
        Route::put('/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('update-permissions');
    });

    Route::prefix('permissions')->name('permissions.')->middleware('permission:permissions.view')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/data', [PermissionController::class, 'data'])->name('data');
        Route::post('/', [PermissionController::class, 'store'])->name('store')->middleware('permission:permissions.create');
        Route::get('/{permission}', [PermissionController::class, 'show'])->name('show');
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('update')->middleware('permission:permissions.edit');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy')->middleware('permission:permissions.delete');
    });

    // Customers
    Route::prefix('customers')->name('customers.')->middleware('permission:customers.view')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/data', [CustomerController::class, 'data'])->name('data');
        Route::post('/', [CustomerController::class, 'store'])->name('store')->middleware('permission:customers.create');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update')->middleware('permission:customers.edit');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy')->middleware('permission:customers.delete');
    });

    // Customer Units
    Route::prefix('customer-units')->name('customer-units.')->middleware('permission:customer-units.view')->group(function () {
        Route::get('/', [CustomerUnitController::class, 'index'])->name('index');
        Route::get('/data', [CustomerUnitController::class, 'data'])->name('data');
        Route::get('/by-customer/{customer}', [CustomerUnitController::class, 'byCustomer'])->name('by-customer');
        Route::post('/', [CustomerUnitController::class, 'store'])->name('store')->middleware('permission:customer-units.create');
        Route::get('/{customerUnit}', [CustomerUnitController::class, 'show'])->name('show');
        Route::put('/{customerUnit}', [CustomerUnitController::class, 'update'])->name('update')->middleware('permission:customer-units.edit');
        Route::delete('/{customerUnit}', [CustomerUnitController::class, 'destroy'])->name('destroy')->middleware('permission:customer-units.delete');
    });

    // Technicians
    Route::prefix('technicians')->name('technicians.')->middleware('permission:technicians.view')->group(function () {
        Route::get('/', [TechnicianController::class, 'index'])->name('index');
        Route::get('/data', [TechnicianController::class, 'data'])->name('data');
        Route::post('/', [TechnicianController::class, 'store'])->name('store')->middleware('permission:technicians.create');
        Route::get('/{technician}', [TechnicianController::class, 'show'])->name('show');
        Route::put('/{technician}', [TechnicianController::class, 'update'])->name('update')->middleware('permission:technicians.edit');
        Route::delete('/{technician}', [TechnicianController::class, 'destroy'])->name('destroy')->middleware('permission:technicians.delete');
    });

    // Work Orders
    Route::prefix('work-orders')->name('work-orders.')->middleware('permission:work-orders.view')->group(function () {
        Route::get('/', [WorkOrderController::class, 'index'])->name('index');
        Route::get('/data', [WorkOrderController::class, 'data'])->name('data');
        Route::get('/{workOrder}/detail', [WorkOrderController::class, 'detail'])->name('detail');
        Route::get('/{workOrder}/technicians', [WorkOrderController::class, 'technicians'])->name('technicians');
        Route::post('/', [WorkOrderController::class, 'store'])->name('store')->middleware('permission:work-orders.create');
        Route::get('/{workOrder}', [WorkOrderController::class, 'show'])->name('show');
        Route::put('/{workOrder}', [WorkOrderController::class, 'update'])->name('update')->middleware('permission:work-orders.edit');
        Route::delete('/{workOrder}', [WorkOrderController::class, 'destroy'])->name('destroy')->middleware('permission:work-orders.delete');
        Route::post('/{workOrder}/update-progress', [WorkOrderController::class, 'updateProgress'])->name('update-progress');
        Route::post('/{workOrder}/complete', [WorkOrderController::class, 'complete'])->name('complete');
        Route::post('/{workOrder}/photos', [WorkOrderController::class, 'uploadPhoto'])->name('upload-photo');
        Route::delete('/{workOrder}/photos/{photo}', [WorkOrderController::class, 'deletePhoto'])->name('delete-photo');
    });

    // Service Reports
    Route::prefix('service-reports')->name('service-reports.')->middleware('permission:service-reports.view')->group(function () {
        Route::get('/', [ServiceReportController::class, 'index'])->name('index');
        Route::get('/data', [ServiceReportController::class, 'data'])->name('data');
        Route::post('/', [ServiceReportController::class, 'store'])->name('store')->middleware('permission:service-reports.create');
        Route::get('/{serviceReport}', [ServiceReportController::class, 'show'])->name('show');
        Route::put('/{serviceReport}', [ServiceReportController::class, 'update'])->name('update')->middleware('permission:service-reports.edit');
        Route::delete('/{serviceReport}', [ServiceReportController::class, 'destroy'])->name('destroy')->middleware('permission:service-reports.delete');
    });

    // Complaints
    Route::prefix('complaints')->name('complaints.')->middleware('permission:complaints.view')->group(function () {
        Route::get('/', [ComplaintController::class, 'index'])->name('index');
        Route::get('/data', [ComplaintController::class, 'data'])->name('data');
        Route::post('/', [ComplaintController::class, 'store'])->name('store')->middleware('permission:complaints.create');
        Route::get('/{complaint}', [ComplaintController::class, 'show'])->name('show');
        Route::put('/{complaint}', [ComplaintController::class, 'update'])->name('update')->middleware('permission:complaints.edit');
        Route::delete('/{complaint}', [ComplaintController::class, 'destroy'])->name('destroy')->middleware('permission:complaints.delete');
    });

    // Spareparts
    Route::prefix('spareparts')->name('spareparts.')->middleware('permission:spareparts.view')->group(function () {
        Route::get('/', [SparepartController::class, 'index'])->name('index');
        Route::get('/data', [SparepartController::class, 'data'])->name('data');
        Route::post('/', [SparepartController::class, 'store'])->name('store')->middleware('permission:spareparts.create');
        Route::get('/{sparepart}', [SparepartController::class, 'show'])->name('show');
        Route::put('/{sparepart}', [SparepartController::class, 'update'])->name('update')->middleware('permission:spareparts.edit');
        Route::delete('/{sparepart}', [SparepartController::class, 'destroy'])->name('destroy')->middleware('permission:spareparts.delete');
    });

    // Freon Inventory
    Route::prefix('freon-inventory')->name('freon-inventory.')->middleware('permission:freon.view')->group(function () {
        Route::get('/', [FreonController::class, 'index'])->name('index');
        Route::get('/data', [FreonController::class, 'data'])->name('data');
        Route::post('/', [FreonController::class, 'store'])->name('store')->middleware('permission:freon.create');
        Route::get('/{freonInventory}', [FreonController::class, 'show'])->name('show');
        Route::put('/{freonInventory}', [FreonController::class, 'update'])->name('update')->middleware('permission:freon.edit');
        Route::delete('/{freonInventory}', [FreonController::class, 'destroy'])->name('destroy')->middleware('permission:freon.delete');
    });

    // Quotations
    Route::prefix('quotations')->name('quotations.')->middleware('permission:quotations.view')->group(function () {
        Route::get('/', [QuotationController::class, 'index'])->name('index');
        Route::get('/data', [QuotationController::class, 'data'])->name('data');
        Route::post('/', [QuotationController::class, 'store'])->name('store')->middleware('permission:quotations.create');
        Route::get('/{quotation}', [QuotationController::class, 'show'])->name('show');
        Route::put('/{quotation}', [QuotationController::class, 'update'])->name('update')->middleware('permission:quotations.edit');
        Route::delete('/{quotation}', [QuotationController::class, 'destroy'])->name('destroy')->middleware('permission:quotations.delete');
    });

    // Invoices
    Route::prefix('invoices')->name('invoices.')->middleware('permission:invoices.view')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/data', [InvoiceController::class, 'data'])->name('data');
        Route::post('/', [InvoiceController::class, 'store'])->name('store')->middleware('permission:invoices.create');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update')->middleware('permission:invoices.edit');
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy')->middleware('permission:invoices.delete');
    });

    // Payments
    Route::prefix('payments')->name('payments.')->middleware('permission:payments.view')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/data', [PaymentController::class, 'data'])->name('data');
        Route::post('/', [PaymentController::class, 'store'])->name('store')->middleware('permission:payments.create');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        Route::put('/{payment}', [PaymentController::class, 'update'])->name('update')->middleware('permission:payments.edit');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy')->middleware('permission:payments.delete');
    });

    // Maintenance Contracts
    Route::prefix('maintenance-contracts')->name('maintenance-contracts.')->middleware('permission:contracts.view')->group(function () {
        Route::get('/', [MaintenanceContractController::class, 'index'])->name('index');
        Route::get('/data', [MaintenanceContractController::class, 'data'])->name('data');
        Route::post('/', [MaintenanceContractController::class, 'store'])->name('store')->middleware('permission:contracts.create');
        Route::get('/{maintenanceContract}', [MaintenanceContractController::class, 'show'])->name('show');
        Route::put('/{maintenanceContract}', [MaintenanceContractController::class, 'update'])->name('update')->middleware('permission:contracts.edit');
        Route::delete('/{maintenanceContract}', [MaintenanceContractController::class, 'destroy'])->name('destroy')->middleware('permission:contracts.delete');
    });
});
