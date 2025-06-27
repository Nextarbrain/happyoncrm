<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\AdminPermission;
use App\Http\Controllers\EventController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\OpenBillingController;
use App\Http\Controllers\TrashController;

Route::get('/', [AdminController::class, 'index'])->name('login');
Route::get('/logout-g', [AdminController::class, 'logout'])->name('logoutA');
Route::post('/auth', [AdminController::class, 'auth'])->name('auth');



Route::group(['middleware' => 'admin_auth'], function () {
    Route::resource('permission', PermissionController::class);
    Route::any('users/admin', [AdminPermission::class, 'index'])->name('admin.admin.permission');
    Route::any('users/role', [AdminPermission::class, 'role'])->name('admin.admin.role');
    Route::match(['GET', 'PUT'], 'users/role/edit/{id}', [AdminPermission::class, 'edit'])->name('admin.admin.role.edit');
    Route::match(['GET', 'PUT'], 'users/admin/edit/{id}', [AdminPermission::class, 'editAdmin'])->name('admin.admin.edit');
    //manage logout page

    // Route::get('/logout', function (Request $request) {
    //     Auth::guard('admin')->logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return redirect('/')->with('success', 'Logged out successfully.');
    // });


    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/get-branch-role', [AdminPermission::class, 'getBranchRole'])->name('getBranchRole');


    Route::post('/datawise-record-show', [AdminController::class, 'dashboard'])->name('datawiseRecordShow');
    Route::post('/branch-wise-data', [AdminController::class, 'dashboard'])->name('branchWiseData');

    Route::get('/admin', [AdminController::class, 'adminPlansView'])->name('admin');


    Route::get('/datawise-record-show/{date}', [AdminController::class, 'DatawiseRecord']);
    Route::get('/month_wise_sale', [AdminController::class, 'monthWise'])->name('monthWise');

    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/payment-method-filter/{date}', [AdminController::class, 'paymentMethodFilter'])->name('paymentMethodFilter');

    /******** Staff ********* */
    Route::get('/staffs', [StaffController::class, 'staffsView'])->name('staffs');
    Route::get('/add-staffs', [StaffController::class, 'addStaff'])->name('addStaff');
    Route::post('/add-staff-post', [StaffController::class, 'addStaffPost'])->name('addStaffPost');
    Route::post('/edit-staff-post', [StaffController::class, 'editStaffPost'])->name('editStaffPost');
    Route::get('/edit-staffs/{id}', [StaffController::class, 'editStaff'])->name('editStaff');
    Route::get('/change-status', [StaffController::class, 'changeStatus'])->name('changeStatus');
    Route::delete('/delete-staff', [StaffController::class, 'deleteStaff']);

    /******** Products ********* */

    Route::post('/productPostData', [AdminController::class, 'addProduct']);
    Route::post('/editProductPost', [AdminController::class, 'editProductPost']);
    Route::get('/edit-products-modal', [AdminController::class, 'editOpenModel']);
    Route::delete('/delete-products', [AdminController::class, 'deleteProduct']);


    /********** Coupon Code *************/

    Route::get('/get-coupon', [AdminController::class, 'getCoupon']);
    Route::post('/couponPostData', [AdminController::class, 'addCoupon']);
    Route::post('/editCouponPost', [AdminController::class, 'editCouponPost']);
    Route::get('/edit-coupon-modal', [AdminController::class, 'editCouponModal']);
    Route::delete('/delete-coupon', [AdminController::class, 'deleteCoupon']);

    /***********Admin Dayprice ************/

    Route::post('/editDayPricePost', [AdminController::class, 'editDayPricePost']);
    Route::get('/edit-dayprice-modal', [AdminController::class, 'editDayPriceModal']);

    Route::post('/editWeekendPost', [AdminController::class, 'editWeekendPost']);
    Route::get('/edit-weekend-modal', [AdminController::class, 'editWeekendModal']);

    /******************* Ticket Routes **************/



    //Route::get('/edit-tickets-view/{id}', [TicketController::class, 'editTicketsView']);
    Route::get('/tickets-edit-view/{id}', [TicketController::class, 'ticketEdit']);
    Route::post('/update-tickets', [TicketController::class, 'updateTickets']);
    Route::get('/invoice-pdf/{id}', [TicketController::class, 'invoiceGenerate']);
    Route::post('/tickets-booking-report', [TicketController::class, 'ticketsBookingReport'])->name('ticketsBookingReport');
    Route::get('/tickets', [TicketController::class, 'ticketsView'])->name('tickets');

    // ajax customer
    Route::get('/customers', [TicketController::class, 'custmerView'])->name('customers');


    Route::get('/all-customers', [TicketController::class, 'custmerAllView'])->name('allCustomers');
    Route::post('/get-ticket-phone-list', [TicketController::class, 'getPhoneList']);
    Route::post('/get-user-detail', [TicketController::class, 'getUserDetail']);
    Route::get('/get-price-kids', [TicketController::class, 'getPriceKids']);
    Route::post('/add-tickets', [TicketController::class, 'addTickets']);
    Route::post('/userPostData', [TicketController::class, 'addUser']);
    Route::delete('/delete-user', [TicketController::class, 'deleteUser']);
    Route::delete('/delete-customer', [TicketController::class, 'deleteCustomer']);
    Route::get('/customer-filter', [TicketController::class, 'filterCustomer']);
    Route::get('/upcomingbirthdays', [TicketController::class, 'upcomingBirthdaysView'])->name('upcomingBirthdays');

    /******************* Membership Routes **************/

    Route::post('/membership-booking-report', [MembershipController::class, 'membershipBookingReport'])->name('membershipBookingReport');
    Route::post('/membership-booking-usage', [MembershipController::class, 'membershipBookingUser'])->name('membershipBookingUser');
    Route::post('/get-membership-phone-list', [MembershipController::class, 'getMembershipPhoneList']);
    Route::get('/invoice-membership-pdf/{id}', [MembershipController::class, 'invoiceMembershipGenerate']);
    Route::get('/membership-history/{id}', [MembershipController::class, 'membershipHistoryView']);
    Route::get('/memberships', [MembershipController::class, 'membershipView'])->name('memberships');
    Route::get('/membership-history', [MembershipController::class, 'membershipHistory'])->name('membershipHistory');
    Route::post('/get-membership-plan', [MembershipController::class, 'getMembershipPlan']);
    Route::post('/add-membership-plan', [MembershipController::class, 'addMembershipPlan']);
    Route::get('/get-plan', [MembershipController::class, 'getPlan']);
    Route::post('/send-otp-membership', [MembershipController::class, 'SendOtpMembership']);
    Route::post('/update-membership', [MembershipController::class, 'updateMembership']);
    Route::post('/membershipPostData', [MembershipController::class, 'addMembership']);
    Route::post('/editMemberPost', [MembershipController::class, 'editMemberPost']);
    Route::get('/edit-member-modal', [MembershipController::class, 'editMemberModel']);
    Route::delete('/delete-membership', [MembershipController::class, 'deleteMembership']);
    Route::delete('/delete-membership-buy', [MembershipController::class, 'deleteMembershipBuyers']);
    Route::delete('/delete-membership-history', [MembershipController::class, 'deleteMembershipHistory']);

    Route::get('/get-branch-plan', [MembershipController::class, 'getBranchPlan']);

    /******************* Event Routes **************/

    Route::post('/event-booking-report', [EventController::class, 'eventBookingReport'])->name('eventBookingReport');
    Route::get('/events', [EventController::class, 'eventsView'])->name('events');
    Route::get('/add-event', [EventController::class, 'addEventView'])->name('addEventView');
    Route::post('/add-event-post', [EventController::class, 'addEventPost'])->name('addEventPost');
    Route::get('/edit-event/{id}', [EventController::class, 'editEventView'])->name('editEventView');
    Route::post('/edit-event-post', [EventController::class, 'editEventPost'])->name('editEventPost');
    Route::get('/invoice-event-pdf/{id}', [EventController::class, 'invoiceEvent'])->name('invoiceEvent');
    Route::post('/eventItemPostData', [EventController::class, 'addEventItem']);
    Route::post('/editEventItemPost', [EventController::class, 'editEventItemPost']);
    Route::get('/edit-event-item-modal', [EventController::class, 'editOpenEventItemModel']);
    Route::delete('/delete-event-item', [EventController::class, 'deleteEventItem']);
    Route::delete('/delete-event', [EventController::class, 'deleteEvent']);
    Route::get('/eventsbookingmonth', [EventController::class, 'eventsbookingmonthView'])->name('eventsbookingmonth');
    Route::get('/upcomingeventsbooking', [EventController::class, 'upcomingeventsbookingView'])->name('upcomingeventsbooking');

    /*******open Billing ***********/
    Route::get('/open-billing', [OpenBillingController::class, 'openBilling'])->name('openBilling');
    Route::get('/add-open-billing', [OpenBillingController::class, 'addOpenBillingView'])->name('addOpenBillingView');
    Route::post('/add-open-billing-post', [OpenBillingController::class, 'addOpenBillingPost'])->name('addOpenBillingPost');
    Route::get('/edit-open-billing/{id}', [OpenBillingController::class, 'editOpenBillingView'])->name('editOpenBillingView');
    Route::delete('/delete-open-billing-item', [OpenBillingController::class, 'deleteOpenBillingItem']);
    Route::post('/edit-open-billing-post', [OpenBillingController::class, 'editOpenBillingPost'])->name('editOpenBillingPost');
    Route::post('/open-billing-report', [OpenBillingController::class, 'openBillingReport'])->name('openBillingReport');
    Route::get('/invoice-open-billing-pdf/{id}', [OpenBillingController::class, 'invoiceOpenBilling'])->name('invoiceOpenBilling');
    Route::delete('/delete-open-billing', [OpenBillingController::class, 'deleteOpenBilling']);
    // Simple
    Route::get('/sample', function () {
        return view('happyView.sample');
    });

    Route::get('/code', [AdminController::class, 'qrCode']);
    Route::get('/trashed', [TrashController::class, 'index'])->name('trash');
    Route::get('/restore/{id}/{model}', [TrashController::class, 'restore'])->name('restore');
    Route::get('/delete/{id}/{model}', [TrashController::class, 'delete'])->name('delete');

    //BRANCH ROUTES
    Route::get('/get-membership-users-by-branch', [BranchController::class, 'getMembershipUserByBranch']);
    Route::get('/create-branch', [BranchController::class, 'branchView'])->name('branchView');
    Route::get('/edit-branch/{id}', [BranchController::class, 'editBranchView'])->name('editBranchView');
    Route::post('/edit-branch-post', [BranchController::class, 'editBranchPost'])->name('editBranchPost');
    Route::get('/branch', [BranchController::class, 'branch'])->name('branch');
    Route::post('/add-branch', [BranchController::class, 'addBranch'])->name('addBranch');
    Route::delete('/delete-branch', [BranchController::class, 'deleteBranch'])->name('deleteBranch');




});

// Clear application cache:
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return 'Application cache has been cleared';
});

//Clear route cache:
Route::get('/route-cache', function () {
    Artisan::call('route:cache');
    return 'Routes cache has been cleared';
});

//Clear config cache:
Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    return 'Config cache has been cleared';
});

// Clear view cache:
Route::get('/view-clear', function () {
    Artisan::call('view:clear');
    return 'View cache has been cleared';
});

Route::get('/updateapp', function () {
    Artisan::call('dump-autoload');
    return 'dump-autoload complete';
});






