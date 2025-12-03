<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\MonthlyBudgetController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\GoalLogController;
use App\Http\Controllers\UserController;

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


Route::group(['middleware' => 'auth'], function () {

	Route::get('/', [HomeController::class, 'home']);
	Route::get('dashboard', function () {
		return view('dashboard');
	})->name('dashboard');

	Route::get('billing', function () {
		return view('billing');
	})->name('billing');

	Route::get('profile', function () {
		return view('profile');
	})->name('profile');

	Route::get('rtl', function () {
		return view('rtl');
	})->name('rtl');


	Route::get('tables', function () {
		return view('tables');
	})->name('tables');

	Route::get('virtual-reality', function () {
		return view('virtual-reality');
	})->name('virtual-reality');

	Route::get('static-sign-in', function () {
		return view('static-sign-in');
	})->name('sign-in');

	Route::get('static-sign-up', function () {
		return view('static-sign-up');
	})->name('sign-up');

	Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
	Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');



	Route::get('/', function () {
		return redirect()->route('dashboard');
	});

	// Dashboard (Ringkasan saldo, grafik, total pengeluaran, sisa budget)
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

	// Category CRUD
	Route::resource('categories', CategoryController::class);

	// Budget CRUD (Monthly / yearly budget)
	Route::resource('budgets', DashboardController::class);
	Route::resource('monthly-budget', MonthlyBudgetController::class);

	// Transaction (Income & Expense)
	Route::get('/transactions/income', [TransactionController::class, 'income'])
		->name('transactions.income');
	Route::get('/transactions/expense', [TransactionController::class, 'expense'])
		->name('transactions.expense');

	Route::resource('transactions', TransactionController::class);
	Route::resource('goals', GoalController::class);
	Route::middleware(['auth', 'isAdmin'])->group(function () {
		Route::resource('users', UserController::class);
	});

	// Optional: filter transaksi berdasarkan tanggal / kategori
	Route::get('/transactions/filter/{type?}', [TransactionController::class, 'filter'])->name('transactions.filter');

	Route::post('/goals/{goal}/log', [GoalLogController::class, 'store'])->name('goal.log.store');
	Route::delete('/goal-log/{log}', [GoalLogController::class, 'destroy'])->name('goal.log.delete');
});



Route::group(['middleware' => 'guest'], function () {
	Route::get('/register', [RegisterController::class, 'create']);
	Route::post('/register', [RegisterController::class, 'store']);
	Route::get('/login', [SessionsController::class, 'create']);
	Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

Route::get('/login', function () {
	return view('session/login-session');
})->name('login');
