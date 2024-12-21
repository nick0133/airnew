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

        $originalKeys = collect($category->getOriginal('show_keys')); // Оригинальная коллекция
        $keys = collect($category->show_keys); // Текущая коллекция

        // Найти добавленные и удалённые ключи
        $addedKeys = $keys->diffKeys($originalKeys);
        $removedKeys = $originalKeys->diffKeys($keys);

        // Создать структуру для новых `addedKeys`
        $addedValues = $addedKeys->mapWithKeys(function ($value, $key) {
            $slug = Str::slug($key);
            return [
                $slug => [
                    "name" => $key,
                    "code" => $slug,
                    "value" => ""
                ]
            ];
        })->toArray();

        // Удалить значения для `removedKeys`
        $removedSlugs = $removedKeys->mapWithKeys(function ($value, $key) {
            return [Str::slug($key) => null];
        })->keys()->toArray();

        // Обновляем продукты
        $products = $category->products()->get();
        $products->each(function ($product) use ($addedValues, $removedSlugs) {
            // Добавляем новые значения
            $productValues = $product->values ?? [];
            $productValues = array_merge($productValues, $addedValues);

            // Удаляем значения по slug'ам
            foreach ($removedSlugs as $slug) {
                unset($productValues[$slug]);
            }

            $product->values = $productValues;
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
