<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run()
    {
        // Insert 20 fake test clients
        for ($i = 1; $i <= 20; $i++) {

            $client = Client::create([
                'name'        => "Client $i",
                'surname'     => "Surname $i",
                'company'     => "Company $i",
                'role'        => "Manager",
                'phone'       => "01234-5678$i",
                'email'       => "client$i@example.com",
                'street'      => "Street $i",
                'postal_code' => "100$i",
                'city'        => "Dhaka",
                'country'     => "Bangladesh",
                'assigned_to' => 1, // admin user or any user id
                'custom_fields' => [
                    'budget' => rand(1000, 5000),
                    'priority' => ['Low','Medium','High'][rand(0,2)]
                ]
            ]);

            // Generate client number based on ID
            $client->client_number = "CL-" . str_pad($client->id, 5, '0', STR_PAD_LEFT);
            $client->save();
        }
    }
}
