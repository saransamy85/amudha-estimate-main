<?php

use App\Http\Controllers\Accounts\AccountsController;
use App\Http\Controllers\Purchase\PurchaseDashboardController;
use App\Http\Controllers\Purchase\PurchaseOrderController;
use App\Http\Controllers\Purchase\PurchaseReportController;
use App\Http\Controllers\Purchase\VendorController;
use App\Http\Controllers\Reception\dashboardcontroller;
use App\Http\Controllers\Reception\MaterialItemController;
use App\Http\Controllers\Reception\MaterialReturnController;
use App\Http\Controllers\Reception\ReceptionReportController;
use App\Http\Controllers\admincontroller;
use App\Http\Controllers\customercontroller;
use App\Http\Controllers\estimatecontroller;
use App\Http\Controllers\greetingcontroller;
use App\Http\Controllers\LeadActivitycontroller;
use App\Http\Controllers\pocontroller;
use App\Http\Controllers\reportcontroller;
use App\Http\Controllers\salescontroller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RcsController;

// usermanagement//
Route::get('/admin/user-management', [admincontroller::class, 'usermanagement'])->name('usermanagement');

// purchase//
Route::get('/purchase/dashboard', [pocontroller::class, 'podashboard'])->name('podash');
Route::get('/purchase/PO', [pocontroller::class, 'poviews'])->name('poview');
// Sales//
Route::get('/sales', [salescontroller::class, 'salesdash'])->name('salesdashboard');
Route::get('/sales-customer', [salescontroller::class, 'salescus'])->name('salescustomer');
Route::get('/sales-estimate', [salescontroller::class, 'addestimate'])->name('addestimate');
Route::get('/sales-lead', [salescontroller::class, 'leaddash'])->name('leaddash');
Route::post('/sales-New-lead', [salescontroller::class, 'addlead'])->name('addlead');
Route::post('/sales-lead-feedback', [salescontroller::class, 'addfeedback'])->name('addfeedback');
Route::get('/sales-greetings', [salescontroller::class, 'salesgreetings'])->name('salesgreetings');

// Route::get('/customers',[admincontroller::class,'cus'])->name('customers');

//RCS
Route::post('/send-rcs', [RcsController::class, 'sendTextOnly'])->name('rcs.send');
Route::get('/send-rcs', [admincontroller::class, 'msgs'])->name('rcsmsg');

Route::get('/dashboard/customers', [customercontroller::class, 'clients'])->name('customers');
Route::Post('/dashboard/customers', [customercontroller::class, 'clientstore'])->name('addcustomers');
Route::post('/estimates/{id}/convert-customer', [customercontroller::class, 'convertToCustomer'])
    ->name('customers.convert');

Route::get('/estimates', [estimatecontroller::class, 'index'])->name('dashboard');

Route::get('estimates/{id}/pdf', [estimatecontroller::class, 'pdf'])
    ->name('estimates.pdf');

Route::delete('estimates/items/{id}', [estimatecontroller::class, 'destroyItem'])
    ->name('estimates.items.destroy');

Route::resource('estimates', estimatecontroller::class);

Route::get('estimates/create/{lead_id}', [estimatecontroller::class, 'create1'])->name('estimates.create1');

/* ---Admin----- */
Route::get('/reg', [admincontroller::class, 'registration'])->name('reg');
Route::post('/reg', [admincontroller::class, 'regsubmission'])->name('reg1');
Route::get('/', [admincontroller::class, 'login'])->name('login');
Route::post('/', [admincontroller::class, 'logins'])->name('logins');
Route::get('/logout', [admincontroller::class, 'logout'])->name('logout');
Route::get('/dashboard', [admincontroller::class, 'dashboard'])->name('dashboard1');
Route::get('/admin-lead', [admincontroller::class, 'adminlead'])->name('adminlead');
Route::get('/admin-customer', [admincontroller::class, 'admincus'])->name('admincustomer');
Route::get('/admin-reports', [reportcontroller::class, 'adminreport'])->name('adminreport');
Route::put('/customer/status/{id}', [customercontroller::class, 'updateStatus'])->name('customerstatusupdate');
Route::get('/admin/po-orders', [admincontroller::class, 'po_orders'])->name('admin.po_orders');

Route::get('/leads/{id}/edit', [admincontroller::class, 'edit'])->name('leads.edit');

Route::put('/leads/{id}', [admincontroller::class, 'update'])->name('leads.update');

Route::delete('/leads/{id}', [admincontroller::class, 'destroy'])->name('leads.destroy');
Route::get('/leads/delete/{id}', [admincontroller::class, 'destroy'])->name('leads.delete');

/* ---Greeting mail--- */
Route::post('/greeting', [greetingcontroller::class, 'sendGreeting'])->name('greetings');
Route::get('/greeting', [greetingcontroller::class, 'sendmail'])->name('mail');

Route::get('/admin/leadactivity', [LeadActivitycontroller::class, 'create'])->name('leadactivity');
Route::post('/admin/leadactivity', [LeadActivitycontroller::class, 'store'])->name('activitystore');

