<?php

use App\Http\Controllers\Accessories;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accessories\BulkAssignedAccessoriesController;

/*
* Accessories
 */
Route::group(['prefix' => 'accessories', 'middleware' => ['auth']], function () {
    Route::get(
        '{accessoryID}/checkout',
        [Accessories\AccessoryCheckoutController::class, 'create']
    )->name('accessories.checkout.show');

    Route::get(
        '{accessoryID}/update',
        [Accessories\AccessoryCheckoutController::class, 'view_update']
    )->name('accessories.update.view');

    Route::post(
        '{accessory}/update',
        [Accessories\AccessoryCheckoutController::class, 'update']
    )->name('accessories.update.store');

    Route::post(
        '{accessory}/checkout',
        [Accessories\AccessoryCheckoutController::class, 'store']
    )->name('accessories.checkout.store');

    Route::get(
        '{accessoryID}/checkin/{backto?}',
        [Accessories\AccessoryCheckinController::class, 'create']
    )->name('accessories.checkin.show');

    Route::post(
        '{accessoryID}/checkin/{backto?}',
        [Accessories\AccessoryCheckinController::class, 'store']
    )->name('accessories.checkin.store');

    Route::post(
        '{accessoryId}/upload',
        [Accessories\AccessoriesFilesController::class, 'store']
    )->name('upload/accessory');

    Route::delete(
        '{accessoryId}/deletefile/{fileId}',
        [Accessories\AccessoriesFilesController::class, 'destroy']
    )->name('delete/accessoryfile');

    Route::get(
        '{accessoryId}/showfile/{fileId}/{download?}',
        [Accessories\AccessoriesFilesController::class, 'show']
    )->name('show.accessoryfile');

    Route::get('{accessory}/clone',
            [Accessories\AccessoriesController::class, 'getClone']
        )->name('clone/accessories');

    Route::post('{accessoryId}/clone', 
        [Accessories\AccessoriesController::class, 'postCreate']
    );

    Route::post(
        'bulkedit',
        [BulkAssignedAccessoriesController::class, 'edit']
    )->name('accessories/bulkedit');

    Route::post(
        'bulkupdate',
        [BulkAssignedAccessoriesController::class, 'update']
    )->name('accessories/bulkupdate');
});

Route::resource('accessories', Accessories\AccessoriesController::class, [
    'middleware' => ['auth']
]);
