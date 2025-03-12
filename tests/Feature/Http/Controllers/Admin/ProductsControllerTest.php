<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Product;
use App\Services\Contracts\FileServiceContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\Feature\Traits\SetupTrait;
use Tests\TestCase;

class ProductsControllerTest extends TestCase
{
    use SetupTrait;
    
    public function test_it_create_product_with_valid_data(): void
    {
        Storage::fake('public');
        $fileName = 'test.png';
        $file = UploadedFile::fake()->image($fileName);
        $title = 'Test product';
        $slug = Str::slug($title);
        
        $productData = [
            'title' => $title,
            'slug' => $slug,
            'SKU' => '123456',
            'price' => 100,
            'quantity' => 10,
            'description' => 'Test description',
            'thumbnail' => $file,
        ];
        
        $filePath = "{$slug}/{$fileName}";
        $this->mock(
            FileServiceContract::class,
            function ($mock) use ($filePath) {
                $mock->shouldReceive('upload')
                    ->andReturn($filePath);
            }
        );
        
        $response = $this->actingAs($this->user())
            ->post(route('admin.products.store'), $productData);
        
        $this->assertDatabaseHas('products', [
            'slug'  => $productData['slug'],
            'SKU'  => $productData['SKU'],
        ]);
    }
}