Route::get('/daily-report-pdf', [admincontroller::class, 'dailyReportPdf'])->name('dailyreport');
Route::get('/weekly-report-pdf', [admincontroller::class, 'weeklyReportPdf'])->name('weeklyreport');
Route::match(['get', 'post'], '/source-report-pdf', [admincontroller::class, 'sourceReportPdf'])->name('sourcereport');
Route::get('/reports/custom', [reportcontroller::class, 'customReportForm'])->name('monthlyreport');
Route::post('/reports/custom', [reportcontroller::class, 'customReportPdf'])->name('customreport.pdf');
Route::get('/estimate-monthly-report-pdf', [reportcontroller::class, 'estimateMonthlyReportPdf'])->name('estimatemonthlyreportpdf');
Route::get('/customer-monthly-report-pdf', [reportcontroller::class, 'customerMonthlyReportPdf'])->name('customermonthlyreportpdf');

/* ---Receptionist---- */
Route::get('/reception-dashboard', [dashboardcontroller::class, 'index'])->name('receptiondashboard');
Route::get('/reception-sites', [dashboardcontroller::class, 'sites'])->name('receptionsites');
Route::get('/reception-materialitems', [MaterialItemController::class, 'index'])->name('receptionmaterialitems');
Route::post('/addmaterialitems', [MaterialItemController::class, 'store'])->name('addmaterialitems');
Route::get('/site-timeline/{id}', [dashboardcontroller::class, 'siteTimeline'])->name('sitetimeline');
Route::get('/reception/reports', [ReceptionReportController::class, 'dashboard'])->name('reception.reports');
/* return */
Route::get('/material-return', [MaterialReturnController::class, 'index'])->name('materialreturn');

Route::get('/material-return/items/{customer}', [MaterialReturnController::class, 'getDispatchItems'])->name('materialreturn.items');
Route::post('/material-return/store', [MaterialReturnController::class, 'store'])->name('materialreturn.store');
Route::get('/material-return/history', [dashboardcontroller::class, 'history'])->name('materialreturn.history');
Route::get('/material-return/view/{id}', [dashboardcontroller::class, 'returnview'])->name('materialreturn.view');

Route::prefix('purchase')->group(function () {
    Route::get('/dashboard', [PurchaseDashboardController::class, 'index'])->name('purchasedashboard');

    Route::get('/vendors', [VendorController::class, 'index'])->name('vendors');

    Route::post('/vendors/store', [VendorController::class, 'store'])->name('vendor.store');

    Route::post('/vendors/update/{id}', [VendorController::class, 'update'])->name('vendor.update');

    Route::get('/vendors/delete/{id}', [VendorController::class, 'destroy'])->name('vendor.delete');

    Route::get('/vendors/{id}/purchase-orders', [VendorController::class, 'viewPurchaseOrders'])->name('vendor.purchase_orders');

    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase.index');

    Route::get('/purchase-order/create', [PurchaseOrderController::class, 'create'])->name('purchase.create');

    Route::post('/purchase-order/store', [PurchaseOrderController::class, 'store'])->name('purchase.store');

    Route::get('/purchase-orders/view/{id}', [PurchaseOrderController::class, 'view'])->name('purchase.view');

    Route::get('/purchase-orders/edit/{id}', [PurchaseOrderController::class, 'edit'])->name('purchase.edit');

    Route::post('/purchase-orders/update/{id}', [PurchaseOrderController::class, 'update'])->name('purchase.update');

    Route::delete('/purchase/delete/{id}', [PurchaseOrderController::class, 'destroy'])->name('purchase.delete');

    // Purchase Reports & Exports
    Route::get('/reports', [PurchaseReportController::class, 'index'])->name('purchase.reports');
    Route::get('/report/monthly-pdf', [PurchaseReportController::class, 'monthlyPoReportPdf'])->name('purchase.report.monthly');
    Route::post('/report/custom-pdf', [PurchaseReportController::class, 'customPoReportPdf'])->name('purchase.report.custom');
    Route::get('/report/export-csv', [PurchaseReportController::class, 'exportCsv'])->name('purchase.report.csv');
});
Route::get('/purchase/template/{template}', [PurchaseOrderController::class, 'loadTemplate']);
Route::get('/purchase/pdf/{id}', [PurchaseOrderController::class, 'pdf'])->name('purchase.pdf');

Route::get('/accounts', [AccountsController::class, 'home'])->name('accountshome');
Route::get('/accounts/customer-search', [AccountsController::class, 'customerSearch'])->name('customersearch');
Route::get('/accounts/report/pdf/{lead_id}', [AccountsController::class, 'customerReportPdf'])->name('customer.report.pdf');
Route::get('/accounts/attendance', [AccountsController::class, 'attendance'])->name('accounts.attendance');
Route::post('/accounts/attendance/update', [AccountsController::class, 'updateAttendanceStatus'])->name('accounts.attendance.update');
Route::get('/accounts/attendance/export-csv', [AccountsController::class, 'exportAttendanceCsv'])->name('accounts.attendance.csv');

// Individual Staff Self-Attendance Punch In & Out (Available to all logged-in staff)
Route::post('/my-attendance/checkin', [AccountsController::class, 'myCheckIn'])->name('attendance.checkin');
Route::post('/my-attendance/checkout', [AccountsController::class, 'myCheckOut'])->name('attendance.checkout');
