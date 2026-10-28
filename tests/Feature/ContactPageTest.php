<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_shows_venue_office_without_a_form(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('ติดต่อเรา')
            ->assertSee('Nano Circuit Go-Kart Track บางนา–ตราด กม.15')
            ->assertSee('Bangna-trad, Bang Chalong, Bang Phli, Samut Prakan 10540')
            ->assertSee('+ (66) 612324516')
            ->assertSee('วันจันทร์ - วันอาทิตย์ : 13.00 น. - 22.00 น.')
            ->assertSee('OUR LOCATIONS')
            ->assertDontSee('Your Name')
            ->assertDontSee('Send message');
    }
}
