<?php

namespace Database\Factories;

use App\Models\Food;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    protected $model = Food::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "id" => rand(),
            "code" => "2311215062311",
            "status" => "publish",
            "imported_t" => "2023-05-03 16:49:24",
            "url" => "http:\/\/world-en.openfoodfacts.org\/product\/8718215090281\/yoghurt-grieks-0-naturel-katharos",
            "creator" => "Prof. Kamryn Ryan",
            "created_t" => "1683132564.8484",
            "last_modified_t" => "1683132564.8484",
            "product_name" => "Cecil Sipes",
            "quantity" => "",
            "brands" => "",
            "categories" => "",
            "labels" => "",
            "cities" => "",
            "purchase_places" => "",
            "stores" => "",
            "ingredients_text" => "Est qui in nostrum assumenda accusantium. Sed qui animi consequatur voluptatum.",
            "traces" => "",
            "serving_size" => "",
            "serving_quantity" => "",
            "nutriscore_score" => "",
            "nutriscore_grade" => "",
            "main_category" => "",
            "image_url" => "https:\/\/static.openfoodfacts.org\/images\/products\/871\/821\/509\/0281\/front_fr.4.400.jpg",
            "created_at" => "2023-05-03T16:49:24.000000Z",
            "updated_at" => "2023-05-03T16:49:24.000000Z",
        ];
    }
}
