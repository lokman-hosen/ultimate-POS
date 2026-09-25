<?php

namespace Tests\Feature;

use App\Business;
use App\Mail\BusinessWelcomeMail;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\Feature\Concerns\RegistrationPayload;
use Tests\TestCase;

/**
 * Welcome email with the document to fill in, sent after a business is
 * created from the website or the Superadmin panel.
 * Runs against the configured database inside a transaction that is rolled back.
 */
class BusinessWelcomeMailTest extends TestCase
{
    use DatabaseTransactions, RegistrationPayload;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'constants.allow_registration' => true,
            'constants.enable_recaptcha' => false,
            'yaigo.welcome_email_enabled' => true,
            'yaigo.mail.from_address' => 'no-reply@yaigo.es',
            'yaigo.mail.from_name' => 'Yaigo',
            'yaigo.mail.reply_to' => 'altas@yaigo.es',
        ]);
    }

    protected function assertWelcomeMailSent($email)
    {
        Mail::assertSent(BusinessWelcomeMail::class, 1);
        Mail::assertSent(BusinessWelcomeMail::class, function (BusinessWelcomeMail $mail) use ($email) {
            $mail->assertFrom('no-reply@yaigo.es', 'Yaigo');
            $mail->assertHasReplyTo('altas@yaigo.es');
            $mail->assertHasSubject('¡Bienvenido a Yaigo! Tu registro se ha completado');
            $mail->assertHasAttachment(
                \Illuminate\Mail\Mailables\Attachment::fromPath(BusinessWelcomeMail::attachmentPath())
                    ->as('Autorizacion-VERIFACTU-Yaigo.pdf')->withMime('application/pdf')
            );
            $mail->assertSeeInHtml('Tu cuenta se ha creado correctamente', false);
            $mail->assertSeeInText('rellénalo, fírmalo y envíanoslo');
            $mail->assertSeeInText('altas@yaigo.es');

            return $mail->hasTo($email) && count($mail->attachments()) === 1 && $mail->locale === 'es';
        });
    }

    public function test_website_registration_sends_welcome_mail_with_document()
    {
        Mail::fake();
        $data = $this->payload();

        $this->post('/business/register', $data)->assertRedirect()->assertSessionHasNoErrors();

        $this->assertWelcomeMailSent($data['email']);
    }

    public function test_superadmin_created_business_sends_welcome_mail_with_document()
    {
        $admin = User::where('username', 'admin')->first();
        if (empty($admin)) {
            $this->markTestSkipped('No "admin" superadmin user in this database.');
        }

        Mail::fake();
        $data = $this->payload();
        unset($data['tax_label_1'], $data['tax_number_1']);

        $this->actingAs($admin)->post('/superadmin/business', $data)->assertRedirect()->assertSessionHasNoErrors();

        $this->assertTrue(User::where('username', $data['username'])->exists());
        $this->assertWelcomeMailSent($data['email']);
    }

    public function test_mail_failure_does_not_break_registration()
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));
        $data = $this->payload();

        $this->post('/business/register', $data)->assertRedirect(route('login', ['lang' => 'es']))->assertSessionHasNoErrors();

        $user = User::where('username', $data['username'])->firstOrFail();
        $this->assertNotNull(Business::find($user->business_id));
    }

    public function test_disabled_flag_sends_nothing()
    {
        config(['yaigo.welcome_email_enabled' => false]);
        Mail::fake();

        $this->post('/business/register', $this->payload())->assertSessionHasNoErrors();

        Mail::assertNotSent(BusinessWelcomeMail::class);
    }

    public function test_missing_document_still_sends_welcome_without_attachment()
    {
        config(['yaigo.welcome_attachment.path' => 'public/does-not-exist.pdf']);
        Mail::fake();
        $data = $this->payload();

        $this->post('/business/register', $data)->assertSessionHasNoErrors();

        Mail::assertSent(BusinessWelcomeMail::class, function (BusinessWelcomeMail $mail) use ($data) {
            $mail->assertDontSeeInText('rellénalo, fírmalo y envíanoslo');

            return $mail->hasTo($data['email']) && count($mail->attachments()) === 0;
        });
    }
}
