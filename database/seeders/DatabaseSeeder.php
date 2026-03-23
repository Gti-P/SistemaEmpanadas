<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Cliente de mostrador (por defecto)
        DB::table('clients')->insert([
            'document_type' => 'CC',
            'document_number' => '0000000000',
            'name' => 'Cliente de Mostrador',
            'address' => null,
            'city' => null,
            'phone' => null,
            'is_counter_client' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Algunos clientes de ejemplo
        DB::table('clients')->insert([
            ['document_type' => 'CC', 'document_number' => '1098765432', 'name' => 'María García López', 'address' => 'Cra 10 # 15-20', 'city' => 'Bucaramanga', 'phone' => '3001234567', 'is_counter_client' => false, 'created_at' => now(), 'updated_at' => now()],
            ['document_type' => 'CC', 'document_number' => '1090123456', 'name' => 'Carlos Rueda Pérez', 'address' => 'Cl 30 # 22-05', 'city' => 'Floridablanca', 'phone' => '3159876543', 'is_counter_client' => false, 'created_at' => now(), 'updated_at' => now()],
            ['document_type' => 'CC', 'document_number' => '63500000', 'name' => 'Ana Morales Torres', 'address' => 'Av 45 # 10-12', 'city' => 'Girón', 'phone' => '3204567890', 'is_counter_client' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Productos
        DB::table('products')->insert([
            // Empanadas
            ['name' => 'Empanada de Pollo', 'category' => 'empanada', 'description' => 'Deliciosa empanada rellena de pollo desmechado con papa y cebolla', 'price' => 2500, 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Empanada de Carne', 'category' => 'empanada', 'description' => 'Empanada con carne molida y papa criolla', 'price' => 2500, 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Empanada Hawaiana', 'category' => 'empanada', 'description' => 'Empanada con jamón, queso y piña', 'price' => 3000, 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Empanada de Queso', 'category' => 'empanada', 'description' => 'Empanada con queso campesino derretido', 'price' => 2000, 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Empanada Mixta', 'category' => 'empanada', 'description' => 'Empanada con carne y pollo', 'price' => 2800, 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Empanada Vegetal', 'category' => 'empanada', 'description' => 'Empanada con espinaca, zanahoria y queso', 'price' => 2500, 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            // Papas rellenas
            ['name' => 'Papa Rellena de Pollo', 'category' => 'papa_rellena', 'description' => 'Papa rellena con pollo desmechado y ají', 'price' => 3500, 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Papa Rellena de Carne', 'category' => 'papa_rellena', 'description' => 'Papa rellena con carne molida especiada', 'price' => 3500, 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Papa Mixta', 'category' => 'papa_rellena', 'description' => 'Papa rellena con carne y pollo', 'price' => 4000, 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Papa Rellena Especial', 'category' => 'papa_rellena', 'description' => 'Papa rellena con carne, queso y hogao', 'price' => 4500, 'active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
