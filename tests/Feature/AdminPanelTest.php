<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Status;
use App\Models\Thana;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected AdminUser $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = AdminUser::create([
            'name' => 'Admin User',
            'email' => 'admin@shop.com',
            'password' => Hash::make('password'),
        ]);

        Status::create(['tag' => 'pending', 'name' => 'Pending']);
        Status::create(['tag' => 'delivered', 'name' => 'Delivered']);

        Thana::create(['name' => 'Bogra Sadar']);
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('ShopAdmin');
        $response->assertSee('Sign in to your admin account');
    }

    public function test_admin_can_authenticate_using_the_login_screen(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@shop.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated('admin');
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_cannot_authenticate_with_invalid_password(): void
    {
        $this->post('/admin/login', [
            'email' => 'admin@shop.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest('admin');
    }

    public function test_admin_dashboard_can_be_rendered(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Total Products');
        $response->assertSee('Total Customers');
        $response->assertSee('Total Orders');
        $response->assertSee('Total Revenue');
    }

    public function test_product_crud_operations(): void
    {
        // 1. Index
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/products');
        $response->assertStatus(200);

        // 2. Create form
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/products/create');
        $response->assertStatus(200);

        // 3. Store
        $response = $this->actingAs($this->admin, 'admin')->post('/admin/products', [
            'name' => 'Test Denim Jacket',
            'link' => 'https://example.com/denim-jacket',
            'category' => 'Jackets',
            'size' => 'L, XL',
            'price' => 1500,
            'selling_price' => 1250,
        ]);
        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('name', 'Test Denim Jacket')->first();
        $this->assertNotNull($product);
        $this->assertEquals(1250, $product->selling_price);

        // 4. Show
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/products/' . $product->id);
        $response->assertStatus(200);

        // 5. Update
        $response = $this->actingAs($this->admin, 'admin')->put('/admin/products/' . $product->id, [
            'name' => 'Updated Denim Jacket',
            'link' => 'https://example.com/denim-jacket',
            'selling_price' => 1200,
        ]);
        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Updated Denim Jacket']);

        // 6. Delete
        $response = $this->actingAs($this->admin, 'admin')->delete('/admin/products/' . $product->id);
        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_customer_crud_operations_and_quick_store(): void
    {
        // 1. Index
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/customers');
        $response->assertStatus(200);

        // 2. Store
        $phone = '01711' . rand(100000, 999999);
        $response = $this->actingAs($this->admin, 'admin')->post('/admin/customers', [
            'name' => 'Test Customer',
            'phone' => $phone,
        ]);
        $response->assertRedirect(route('admin.customers.index'));
        $customer = Customer::where('phone', $phone)->first();
        $this->assertNotNull($customer);

        // 3. Quick store AJAX
        $quickPhone = '01811' . rand(100000, 999999);
        $ajaxResponse = $this->actingAs($this->admin, 'admin')->postJson('/admin/customers/quick-store', [
            'name' => 'Quick Customer',
            'phone' => $quickPhone,
        ]);
        $ajaxResponse->assertStatus(200)->assertJson(['success' => true]);

        // 4. Update
        $response = $this->actingAs($this->admin, 'admin')->put('/admin/customers/' . $customer->id, [
            'name' => 'Updated Customer Name',
            'phone' => $phone,
        ]);
        $response->assertRedirect(route('admin.customers.index'));
        $this->assertDatabaseHas('customers', ['name' => 'Updated Customer Name']);

        // 5. Delete
        $response = $this->actingAs($this->admin, 'admin')->delete('/admin/customers/' . $customer->id);
        $response->assertRedirect(route('admin.customers.index'));
    }

    public function test_order_creation_and_details(): void
    {
        $customer = Customer::create([
            'name' => 'Rahim Ahmed',
            'phone' => '01711-000001',
        ]);

        $product = Product::create([
            'name' => 'Classic Tee',
            'link' => 'https://example.com/tee',
            'selling_price' => 380,
        ]);

        $thana = Thana::first();

        // Create order
        $response = $this->actingAs($this->admin, 'admin')->post('/admin/orders', [
            'customer_id' => $customer->id,
            'order_date' => date('Y-m-d'),
            'address' => 'Test delivery address',
            'thana_id' => $thana?->id,
            'status' => 'pending',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'making_price' => 300,
                    'asking_price' => 450,
                    'selling_price' => 380,
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.orders.index'));

        $order = Order::where('customer_id', $customer->id)->latest('id')->first();
        $this->assertNotNull($order);
        $this->assertCount(1, $order->orderDetails);
        $this->assertEquals(760, $order->total_amount);

        // Show view
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/orders/' . $order->id);
        $response->assertStatus(200);
        $response->assertSee($order->order_no);
    }
}
