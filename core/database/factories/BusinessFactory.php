<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Restoran' => ['Kral', 'Lezzet', 'Nefis', 'Osmanlı', 'Anadolu', 'Saray', 'Cadde', 'Meydan', 'Liman', 'Sahil'],
            'Kafe' => ['Kitap', 'Sanat', 'Mola', 'Keyif', 'Bahçe', 'Teras', 'Retro', 'Vintage', 'Modern', 'Lounge'],
            'Güzellik Salonu' => ['Peri', 'Venüs', 'Altın', 'Elmas', 'İnci', 'Pırıltı', 'Işıltı', 'Zarafet', 'Estetik', 'Güzellik'],
            'Otel' => ['Grand', 'Royal', 'Elite', 'Palace', 'Konfor', 'Huzur', 'Kent', 'Şehir', 'Merkez', 'Plaza']
        ];

        $selectedCategory = $this->faker->randomElement(array_keys($categories));
        $prefix = $this->faker->randomElement($categories[$selectedCategory]);
        $suffix = $selectedCategory;
        $name = "$prefix $suffix " . $this->faker->numberBetween(1, 100);

        // Fetch valid category ID or default to 1
        $categoryId = \App\Models\Category::where('name', $selectedCategory)->first()?->id ?? 1;

        return [
            'owner_id' => 1, // Will be overridden
            'category_id' => $categoryId,
            'name' => $name,
            'description' => $this->faker->realText(200),
            'address' => $this->faker->address,
            'phone' => '05' . $this->faker->numerify('#########'),
            'rating' => $this->faker->randomFloat(1, 3, 5),
            'is_active' => true,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'price_per_person' => $this->faker->numberBetween(100, 2000),
        ];
    }
}
