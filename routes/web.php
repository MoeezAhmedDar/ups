<?php

use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\Marble\ProductController;
use App\Http\Controllers\Marble\CategoryController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// Route::middleware(['auth', 'isAdmin'])->group(function () {

//     // Route::get('/', function () {
//     //     return view('dashboard');
//     // });

//     //Route::get('quotation', QuotationController::class);
//     //Route::get('/logout', [AuthenticatedSessionController::class,"destroy"])->name('logout');


// });



// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');
Route::get('/', function () {
   return redirect('dashboard');
});
Route::get('/clear-cache', function () {
   \Artisan::call('config:clear');
   \Artisan::call('route:clear');
   \Artisan::call('view:clear');

   dd('cleared');
});

require __DIR__ . '/auth.php';

Route::group(['middleware' => 'auth'], function () {
   Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');
   Route::get('/users', [Dashboard::class, 'users'])->name('users');
   Route::get('register-form/', [RegisteredUserController::class, 'create'])->name('register-form');
   Route::post('register-user/', [RegisteredUserController::class, 'store'])->name('register-user');
   Route::get('category/', [CategoryController::class, 'index'])->name('category');
   Route::get('deletecategory/{id}', [CategoryController::class, 'deleteCategory'])->name('deletecategory');
   Route::get('add-category/', [CategoryController::class, 'addCategory'])->name('add-category');
   Route::post('store/', [CategoryController::class, 'insertCategory'])->name('store');
   Route::post('insert-category/', [CategoryController::class, 'insertCategory']);

   Route::get('view_subcategory', [SubCategoryController::class, 'index'])->name('view_subcategory');
   Route::get('add_subcategory', [SubCategoryController::class, 'create'])->name('add_subcategory');
   Route::post('insert_subcategory', [SubCategoryController::class, 'store'])->name('insert_subcategory');
   Route::get('deletecompany/{id}', [SubCategoryController::class, 'destroy'])->name('deletecompany');


   Route::get('product/', [ProductController::class, 'index'])->name('product');
   Route::get('add-products/', [ProductController::class, 'addProduct'])->name('add-products');
   Route::post('insert-Product/', [ProductController::class, 'insertProduct']);
   Route::get('update-products/{id}', [ProductController::class, 'updateProduct']);
   Route::put('edit-Product/{id}', [ProductController::class, 'editProduct']);
   Route::get('delete-products/{id}', [ProductController::class, 'deleteProduct']);
   Route::get('/sub_category_ajax/{id?}', [ProductController::class, "sub_category_ajax"])->name('sub_category_ajax');
   Route::get('/price_search_ajax/{id?}', [ProductController::class, "price_search_ajax"])->name('price_search_ajax');
   Route::get('/available_qty_search_ajax/{id?}', [ProductController::class, "available_qty_search_ajax"])->name('available_qty_search_ajax');

   Route::get('/view_ledger/{id?}', [LedgerController::class, "index"])->name('view_ledger');
   Route::get('/view_ledger_customer/{id}', [LedgerController::class, "index_customer"])->name('view_ledger_customer');
   Route::get('/get_Ledgerdata', [LedgerController::class, "getLedgerdata"])->name('get_Ledgerdata');
   Route::post('/insert_ledger', [LedgerController::class, "store"])->name('insert_ledger');
   Route::get('/gneratedpdfview', [LedgerController::class, "gnerated_pdf_view"])->name('gneratedpdfview');
   Route::get('/gneratedpdfview_customer', [LedgerController::class, "gneratedpdfview_customer"])->name('gneratedpdfview_customer');
   Route::post('/addincentive', [LedgerController::class, "add_incentive"])->name('addincentive');

   Route::get('/quotation', [QuotationController::class, "index"])->name('quotation');
   Route::get('/create_quotation', [QuotationController::class, "create"])->name('create_quotation');
   Route::post('/store_quotation', [QuotationController::class, "store"])->name('store_quotation');
   Route::get('/quotation_pdf', [QuotationController::class, "generatePDF"])->name('quotation_pdf');
   Route::get('edit-quotation/{id}', [QuotationController::class, 'edit'])->name('edit-quotation');
   Route::get('/print_quotation', [QuotationController::class, "printquotation"])->name('print_quotation');
   //Route::put('update-quotation',[QuotationController::class, 'edit'])->name('update-quotation');
   //vendors
   Route::resource('vendor', VendorController::class);


   //stock
   Route::resource('stock', StockController::class);
   Route::get('/availblestock', [ProductController::class, "availblestock"])->name('availblestock');




   //customer
   Route::post('return', [\App\Http\Controllers\CustomerController::class, "returnstock"])->name('return');
   Route::get('searchinvoice', [\App\Http\Controllers\CustomerController::class, "search_invoice"])->name('searchinvoice');
   Route::resource('customer', CustomerController::class);
   Route::get('sales/edit/{id}', [SalesController::class, 'edit'])->name('sales.edit');
   Route::put('sales/edit/{id}', [SalesController::class, 'update']);
   Route::get('sale_print', [ProductController::class, "sale_print"])->name('sale_print');
   //Route::get('/logout', [AuthenticatedSessionController::class,"destroy"])->name('logout');
   //expense
   Route::get('bank_detail', [\App\Http\Controllers\ExpenseController::class, "bankdetails"])->name('bank_detail');
   Route::get('cash_detail', [\App\Http\Controllers\ExpenseController::class, "cashdetail"])->name('cash_detail');
   Route::get('income_expense', [\App\Http\Controllers\ExpenseController::class, "incomeexpense"])->name('income_expense');
   Route::get('get_Expensedata', [\App\Http\Controllers\ExpenseController::class, "getExpensedata"])->name('get_Expensedata');
   Route::get('generateincomeexpense_pdf', [\App\Http\Controllers\ExpenseController::class, "generate_income_expense_pdf"])->name('generateincomeexpense_pdf');
   Route::resource('expense', ExpenseController::class);
   Route::post('add_bankbalance', [\App\Http\Controllers\BankController::class, "add_bank_balance"])->name('add_bankbalance');
   Route::resource('bank', BankController::class);

   Route::get('profit', [\App\Http\Controllers\ProfitController::class, "index"])->name('profit');
   Route::get('profit/pdf', [\App\Http\Controllers\ProfitController::class, "pdf"])->name('profit.pdf');

   Route::get('optimize-clear', function () {
      Artisan::call('optimize:clear');
      return "Cache is cleared";
   });
});
