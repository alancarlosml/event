<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buscar dados necessários
        $owner1 = DB::table('owners')->where('name', 'Eventos Tech Brasil')->first();
        $owner2 = DB::table('owners')->where('name', 'ShowTime Produções')->first();
        $owner3 = DB::table('owners')->where('name', 'Academia de Eventos')->first();
        
        $areaTech = DB::table('areas')->where('slug', 'desenvolvimento-software')->first();
        $areaAI = DB::table('areas')->where('slug', 'inteligencia-artificial')->first();
        $areaShow = DB::table('areas')->where('slug', 'musica-popular')->first();
        $areaBusiness = DB::table('areas')->where('slug', 'empreendedorismo')->first();
        
        $placeAnhembi = DB::table('places')->where('name', 'Centro de Convenções Anhembi')->first();
        $placeAllianz = DB::table('places')->where('name', 'Allianz Parque')->first();
        $placeRiocentro = DB::table('places')->where('name', 'Riocentro')->first();
        $placeExpominas = DB::table('places')->where('name', 'Expominas')->first();

        $events = [
            [
                'name' => 'Tech Conference 2024',
                'subtitle' => 'A maior conferência de tecnologia do Brasil',
                'slug' => 'tech-conference-2024',
                'description' => 'Conferência completa sobre as últimas tendências em tecnologia, desenvolvimento de software, inteligência artificial e inovação. Palestras com os maiores especialistas do mercado.',
                'banner' => 'tech-conference-banner.jpg',
                'banner_option' => 1,
                'config_tax' => 0.07,
                'max_tickets' => '5000',
                'theme' => 'default',
                'contact' => 'contato@techconference.com.br',
                'status' => 1,
                'owner_id' => $owner1->id,
                'place_id' => $placeAnhembi->id,
                'area_id' => $areaTech->id,
                'paid' => 1,
            ],
            [
                'name' => 'Festival de Música Popular',
                'subtitle' => 'Os maiores nomes da música brasileira',
                'slug' => 'festival-musica-popular-2024',
                'description' => 'Festival com os principais artistas da música popular brasileira. Um evento imperdível para os amantes da MPB.',
                'banner' => 'festival-mpb-banner.jpg',
                'banner_option' => 1,
                'config_tax' => 0.10,
                'max_tickets' => '10000',
                'theme' => 'default',
                'contact' => 'contato@showtime.com.br',
                'status' => 1,
                'owner_id' => $owner2->id,
                'place_id' => $placeAllianz->id,
                'area_id' => $areaShow->id,
                'paid' => 1,
            ],
            [
                'name' => 'Workshop de Inteligência Artificial',
                'subtitle' => 'Aprenda IA na prática',
                'slug' => 'workshop-ia-2024',
                'description' => 'Workshop prático sobre inteligência artificial, machine learning e deep learning. Traga seu notebook e aprenda na prática.',
                'banner' => 'workshop-ia-banner.jpg',
                'banner_option' => 1,
                'config_tax' => 0.05,
                'max_tickets' => '200',
                'theme' => 'default',
                'contact' => 'contato@academiaeventos.com.br',
                'status' => 1,
                'owner_id' => $owner3->id,
                'place_id' => $placeRiocentro->id,
                'area_id' => $areaAI->id,
                'paid' => 1,
            ],
            [
                'name' => 'Conferência de Empreendedorismo',
                'subtitle' => 'Conectando ideias e negócios',
                'slug' => 'conferencia-empreendedorismo-2024',
                'description' => 'Conferência para empreendedores, startups e investidores. Networking, palestras e oportunidades de negócio.',
                'banner' => 'empreendedorismo-banner.jpg',
                'banner_option' => 1,
                'config_tax' => 0.08,
                'max_tickets' => '2000',
                'theme' => 'default',
                'contact' => 'contato@businessconnect.com.br',
                'status' => 1,
                'owner_id' => $owner1->id,
                'place_id' => $placeExpominas->id,
                'area_id' => $areaBusiness->id,
                'paid' => 1,
            ],
        ];

        foreach ($events as $eventData) {
            $eventData['hash'] = md5($eventData['name'] . $eventData['description'] . md5('7bc05eb02415fe73101eeea0180e258d45e8ba2b'));
            
            $event = Event::create($eventData);
            
            // Criar datas para os eventos
            $this->createEventDates($event->id);
        }
    }

    private function createEventDates($eventId)
    {
        $event = Event::find($eventId);
        
        // Criar datas diferentes baseadas no tipo de evento
        if (str_contains($event->name, 'Conference') || str_contains($event->name, 'Conferência')) {
            // Eventos de 2 dias
            DB::table('event_dates')->insert([
                'event_id' => $eventId,
                'date' => now()->addDays(30)->format('Y-m-d'),
                'time_begin' => '09:00:00',
                'time_end' => '18:00:00',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('event_dates')->insert([
                'event_id' => $eventId,
                'date' => now()->addDays(31)->format('Y-m-d'),
                'time_begin' => '09:00:00',
                'time_end' => '18:00:00',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif (str_contains($event->name, 'Workshop')) {
            // Workshops de 1 dia
            DB::table('event_dates')->insert([
                'event_id' => $eventId,
                'date' => now()->addDays(20)->format('Y-m-d'),
                'time_begin' => '14:00:00',
                'time_end' => '18:00:00',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // Festivais e shows - 1 dia
            DB::table('event_dates')->insert([
                'event_id' => $eventId,
                'date' => now()->addDays(45)->format('Y-m-d'),
                'time_begin' => '19:00:00',
                'time_end' => '23:00:00',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

