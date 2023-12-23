Installation
============

Official installation method is via composer and its packagist package [marifhasan/printpdf](https://packagist.org/packages/marifhasan/printpdf).

it usages the official package of [mpdf/mpdf](https://packagist.org/packages/mpdf/mpdf)

```
$ composer require marifhasan/printpdf --save-dev
```

Usage
=====

Add DemoPrint pdf for your application. by below command

```
$ php artisan make:print DemoPrint
```

It will create `app/Prints/DemoPrint.php` File inside app directory, add `resources/views/prints` directory and `demo-print.blade.php`

Modify `app/Prints/DemoPrint.php` and `resources/views/prints/demo-print.blade.php` for desired pdf.

For modifying the pdf property change the 'options' method in side of `app/Prints/DemoPrint.php` file.
