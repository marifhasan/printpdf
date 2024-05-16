PrintPDF is a PHP, Laravel package for [mpdf/mpdf](https://packagist.org/packages/mpdf/mpdf) library

Installation
============

Official installation method is via composer and its packagist package [marifhasan/printpdf](https://packagist.org/packages/marifhasan/printpdf).

```
composer require marifhasan/printpdf --dev
```

Usage
=====

Add OrderPrint pdf for your application. by below command

```
php artisan make:print OrderPrint
```

It will create `app/Prints/OrderPrint.php` File inside app directory, add `resources/views/prints` directory and `order-print.blade.php`

Modify `app/Prints/OrderPrint.php` and `resources/views/prints/order-print.blade.php` for desired pdf.

For modifying the pdf property change the 'options' method in side of `app/Prints/OrderPrint.php` file.

Usage
=====
Declare route in your `routes/web.php` file.

```php
use 

Route::get('/{order:id}/print', [OrderController::class, 'print'])->name('print'); // order.print
```

Your `OrderController.php` add this method
```php
use App\Models\Order;
use App\Prints\OrderPrint;
use Marifhasan\PrintPDF\PrintPDF;

/**
* Display the order print form.
*/
public function print(Order $order)
{
	return PrintPDF::make(new OrderPrint($order))
		->inline();
}
```

available methods for mPDF property

```
inline() for 'I' //mPDF property
download() for 'D' //mPDF property
file() for 'F' //mPDF property
string() for 'S' //mPDF property
```
