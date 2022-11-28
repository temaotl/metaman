<?php

namespace Database\Factories;

use App\Models\Federation;
use Illuminate\Database\Eloquent\Factories\Factory;

class FederationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Federation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = substr($this->faker->unique()->company(), 0, 32);
        $id = generateFederationID($name);

        return [
            'name' => $name,
            'description' => $this->faker->catchPhrase(),
            'tagfile' => "$id.tag",
            'cfgfile' => "$id.cfg",
            'xml_id' => $id,
            'xml_name' => "urn:mace:cesnet.cz:$id",
            'filters' => $this->faker->unique()->text(32),
            'approved' => true,
            'explanation' => $this->faker->text(255),
        ];
    }
}
