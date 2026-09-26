<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Quick access menus on the dashboard must mirror the sidebar built by AdminSidebarMenu.
 * Runs against the configured database inside a transaction that is rolled back.
 */
class DashboardQuickAccessTest extends TestCase
{
    use DatabaseTransactions;

    protected function dashboardFor(User $user)
    {
        // The layout and app/Http/helpers.php read these from $_SERVER directly
        $_SERVER['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $_SERVER['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'] ?? 'Symfony';

        return $this->actingAs($user)
            ->withSession(['preferred_section' => 'quick-access'])
            ->get('/home');
    }

    protected function quickAccessUrls($html)
    {
        $this->assertStringContainsString('<!--Quick Access Menus Start-->', $html);
        $this->assertStringContainsString('<!--Quick Access Menus End-->', $html);

        $section = explode('<!--Quick Access Menus End-->', explode('<!--Quick Access Menus Start-->', $html)[1])[0];
        preg_match_all('/<a href="([^"]+)" class="launch-card"/', $section, $matches);

        return array_map('html_entity_decode', $matches[1]);
    }

    protected function sidebarUrls($html)
    {
        $sidebar = explode('id="sidebar-no-results"', explode('id="side-bar"', $html)[1])[0];
        preg_match_all('/<a href="([^"#][^"]*)"/', $sidebar, $matches);

        $home = route('home');

        return array_values(array_filter(array_map('html_entity_decode', $matches[1]), fn ($url) => $url !== $home));
    }

    public function test_admin_quick_access_matches_sidebar()
    {
        $admin = User::where('user_type', 'user')->whereNotNull('business_id')->get()
            ->first(fn ($user) => $user->hasRole('Admin#' . $user->business_id));

        if (empty($admin)) {
            $this->markTestSkipped('No business admin user in the database.');
        }

        $html = $this->dashboardFor($admin)->assertOk()->getContent();

        $this->assertSame($this->sidebarUrls($html), $this->quickAccessUrls($html));
        $this->assertContains(route('products.index'), $this->quickAccessUrls($html));

        // Every shortcut carries a subtitle
        $section = explode('<!--Quick Access Menus End-->', explode('<!--Quick Access Menus Start-->', $html)[1])[0];
        $this->assertSame(substr_count($section, 'class="launch-card"'), substr_count($section, 'class="launch-desc"'));
    }

    public function test_restricted_role_only_sees_permitted_shortcuts()
    {
        $admin = User::where('user_type', 'user')->whereNotNull('business_id')->firstOrFail();
        $business_id = $admin->business_id;

        $role = Role::create(['name' => 'QuickAccessTest#' . $business_id, 'business_id' => $business_id, 'guard_name' => 'web']);
        $role->givePermissionTo('sell.view');

        $user = User::create([
            'surname' => '', 'first_name' => 'Quick', 'last_name' => 'Access',
            'username' => 'quick_access_' . uniqid(), 'email' => uniqid() . '@example.com',
            'password' => bcrypt('secret'), 'language' => 'en',
            'business_id' => $business_id, 'user_type' => 'user', 'allow_login' => 1,
        ]);
        $user->assignRole($role);

        $html = $this->dashboardFor($user)->assertOk()->getContent();
        $urls = $this->quickAccessUrls($html);

        $this->assertSame($this->sidebarUrls($html), $urls);
        $this->assertContains(route('sells.index'), $urls);
        $this->assertNotContains(route('products.index'), $urls);
        $this->assertNotContains(route('users.index'), $urls);
        $this->assertNotContains(route('purchases.index'), $urls);
    }
}
