<?php

namespace Tests\Feature;

use App\Data\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CollectionTest extends TestCase
{
    public function testCreateCollection()
    {
        $collection = collect([1, 2, 3]);
        $this->assertEqualsCanonicalizing([1, 2, 3], $collection->all());
    }

    public function testForEach()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        foreach ($collection as $key => $value) {
            $this->assertEquals($key + 1, $value);
        }
    }

    public function testCrut()
    {
        // push untuk memasukan data
        $collection = collect([]);
        $collection->push(1, 2, 3);
        $this->assertEqualsCanonicalizing([1, 2, 3], $collection->all());

        // pop ambil data yang paling belakan
        $result = $collection->pop();
        $this->assertEquals(3, $result);
        $this->assertEqualsCanonicalizing([1, 2], $collection->all());
    }

    // Mapping untuk memgubah benuk data menjadi data lain
    public function testMap()
    {
        $collection = collect([1, 2, 3]);
        $result = $collection->map(function ($item) {
            return $item * 2;
        });

        $this->assertEqualsCanonicalizing([2, 4, 6], $result->all());
    }

    // mapinto
    public function testMapInto()
    {
        $collection = collect(["Jubi"]);
        $result = $collection->mapInto(Person::class);
        $this->assertEquals([new Person("Jubi")], $result->all());
    }

    // mapSpead data yang pisah dan di satuin lagi
    public function testMapSpread()
    {
        $collection = collect([
            ["Jubi", "Ximenes"],
            ["Assuncao", "ZuBy"]
        ]);

        $result = $collection->mapSpread(function ($firstName, $lastName) {
            $fullName = $firstName . ' ' . $lastName;
            return new Person($fullName);
        });

        $this->assertEquals([
            new Person("Jubi Ximenes"),
            new Person("Assuncao ZuBy"),
        ], $result->all());
    }

    // maptogroups
    public function testMapToGroups()
    {
        $collection = collect([
            [
                "name" => "Jubi",
                "departament" => "IT"
            ],
            [
                "name" => "Zawa",
                "departament" => "LAW"
            ],
            [
                "name" => "Max",
                "departament" => "IT"
            ],
        ]);

        $result = $collection->mapToGroups(function ($person) {
            return [
                $person["departament"] => $person["name"]
            ];
        });

        $this->assertEquals([
            "IT" => collect(["Jubi", "Max"]),
            "LAW" => collect(["Zawa"])
        ], $result->all());
    }
}
