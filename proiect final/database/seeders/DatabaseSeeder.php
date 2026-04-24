<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@kinder.md',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'city' => 'Chișinău',
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'user@kinder.md',
            'password' => Hash::make('password'),
            'phone' => '+373 69 123 456',
            'address' => 'Strada Stefan cel Mare 100',
            'city' => 'Chișinău',
            'postal_code' => 'MD-2001',
        ]);

        // Create categories
        $categories = [
            ['name' => 'Action Figures', 'slug' => 'action-figures', 'description' => 'Heroes and villains for epic adventures', 'sort_order' => 1],
            ['name' => 'Board Games', 'slug' => 'board-games', 'description' => 'Fun for the whole family', 'sort_order' => 2],
            ['name' => 'Building Blocks', 'slug' => 'building-blocks', 'description' => 'Build, create, and imagine', 'sort_order' => 3],
            ['name' => 'Dolls & Plush', 'slug' => 'dolls-plush', 'description' => 'Soft friends and companions', 'sort_order' => 4],
            ['name' => 'Educational Toys', 'slug' => 'educational-toys', 'description' => 'Learn while playing', 'sort_order' => 5],
            ['name' => 'Outdoor Toys', 'slug' => 'outdoor-toys', 'description' => 'Fun in the sun', 'sort_order' => 6],
            ['name' => 'Puzzles', 'slug' => 'puzzles', 'description' => 'Challenge your mind', 'sort_order' => 7],
            ['name' => 'Remote Control', 'slug' => 'remote-control', 'description' => 'Drive, fly, and race', 'sort_order' => 8],
            ['name' => 'Arts & Crafts', 'slug' => 'arts-crafts', 'description' => 'Express your creativity', 'sort_order' => 9],
            ['name' => 'Baby Toys', 'slug' => 'baby-toys', 'description' => 'Safe toys for little ones', 'sort_order' => 10],
        ];

        foreach ($categories as $cat) {
            // Attach an image if a matching file is available in public storage.
            $candidate = 'categories/' . $cat['slug'] . '.jpg';
            if (Storage::disk('public')->exists($candidate)) {
                $cat['image'] = $candidate;
            }
            Category::create($cat);
        }

        // Create subcategories
        $actionFigures = Category::where('slug', 'action-figures')->first();
        Category::create(['name' => 'Superheroes', 'slug' => 'superheroes', 'parent_id' => $actionFigures->id, 'sort_order' => 1]);
        Category::create(['name' => 'Dinosaurs', 'slug' => 'dinosaurs', 'parent_id' => $actionFigures->id, 'sort_order' => 2]);

        $buildingBlocks = Category::where('slug', 'building-blocks')->first();
        Category::create(['name' => 'LEGO Sets', 'slug' => 'lego-sets', 'parent_id' => $buildingBlocks->id, 'sort_order' => 1]);
        Category::create(['name' => 'Magnetic Blocks', 'slug' => 'magnetic-blocks', 'parent_id' => $buildingBlocks->id, 'sort_order' => 2]);

        $babyToys = Category::where('slug', 'baby-toys')->first();
        Category::create(['name' => 'Rattles', 'slug' => 'rattles', 'parent_id' => $babyToys->id, 'sort_order' => 1]);
        Category::create(['name' => 'Teethers', 'slug' => 'teethers', 'parent_id' => $babyToys->id, 'sort_order' => 2]);

        // Create products - realistic toy store data
        $products = [
            // Action Figures
            ['name' => 'Spider-Man Action Figure 30cm', 'category' => 'action-figures', 'price' => 299.00, 'brand' => 'Hasbro', 'age_min' => 4, 'age_max' => 12, 'stock' => 25, 'badge' => 'hot', 'is_featured' => true, 'short_description' => 'Highly detailed Spider-Man figure with movable joints'],
            ['name' => 'T-Rex Dinosaur Walking Toy', 'category' => 'dinosaurs', 'price' => 449.00, 'brand' => 'Mattel', 'age_min' => 3, 'age_max' => 10, 'stock' => 15, 'badge' => 'new', 'short_description' => 'Walking T-Rex with realistic sounds and lights'],
            ['name' => 'Batman Dark Knight Figure', 'category' => 'superheroes', 'price' => 349.00, 'brand' => 'Hasbro', 'age_min' => 4, 'age_max' => 12, 'stock' => 18, 'short_description' => 'Premium Batman figure with cape and accessories'],
            ['name' => 'Transformers Optimus Prime', 'category' => 'action-figures', 'price' => 599.00, 'brand' => 'Hasbro', 'age_min' => 6, 'age_max' => 14, 'stock' => 10, 'is_featured' => true, 'badge' => 'hot', 'short_description' => 'Converts from robot to truck in 15 steps'],

            // Board Games
            ['name' => 'Monopoly Classic Edition', 'category' => 'board-games', 'price' => 399.00, 'brand' => 'Hasbro', 'age_min' => 8, 'age_max' => 99, 'stock' => 30, 'is_featured' => true, 'short_description' => 'The classic property trading board game'],
            ['name' => 'UNO Card Game', 'category' => 'board-games', 'price' => 89.00, 'brand' => 'Mattel', 'age_min' => 7, 'age_max' => 99, 'stock' => 50, 'badge' => 'hot', 'short_description' => 'Classic card game fun for everyone'],
            ['name' => 'Scrabble Junior', 'category' => 'board-games', 'price' => 249.00, 'brand' => 'Mattel', 'age_min' => 5, 'age_max' => 10, 'stock' => 20, 'short_description' => 'Word-building game for young minds'],

            // Building Blocks
            ['name' => 'LEGO City Fire Station', 'category' => 'lego-sets', 'price' => 899.00, 'brand' => 'LEGO', 'age_min' => 6, 'age_max' => 12, 'stock' => 12, 'is_featured' => true, 'badge' => 'hot', 'short_description' => '509 pieces with 5 minifigures and fire truck'],
            ['name' => 'LEGO Friends Heartlake Cafe', 'category' => 'lego-sets', 'price' => 549.00, 'brand' => 'LEGO', 'age_min' => 6, 'age_max' => 12, 'stock' => 8, 'badge' => 'new', 'short_description' => 'Build and play in the cutest cafe in town'],
            ['name' => 'Magnetic Building Tiles 100pc', 'category' => 'magnetic-blocks', 'price' => 699.00, 'brand' => 'Magformers', 'age_min' => 3, 'age_max' => 10, 'stock' => 20, 'is_featured' => true, 'short_description' => 'Colorful magnetic tiles for endless creations'],
            ['name' => 'LEGO Technic Race Car', 'category' => 'lego-sets', 'price' => 749.00, 'brand' => 'LEGO', 'age_min' => 8, 'age_max' => 16, 'stock' => 6, 'short_description' => 'Advanced building set with working mechanics'],

            // Dolls & Plush
            ['name' => 'Teddy Bear Premium 45cm', 'category' => 'dolls-plush', 'price' => 349.00, 'brand' => 'Steiff', 'age_min' => 0, 'age_max' => 99, 'stock' => 35, 'is_featured' => true, 'badge' => 'hot', 'short_description' => 'Ultra-soft premium teddy bear, perfect gift'],
            ['name' => 'Baby Doll with Accessories', 'category' => 'dolls-plush', 'price' => 499.00, 'brand' => 'Zapf', 'age_min' => 3, 'age_max' => 10, 'stock' => 15, 'badge' => 'new', 'short_description' => 'Interactive baby doll with feeding set'],
            ['name' => 'Unicorn Plush Rainbow 35cm', 'category' => 'dolls-plush', 'price' => 199.00, 'brand' => 'TY', 'age_min' => 0, 'age_max' => 99, 'stock' => 40, 'short_description' => 'Sparkly rainbow unicorn stuffed animal'],
            ['name' => 'Barbie Dreamhouse Set', 'category' => 'dolls-plush', 'price' => 1299.00, 'brand' => 'Mattel', 'age_min' => 3, 'age_max' => 10, 'stock' => 5, 'is_featured' => true, 'short_description' => '3-story dreamhouse with furniture and accessories'],

            // Educational Toys
            ['name' => 'Kids Science Lab Kit', 'category' => 'educational-toys', 'price' => 449.00, 'brand' => 'National Geographic', 'age_min' => 8, 'age_max' => 14, 'stock' => 22, 'badge' => 'new', 'short_description' => '30+ science experiments for curious minds'],
            ['name' => 'Wooden Alphabet Puzzle', 'category' => 'educational-toys', 'price' => 149.00, 'brand' => 'Melissa & Doug', 'age_min' => 2, 'age_max' => 5, 'stock' => 30, 'short_description' => 'Colorful wooden letters for early learning'],
            ['name' => 'Interactive Globe Explorer', 'category' => 'educational-toys', 'price' => 599.00, 'brand' => 'LeapFrog', 'age_min' => 5, 'age_max' => 12, 'stock' => 10, 'is_featured' => true, 'short_description' => 'Touch and learn about countries and cultures'],

            // Outdoor Toys
            ['name' => 'Kids Bicycle 16 inch', 'category' => 'outdoor-toys', 'price' => 1499.00, 'brand' => 'Decathlon', 'age_min' => 4, 'age_max' => 8, 'stock' => 8, 'short_description' => 'Sturdy bicycle with training wheels'],
            ['name' => 'Bubble Machine Deluxe', 'category' => 'outdoor-toys', 'price' => 199.00, 'brand' => 'Gazillion', 'age_min' => 3, 'age_max' => 10, 'stock' => 25, 'badge' => 'hot', 'short_description' => 'Thousands of bubbles per minute'],
            ['name' => 'Water Blaster Super Soaker', 'category' => 'outdoor-toys', 'price' => 249.00, 'brand' => 'Nerf', 'age_min' => 6, 'age_max' => 14, 'stock' => 20, 'short_description' => 'Ultimate water battle toy'],

            // Puzzles
            ['name' => 'World Map Puzzle 500pc', 'category' => 'puzzles', 'price' => 179.00, 'brand' => 'Ravensburger', 'age_min' => 8, 'age_max' => 99, 'stock' => 18, 'short_description' => 'Beautiful illustrated world map puzzle'],
            ['name' => 'Disney Princess Puzzle 100pc', 'category' => 'puzzles', 'price' => 99.00, 'brand' => 'Ravensburger', 'age_min' => 5, 'age_max' => 10, 'stock' => 25, 'badge' => 'new', 'short_description' => 'Magical princess-themed jigsaw puzzle'],

            // Remote Control
            ['name' => 'RC Racing Car 1:16', 'category' => 'remote-control', 'price' => 549.00, 'brand' => 'Traxxas', 'age_min' => 6, 'age_max' => 14, 'stock' => 12, 'is_featured' => true, 'badge' => 'hot', 'short_description' => 'High-speed remote control racing car'],
            ['name' => 'RC Drone with Camera', 'category' => 'remote-control', 'price' => 799.00, 'brand' => 'DJI', 'age_min' => 10, 'age_max' => 99, 'stock' => 7, 'badge' => 'new', 'short_description' => 'Entry-level drone with HD camera'],
            ['name' => 'RC Helicopter Indoor', 'category' => 'remote-control', 'price' => 299.00, 'brand' => 'Syma', 'age_min' => 8, 'age_max' => 14, 'stock' => 15, 'short_description' => 'Easy to fly indoor helicopter'],

            // Arts & Crafts
            ['name' => 'Kids Art Set 150 Pieces', 'category' => 'arts-crafts', 'price' => 349.00, 'brand' => 'Crayola', 'age_min' => 4, 'age_max' => 12, 'stock' => 28, 'is_featured' => true, 'short_description' => 'Complete art set with crayons, markers, and paints'],
            ['name' => 'Play-Doh Mega Set', 'category' => 'arts-crafts', 'price' => 249.00, 'brand' => 'Hasbro', 'age_min' => 2, 'age_max' => 8, 'stock' => 30, 'short_description' => '20 colors of modeling compound with tools'],
            ['name' => 'Jewelry Making Kit', 'category' => 'arts-crafts', 'price' => 199.00, 'brand' => 'Klutz', 'age_min' => 6, 'age_max' => 12, 'stock' => 20, 'badge' => 'new', 'short_description' => 'Create beautiful bracelets and necklaces'],

            // Baby Toys
            ['name' => 'Baby Activity Cube', 'category' => 'baby-toys', 'price' => 399.00, 'brand' => 'VTech', 'age_min' => 0, 'age_max' => 3, 'stock' => 15, 'is_featured' => true, 'badge' => 'hot', 'short_description' => '5-sided activity cube with lights and sounds'],
            ['name' => 'Stacking Rings Classic', 'category' => 'baby-toys', 'price' => 79.00, 'brand' => 'Fisher-Price', 'age_min' => 0, 'age_max' => 3, 'stock' => 40, 'short_description' => 'Classic ring stacker for motor skill development'],
            ['name' => 'Musical Baby Walker', 'category' => 'baby-toys', 'price' => 549.00, 'brand' => 'VTech', 'age_min' => 0, 'age_max' => 2, 'stock' => 10, 'short_description' => 'Interactive walker with music and activities'],
            ['name' => 'Soft Rattle Set 4pc', 'category' => 'rattles', 'price' => 129.00, 'brand' => 'Fisher-Price', 'age_min' => 0, 'age_max' => 1, 'stock' => 30, 'badge' => 'new', 'short_description' => 'Colorful rattles safe for newborns'],
        ];

        foreach ($products as $productData) {
            $categorySlug = $productData['category'];
            unset($productData['category']);

            $category = Category::where('slug', $categorySlug)->first();
            $productData['category_id'] = $category->id;
            $productData['slug'] = \Illuminate\Support\Str::slug($productData['name']);
            $productData['description'] = $productData['short_description'] . '. This is a high-quality toy available at Kinder toy store in Moldova. Perfect for children and makes a great gift for birthdays and holidays.';
            $productData['sku'] = 'KND-' . strtoupper(\Illuminate\Support\Str::random(8));
            $productData['badge'] = $productData['badge'] ?? 'none';
            $productData['is_featured'] = $productData['is_featured'] ?? false;

            $product = Product::create($productData);

            // Attach the best image we can find. Preference order:
            //   1. category image (if this product's category has one)
            //   2. parent category image (for subcategory products)
            //   3. shared placeholder
            $imagePath = $category->image;
            if (! $imagePath && $category->parent_id) {
                $imagePath = Category::find($category->parent_id)?->image;
            }
            if (! $imagePath || ! Storage::disk('public')->exists($imagePath)) {
                $imagePath = 'products/placeholder.jpg';
            }

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $imagePath,
                'alt' => $product->name,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }

        // Create some discounts
        $discountProducts = Product::whereIn('slug', [
            'spider-man-action-figure-30cm',
            'monopoly-classic-edition',
            'teddy-bear-premium-45cm',
            'lego-city-fire-station',
            'rc-racing-car-116',
            'kids-art-set-150-pieces',
            'baby-activity-cube',
            'bubble-machine-deluxe',
        ])->get();

        foreach ($discountProducts as $product) {
            $percent = collect([10, 15, 20, 25, 30])->random();
            Discount::create([
                'product_id' => $product->id,
                'discount_percent' => $percent,
                'original_price' => $product->price,
                'discounted_price' => round($product->price * (1 - $percent / 100), 2),
                'starts_at' => Carbon::now()->subDays(2),
                'ends_at' => Carbon::now()->addDays(14),
                'is_active' => true,
            ]);
        }
    }
}
