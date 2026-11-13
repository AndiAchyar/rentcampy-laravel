<?php

namespace App\Http\Controllers;

use RuntimeException;

/** Kegagalan checkout yang pesannya aman ditampilkan ke user (stok kurang, produk hilang). */
class CheckoutException extends RuntimeException
{
}
