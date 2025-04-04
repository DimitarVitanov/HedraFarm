<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

#users
use App\Http\Controllers\UserController;
#company
use App\Http\Controllers\CompanyInfoController;
#slider
use App\Http\Controllers\SliderController;
#products
use App\Http\Controllers\ProductController;
#prdocut categories
use App\Http\Controllers\ProductCategoryController;
#product subcategories
use App\Http\Controllers\ProductSubcategoryController;
#blog controller
use App\Http\Controllers\BlogController;
use App\Http\Controllers\OrderController;

#order

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(App\Http\Controllers\Auth\PasswordController::class)->group(function () {
        Route::get('/password/set', 'show')->name('password.set');
        Route::post('/password/set', 'store');
    });
});

Route::get('/', function () {
    return Inertia::render('Home/Index');
})->name('home');

Route::middleware('auth')->prefix('admin')->group(function () {
    /* Admin Users */
    Route::group(['prefix'=>'users'],function(){
        Route::get('/', function () {
            return Inertia::render('Admin/Users/Index');
        })->name('admin.users');

        Route::get('fetch', [UserController::class, 'fetchUsers'])->name('admin.users.fetch');
        Route::post('store', [UserController::class, 'store'])->name('admin.users.store');
        Route::post('update', [UserController::class, 'update'])->name('admin.users.update');
        Route::post('delete', [UserController::class, 'delete'])->name('admin.users.delete');
    });

    Route::group(['prefix' => 'company'], function(){
        Route::get('/', function(){
            return Inertia::render('Admin/Company/Index');
        })->name('admin.company');

        Route::post('store', [CompanyInfoController::class, 'store'])->name('admin.company.store');
    });

    Route::group(['prefix' => 'sliders'], function(){
        Route::get('/', function(){
            return Inertia::render('Admin/Slider/Index');
        })->name('admin.slider');

        Route::post('store', [SliderController::class, 'store'])->name('admin.slider.store');
        Route::post('update', [SliderController::class, 'update'])->name('admin.slider.update');
        Route::post('delete', [SliderController::class, 'delete'])->name('admin.slider.delete');
    });

    Route::group(['prefix' => 'products'], function(){
        Route::get('/', function(){
            return Inertia::render('Admin/Products/Index');
        })->name('admin.products');
        Route::post('store', [ProductController::class, 'store'])->name('admin.products.store');
        Route::post('update', [ProductController::class, 'update'])->name('admin.products.update');
        Route::post('delete', [ProductController::class, 'delete'])->name('admin.products.delete');
    });

    Route::group(['prefix' => 'product-categories'], function(){
       /*
        Route::get('/', function(){
            return Inertia::render('Admin/Categories/Index');
        })->name('admin.categories');
        */
    });
    Route::group(['prefix' => 'blogs'], function(){
        Route::get('/', function(){
            return Inertia::render('Admin/Blog/Index');
        })->name('admin.blog');
        Route::post('store', [BlogController::class, 'store'])->name('blog.store');
        Route::post('update', [BlogController::class, 'update'])->name('blog.update');
        Route::post('delete', [BlogController::class, 'delete'])->name('blog.delete');
    });

    Route::group(['prefix' => 'orders'], function(){
        Route::get('/', function(){
            return Inertia::render('Admin/Orders/Index');
        })->name('admin.orders');
        Route::get('fetch', [OrderController::class, 'getOrders'])->name('admin.orders.fetch');
        Route::post('update', [OrderController::class, 'update'])->name('admin.orders.update');
    });
});
Route::get('/product-categories/fetch', [ProductCategoryController::class, 'getCategories'])->name('admin.categories.fetch');


#company
Route::group(['prefix' => 'company'], function(){
    Route::get('fetch', [CompanyInfoController::class, 'getCompanyInfo'])->name('company.fetch');
});

#sliders
Route::get('sliders/fetch', [SliderController::class, 'getSliders'])->name('admin.slider.fetch');

#about
Route::get('about', function(){
    return Inertia::render('About/Index');
})->name('about');

#contact
Route::get('contact', function(){
    return Inertia::render('Contact/Index');
})->name('contact');

#blog
Route::group(['prefix' => 'blogs'], function(){
    Route::get('/', function(){
        return Inertia::render('Blog/Index');
    })->name('blog');
    Route::get('fetch', [BlogController::class, 'fetchBlog'])->name('blog.fetch');
    Route::get('{id}/read', [BlogController::class, 'viewBlog'])->name('blog.read');
});

#products
Route::group(['prefix' => 'products'], function(){
    Route::get('{id}/view', [ProductController::class, 'viewProduct'])->name('product.view');
    Route::get('fetch', [ProductController::class, 'getProducts'])->name('products.fetch');
    Route::get('categories/{id}/fetch', [ProductController::class, 'getProductsByCategory'])->name('products.by-category');
    Route::get('{type}/fetch', [ProductController::class, 'getProductsByType'])->name('products.by-type');
});

#subcategories
Route::group(['prefix' => 'product-subcategories'], function(){
    Route::get('fetch', [ProductSubcategoryController::class, 'fetchSubcategories'])->name('fetch.subcategories');;
});

#online-store
Route::group(['prefix' => 'store'], function(){
    Route::get('/', function(){
        return Inertia::render('OnlineStore/Index');
    })->name('store');
});

#cart-preview
Route::group(['prefix' => 'cart-preview'], function(){
    Route::get('/', function(){
        return Inertia::render('Cart/Index');
    })->name('cart-preview');
});

#checkout
Route::group(['prefix' => 'checkout'], function(){
    Route::get('/', function(){
        return Inertia::render('Checkout/Index');
    })->name('checkout');
    Route::get('{order_id}/complete', function(){
        return Inertia::render('Checkout/Message');
    })->name('checkout.message');
});

#order
Route::group(['prefix' => 'api'], function(){
    Route::post('store/make-order', [OrderController::class, 'store'])->name('orders.store');
});


#privacy
Route::group(['prefix' => 'privacy'], function(){
    Route::get('/', function(){
        return Inertia::render('Privacy/Index');
    })->name('privacy');
});

#delivery-policy
Route::group(['prefix' => 'delivery-policy'], function(){
    Route::get('/', function(){
        return Inertia::render('Delivery/Index');
    })->name('delivery-policy');
});

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');
require __DIR__.'/auth.php';
