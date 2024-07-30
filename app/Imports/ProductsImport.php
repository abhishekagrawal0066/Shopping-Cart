<?php
namespace App\Imports;

use App\Models\Product;
use App\Models\ProductImage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Storage;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $product = Product::create([
            'category_id' => $row['category_id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => $row['quantity'],
            'description' => $row['description'],
        ]);

        // Process and store images
        $this->processImages($product, $row['images']);

        return $product;
    }

    private function processImages($product, $images)
    {
        $imagesArray = explode(',', $images);

        foreach ($imagesArray as $image) {
            // Assuming images are stored in the public storage (public/images directory)
            $storedImage = Storage::disk('public')->put('images', $image);
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $storedImage,
            ]);
        }
    }
}
