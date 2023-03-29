<?php

namespace App\Observers;

use App\Models\Admin\Product;
use Illuminate\Support\Carbon;


class AdminProductObserver
{
    public function creating(Product $product)
    {
      $this->setAlias($product);
    }


    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Admin\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }

    /** Set Alias for new Product */
    public function setAlias(Product $product)
    {
        if(empty($product->alias))
        {
            $product->alias = \Str::slug($product->title);
            $check = Product::where('alias', '=', $product->alias)->exists();

            if($check)
            {
                $product->alias = \Str::random($product->title) . time();
            }
        }
    }



    /**
     * Set Alias for new Product
     */
    public function saving(Product $product)
    {
        $this->setPublishedAt($product);
    }



    /**
     * Set Published Product
     */
    public function setPublishedAt(Product $product)
    {
        $needSetPublished = empty($product->updated_at) || !empty($product->updated_at);

        if ($needSetPublished){
            $product->updated_at = Carbon::now();
        }
    }

}
