<?php

namespace Tests\Feature;

use App\Business;
use App\BusinessActivity;
use App\BusinessLocation;
use App\System;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Password;
use Tests\Feature\Concerns\RegistrationPayload;
use Tests\TestCase;

/**
 * Registration form revision (Yaigo feedback): language handling, validation,
 * where the data is stored and the localized emails.
 * Runs against the configured database inside a transaction that is rolled back.
 */
class BusinessRegistrationTest extends TestCase
{
    use DatabaseTransactions, RegistrationPayload;

    protected function setUp(): void
    {
        parent::setUp();

        config(['constants.allow_registration' => true, 'constants.enable_recaptcha' => false, 'mail.default' => 'array']);

        foreach (['enable_welcome_email' => 1, 'welcome_email_subject' => 'Welcome {owner_name}', 'welcome_email_body' => '<p>Welcome to {business_name}</p>',
            'welcome_email_subject_es' => '', 'welcome_email_body_es' => '', ] as $key => $value) {
            System::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    //Superadmin welcome notification (the Yaigo welcome email with the document is covered by BusinessWelcomeMailTest)
    protected function sentMessages()
    {
        return app('mailer')->getSymfonyTransport()->messages()->map->getOriginalMessage();
    }

    public function test_guest_pages_default_to_spanish_and_keep_the_chosen_language()
    {
        $this->get('/login')->assertOk()->assertSee('Usuario o correo electrónico');

        $this->get('/business/register?lang=en')->assertOk()
            ->assertSee('Who introduced you to Yaigo?')
            ->assertSee('js/lang/en.js', false)
            ->assertDontSee('name="time_zone"', false)
            ->assertDontSee('name="fy_start_month"', false)
            ->assertDontSee('name="accounting_method"', false)
            ->assertDontSee('Autónomo (Autónomo)');

        //Reload without ?lang keeps English
        $this->get('/business/register')->assertOk()->assertSee('Autonomous community');

        $response = $this->get('/business/register?lang=es')->assertOk()->assertSee('js/lang/es.js', false);
        //Form labels are HTML-entity encoded
        $html = html_entity_decode($response->getContent());
        foreach (['¿Quién te ha presentado Yaigo?', 'Comunidad autónoma', 'España - Euro (EUR)', 'Tipo de documento', 'Igual que el número de contacto'] as $text) {
            $this->assertStringContainsString($text, $html);
        }
    }

    public function test_invalid_documents_postal_code_and_phone_are_rejected_in_spanish()
    {
        $this->from('/business/register?lang=es')->post('/business/register', $this->payload([
            'business_type' => 'company',
            'legal_name' => 'Test SL',
            'tax_label_1' => 'CIF',
            'tax_number_1' => 'X9756602W',
            'legal_rep_name' => 'Ana García',
            'legal_rep_position' => 'Administradora única',
            'tax_label_2' => 'NIE',
            'tax_number_2' => 'X97566',
            'zip_code' => '28001',
            'mobile' => '61234',
            'whatsapp_same_as_mobile' => 0,
        ]))->assertRedirect('/business/register?lang=es')
            ->assertSessionHasErrors([
                'tax_number_1' => 'El número de CIF no es válido (p. ej. B12345674).',
                'tax_number_2' => 'El número de NIE no es válido (p. ej. X1234567L).',
                'zip_code' => 'El código postal no corresponde a la provincia seleccionada.',
                'mobile' => 'Introduce un teléfono válido (9 dígitos para España).',
            ]);

        $this->assertSame(0, Business::where('name', 'like', 'Test Shop%')->count());
    }

    public function test_self_employed_registration_in_spanish_stores_data_and_sends_spanish_email()
    {
        $data = $this->payload([
            'community_code' => '05', 'province_code' => '35', 'municipality_code' => '35016', 'zip_code' => '35001',
        ]);
        $this->post('/business/register', $data)->assertRedirect()->assertSessionHasNoErrors();

        $user = User::where('username', $data['username'])->firstOrFail();
        $business = Business::findOrFail($user->business_id);
        $location = BusinessLocation::where('business_id', $business->id)->firstOrFail();

        $this->assertSame('es', $user->language);
        $this->assertSame('Atlantic/Canary', $business->time_zone);
        $this->assertEquals(1, $business->fy_start_month);
        $this->assertSame('fifo', $business->accounting_method);
        $this->assertSame('restaurant', $business->business_sector);
        $this->assertSame('NIE', $business->tax_label_1);
        $this->assertSame('X1234567L', $business->tax_number_1);
        $this->assertNull($business->legal_rep_name);

        $this->assertSame('Spain', $location->country);
        $this->assertSame(['05', '35', '35016'], [$location->community_code, $location->province_code, $location->municipality_code]);
        $this->assertSame('Las Palmas', $location->state);
        $this->assertSame('Las Palmas de Gran Canaria', $location->city);
        $this->assertSame('http://unimerkat.es', $location->website);
        $this->assertSame('+34 612345678', $location->mobile);
        $this->assertSame('+34 612345678', $location->whatsapp_number);
        $this->assertSame('Ana García', $location->contact_person);
        $this->assertSame('2º 1ª', $location->address_line_2);

        $welcome = $this->sentMessages()->first(fn ($m) => $m->getTo()[0]->getAddress() == $data['email'] && $m->getSubject() != __('mail.yaigo_welcome_subject', [], 'es'));
        $this->assertNotNull($welcome, 'welcome email sent');
        $this->assertSame('Bienvenido a '.config('app.name'), $welcome->getSubject());
        $this->assertStringContainsString('Gracias por registrar', $welcome->getHtmlBody());
        $this->assertStringContainsString('Gracias por registrar', $welcome->getTextBody());
    }

    public function test_company_registration_in_english_stores_representative_and_new_activity()
    {
        $data = $this->payload([
            'lang' => 'en',
            'language' => 'en',
            'business_type' => 'company',
            'legal_name' => 'Test SL',
            'tax_label_1' => 'CIF',
            'tax_number_1' => 'B12345674',
            'legal_rep_name' => 'Ana García',
            'legal_rep_position' => 'Sole director',
            'tax_label_2' => 'DNI',
            'tax_number_2' => '12345678Z',
            'business_sector' => 'other',
            'business_activity_other' => 'bookshop and stationery '.uniqid(),
        ]);
        $this->post('/business/register', $data)->assertRedirect()->assertSessionHasNoErrors();

        $business = Business::findOrFail(User::where('username', $data['username'])->value('business_id'));
        $this->assertSame('Europe/Madrid', $business->time_zone);
        $this->assertSame('other', $business->business_sector);
        $this->assertStringStartsWith('Bookshop and stationery', $business->business_activity);
        $this->assertTrue(BusinessActivity::where('name', $business->business_activity)->exists());
        $this->assertSame(['Ana García', 'Sole director', 'DNI', '12345678Z'],
            [$business->legal_rep_name, $business->legal_rep_position, $business->tax_label_2, $business->tax_number_2]);

        //A typed name that matches a known activity maps to that sector
        $data2 = $this->payload(['business_sector' => 'other', 'business_activity_other' => 'Restaurante']);
        $this->post('/business/register', $data2)->assertSessionHasNoErrors();
        $this->assertSame('restaurant', Business::findOrFail(User::where('username', $data2['username'])->value('business_id'))->business_sector);

        $welcome = $this->sentMessages()->first(fn ($m) => $m->getTo()[0]->getAddress() == $data['email'] && $m->getSubject() != __('mail.yaigo_welcome_subject', [], 'es'));
        $this->assertStringStartsWith('Welcome ', $welcome->getSubject());
        $this->assertStringContainsString('Welcome to '.$business->name, $welcome->getTextBody());
    }

    public function test_password_reset_email_uses_the_users_language()
    {
        foreach (['es' => 'Restablece tu contraseña', 'en' => 'Reset your password', 'fr' => 'Reset your password'] as $language => $subject) {
            $user = User::create_user(['first_name' => 'T', 'last_name' => 'U', 'username' => uniqid('u'), 'email' => uniqid('r').'@example.com',
                'password' => 'secret123', 'language' => $language, ]);

            Password::broker()->sendResetLink(['email' => $user->email]);

            $message = $this->sentMessages()->last();
            $this->assertSame(config('app.name').' - '.$subject, $message->getSubject(), $language);
            $this->assertSame(config('mail.from.name'), $message->getFrom()[0]->getName());
            $this->assertNotEmpty($message->getTextBody());
            $this->assertStringNotContainsString('copy and paste', $message->getHtmlBody());
            $this->assertStringNotContainsString('copia y pega', $message->getHtmlBody());
        }
    }

    public function test_login_accepts_username_or_email()
    {
        $user = User::create_user(['first_name' => 'T', 'last_name' => 'U', 'username' => uniqid('u'), 'email' => uniqid('l').'@example.com', 'password' => 'secret123']);
        $user->business_id = Business::value('id');
        $user->save();

        $this->post('/login', ['username' => $user->email, 'password' => 'secret123']);
        $this->assertAuthenticatedAs($user);
    }
}
