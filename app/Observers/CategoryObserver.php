<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        //
    }
    /**
     * Handle the Category "updated" event.
     */

    private function updateKeys(Collection $fromTo, Collection $products)
    {
        $products->each(function ($product) use ($fromTo) {
            $values = json_encode($product->values, JSON_UNESCAPED_UNICODE);
            foreach ($fromTo as $key => $value) {
                $values = str_replace($key, $value, $values);
            }
            $product->values = json_decode($values, true);
            $product->save();
        });
    }

    private function addKeys(Collection $addedKeys, Collection $products)
    {
        $values = $addedKeys->mapWithKeys(function ($value, $key) {
            $slug = Str::slug($key);
            return [
                $slug => [
                    "name" => $key,
                    "code" => $slug,
                    "value" => ""
                ]
            ];
        })->toArray();
        $products->each(function ($product) use ($values) {
            $product->values = array_merge($product->values ?? [], $values);
            $product->save();
        });
    }

    private function slugArray(Collection $array)
    {
        return $array->mapWithKeys(function ($key) {
            return [$key => Str::slug($key)];
        });
    }

    public function updated(Category $category): void
    {
        if (is_null($category->parent_id)) {
            $show_keys = $category->show_keys;

            // Обновляем ключи у всех дочерних категорий
            $children = Category::where('parent_id', $category->id)->get();

            foreach ($children as $child) {
                $child->update(['show_keys' => $show_keys]);
            }
        }

        $products = $category->products()->get();
        $originalKeys = collect($category->getOriginal('show_keys')); // Преобразуем в коллекцию для удобства
        $keys = collect($category->show_keys); // Текущее состояние также в коллекцию
        $changes = $keys->diffKeys($originalKeys);
        $updated = [];
        $added = [];
        if ($originalKeys->count() === $keys->count()) {
            $oldKeys = $originalKeys->diffKeys($keys)->keys();
            $newKeys = $keys->diffKeys($originalKeys)->keys();

            $updated = $oldKeys->combine($newKeys);
            if (!$updated->isEmpty()) {
                $this->updateKeys($updated, $products);
            }
        } else if ($originalKeys->count() < $keys->count()) {
            $added = $keys->diffKeys($originalKeys);
            // dd($changes, $added);

            if (!$added->isEmpty()) {
                $this->addKeys($added, $products);
            }
        }

        // dd($updated, $added);
        // $values = $changes->mapWithKeys(function ($value, $key) {
        //     $slug = Str::slug($key);
        //     return [
        //         $slug => [
        //             "name" => $key,
        //             "code" => $slug,
        //             "value" => ""
        //         ]
        //     ];
        // })->toArray();
        // $products = $category->products()->get();
        // $products->each(function ($product) use ($values) {
        //     $product->values = array_merge($product->values ?? [], $values);
        //     $product->save();
        // });
    }
    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        //
    }
    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }
    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        //
    }
}
