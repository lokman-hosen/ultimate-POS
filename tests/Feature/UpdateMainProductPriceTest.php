<?php

namespace Tests\Feature;

use App\Business;
use App\Product;
use App\ProductVariation;
use App\TaxRate;
use App\Unit;
use App\User;
use App\Variation;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * POS inline unit price edit: "update product main price" endpoint.
 * Runs against the configured database inside a transaction that is rolled back.
 */
class UpdateMainProductPriceTest extends TestCase
{
    use DatabaseTransactions;

    protected $url = '/sells/pos/update-main-product-price';

    protected function setUp(): void
    {
        parent::setUp();

        $_SERVER['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $_SERVER['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'] ?? 'Symfony';
    }

    protected function admin()
    {
        $admin = User::where('user_type', 'user')->whereNotNull('business_id')->get()
            ->first(fn ($user) => $user->hasRole('Admin#' . $user->business_id));

        if (empty($admin)) {
            $this->markTestSkipped('No business admin user in the database.');
        }

        return $admin;
    }

    protected function userWithPermissions($business_id, array $permissions)
    {
        $role = Role::create(['name' => 'PriceTest' . uniqid() . '#' . $business_id, 'business_id' => $business_id, 'guard_name' => 'web']);
        foreach ($permissions as $permission) {
            $role->givePermissionTo(Permission::findOrCreate($permission, 'web'));
        }

        $user = User::create([
            'surname' => '', 'first_name' => 'Price', 'last_name' => 'Test',
            'username' => 'price_test_' . uniqid(), 'email' => uniqid() . '@example.com',
            'password' => bcrypt('secret'), 'language' => 'en',
            'business_id' => $business_id, 'user_type' => 'user', 'allow_login' => 1, 'status' => 'active',
        ]);
        $user->assignRole($role->name);

        return $user;
    }

    /**
     * Single product with purchase price 80 exc. tax, selling price 100 exc. tax, 10% tax.
     */
    protected function makeVariation($business_id, $user_id, $tax_type = 'exclusive', $type = 'single')
    {
        $unit = Unit::create(['business_id' => $business_id, 'actual_name' => 'Piece', 'short_name' => 'pc',
            'allow_decimal' => 0, 'created_by' => $user_id, ]);
        $box = Unit::create(['business_id' => $business_id, 'actual_name' => 'Box', 'short_name' => 'bx',
            'allow_decimal' => 0, 'base_unit_id' => $unit->id, 'base_unit_multiplier' => 12, 'created_by' => $user_id, ]);
        $tax = TaxRate::create(['business_id' => $business_id, 'name' => 'Test 10', 'amount' => 10, 'created_by' => $user_id]);

        $product = Product::create(['name' => 'Price test product', 'business_id' => $business_id, 'type' => $type,
            'unit_id' => $unit->id, 'tax' => $tax->id, 'tax_type' => $tax_type, 'enable_stock' => 0,
            'sku' => 'PT' . uniqid(), 'barcode_type' => 'C128', 'created_by' => $user_id, ]);
        $product_variation = ProductVariation::create(['name' => 'DUMMY', 'product_id' => $product->id, 'is_dummy' => 1]);

        $variation = Variation::create(['name' => 'DUMMY', 'product_id' => $product->id, 'sub_sku' => $product->sku,
            'product_variation_id' => $product_variation->id, 'woocommerce_variation_id' => 0,
            'default_purchase_price' => 80, 'dpp_inc_tax' => 88, 'profit_percent' => 25,
            'default_sell_price' => 100, 'sell_price_inc_tax' => 110, ]);

        $variation->box_unit_id = $box->id;

        return $variation;
    }

    protected function assertPrice(Variation $variation, $dsp, $inc_tax, $profit)
    {
        $fresh = Variation::find($variation->id);
        $this->assertEqualsWithDelta($dsp, (float) $fresh->default_sell_price, 0.0001);
        $this->assertEqualsWithDelta($inc_tax, (float) $fresh->sell_price_inc_tax, 0.0001);
        $this->assertEqualsWithDelta($profit, (float) $fresh->profit_percent, 0.0001);
    }

    public function test_updates_price_for_exclusive_and_inclusive_tax_products()
    {
        $admin = $this->admin();

        foreach (['exclusive', 'inclusive'] as $tax_type) {
            $variation = $this->makeVariation($admin->business_id, $admin->id, $tax_type);

            $this->actingAs($admin)
                ->postJson($this->url, ['variation_id' => $variation->id, 'product_id' => $variation->product_id, 'unit_price' => '120'])
                ->assertOk()
                ->assertJson(['success' => true]);

            $this->assertPrice($variation, 120, 132, 50);
        }
    }

    public function test_sub_unit_price_is_stored_per_base_unit()
    {
        $admin = $this->admin();
        $variation = $this->makeVariation($admin->business_id, $admin->id);

        $this->actingAs($admin)
            ->postJson($this->url, ['variation_id' => $variation->id, 'unit_price' => '1200', 'sub_unit_id' => $variation->box_unit_id])
            ->assertOk()
            ->assertJson(['success' => true, 'base_unit_sell_price' => 100]);

        $this->assertPrice($variation, 100, 110, 25);
    }

    public function test_variation_of_another_business_is_not_found()
    {
        $admin = $this->admin();
        $other_business = Business::where('id', '!=', $admin->business_id)->first();
        if (empty($other_business)) {
            $this->markTestSkipped('Only one business in the database.');
        }

        $variation = $this->makeVariation($other_business->id, $admin->id);

        $this->actingAs($admin)
            ->postJson($this->url, ['variation_id' => $variation->id, 'unit_price' => '120'])
            ->assertNotFound();

        $this->assertPrice($variation, 100, 110, 25);
    }

    public function test_requires_product_update_permission()
    {
        $admin = $this->admin();
        $variation = $this->makeVariation($admin->business_id, $admin->id);
        $user = $this->userWithPermissions($admin->business_id, ['edit_product_price_from_pos_screen']);

        $this->actingAs($user)
            ->postJson($this->url, ['variation_id' => $variation->id, 'unit_price' => '120'])
            ->assertForbidden();

        $this->assertPrice($variation, 100, 110, 25);
    }

    public function test_rejects_zero_negative_and_non_numeric_price()
    {
        $admin = $this->admin();
        $variation = $this->makeVariation($admin->business_id, $admin->id);

        foreach (['0', '-5', 'abc', ''] as $price) {
            $this->actingAs($admin)
                ->postJson($this->url, ['variation_id' => $variation->id, 'unit_price' => $price])
                ->assertStatus(422)
                ->assertJsonValidationErrors('unit_price');
        }

        $this->assertPrice($variation, 100, 110, 25);
    }

    public function test_combo_products_and_price_groups_are_not_updated()
    {
        $admin = $this->admin();

        $combo = $this->makeVariation($admin->business_id, $admin->id, 'exclusive', 'combo');
        $this->actingAs($admin)
            ->postJson($this->url, ['variation_id' => $combo->id, 'unit_price' => '120'])
            ->assertOk()
            ->assertJson(['success' => false]);
        $this->assertPrice($combo, 100, 110, 25);

        $variation = $this->makeVariation($admin->business_id, $admin->id);
        $this->actingAs($admin)
            ->postJson($this->url, ['variation_id' => $variation->id, 'unit_price' => '120', 'price_group_id' => 1])
            ->assertOk()
            ->assertJson(['success' => false]);
        $this->assertPrice($variation, 100, 110, 25);
    }
}
