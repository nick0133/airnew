<?php

namespace App\Observers;

use App\Models\Category;
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
        $originalKeys = collect($category->getOriginal('show_keys')); // Преобразуем в коллекцию для удобства
        $keys = collect($category->show_keys); // Текущее состояние также в коллекцию
        $changes = $keys->diffKeys($originalKeys);
        $values = $changes->mapWithKeys(function ($value, $key) {
            $slug = Str::slug($key);
            return [
                $slug => [
                    "name" => $key,
                    "code" => $slug,
                    "value" => ""
                ]
            ];
        })->toArray();
        $products = $category->products()->get();
        $products->each(function ($product) use ($values) {
            $existingValues = $product->values ?? []; // Текущие характеристики продукта

            // Обновляем только отсутствующие ключи или изменяем slug
            foreach ($values as $slug => $newValue) {
                if (isset($existingValues[$slug])) {
                    // Если ключ уже существует, обновляем только name и code
                    $existingValues[$slug]['name'] = $newValue['name'];
                    $existingValues[$slug]['code'] = $newValue['code'];
                } else {
                    // Добавляем новые ключи
                    $existingValues[$slug] = $newValue;
                }
            }

            $product->values = $existingValues;
            $product->save();
        });
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
