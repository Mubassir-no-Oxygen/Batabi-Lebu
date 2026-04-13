<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Farmer;
use App\Models\Buyer;
use App\Models\Crop;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin ────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@batabi-lebu.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
            'phone'    => '01700000000',
        ]);

        // ─── 10 Farmers ───────────────────────────────────────
        $farmerData = [
            ['name'=>'Rahim Uddin',    'email'=>'rahim@farmer.com',   'phone'=>'01711000001', 'farm'=>'Rahim Green Farm',      'district'=>'Sylhet',       'sub'=>'Companiganj', 'land'=>15.5, 'status'=>'approved'],
            ['name'=>'Karim Ali',      'email'=>'karim@farmer.com',   'phone'=>'01711000002', 'farm'=>'Karim Agro Farm',       'district'=>'Bogura',       'sub'=>'Shibganj',    'land'=>25.0, 'status'=>'approved'],
            ['name'=>'Salam Mia',      'email'=>'salam@farmer.com',   'phone'=>'01711000003', 'farm'=>'Salam Natural Farm',    'district'=>'Mymensingh',   'sub'=>'Trishal',     'land'=>8.0,  'status'=>'pending'],
            ['name'=>'Jamal Hossain',  'email'=>'jamal@farmer.com',   'phone'=>'01711000004', 'farm'=>'Jamal Organic Farm',   'district'=>'Rajshahi',     'sub'=>'Paba',        'land'=>20.0, 'status'=>'approved'],
            ['name'=>'Nurul Islam',    'email'=>'nurul@farmer.com',   'phone'=>'01711000005', 'farm'=>'Nurul Agro',           'district'=>'Comilla',      'sub'=>'Debidwar',    'land'=>12.0, 'status'=>'approved'],
            ['name'=>'Ratan Kumar',    'email'=>'ratan@farmer.com',   'phone'=>'01711000006', 'farm'=>'Ratan Fresh Farm',     'district'=>'Khulna',       'sub'=>'Phultala',    'land'=>18.5, 'status'=>'approved'],
            ['name'=>'Faruk Ahmed',    'email'=>'faruk@farmer.com',   'phone'=>'01711000007', 'farm'=>'Faruk Paddy Farm',     'district'=>'Dinajpur',     'sub'=>'Chirirbandar','land'=>30.0, 'status'=>'approved'],
            ['name'=>'Selim Molla',    'email'=>'selim@farmer.com',   'phone'=>'01711000008', 'farm'=>'Selim Horticulture',   'district'=>'Jessore',      'sub'=>'Bagherpara',  'land'=>10.0, 'status'=>'rejected'],
            ['name'=>'Abul Kalam',     'email'=>'abul@farmer.com',    'phone'=>'01711000009', 'farm'=>'Kalam Green Fields',   'district'=>'Tangail',      'sub'=>'Gopalpur',    'land'=>22.0, 'status'=>'approved'],
            ['name'=>'Joynal Abedin',  'email'=>'joynal@farmer.com',  'phone'=>'01711000010', 'farm'=>'Joynal Farm & Co',     'district'=>'Rangpur',      'sub'=>'Mithapukur',  'land'=>16.0, 'status'=>'approved'],
        ];

        $farmers = [];
        foreach ($farmerData as $d) {
            $user = User::create([
                'name'     => $d['name'],
                'email'    => $d['email'],
                'password' => Hash::make('password123'),
                'role'     => 'farmer',
                'phone'    => $d['phone'],
            ]);
            $farmers[] = Farmer::create([
                'user_id'             => $user->id,
                'farm_name'           => $d['farm'],
                'district'            => $d['district'],
                'sub_district'        => $d['sub'],
                'land_size'           => $d['land'],
                'land_unit'           => 'bigha',
                'verification_status' => $d['status'],
                'rejection_reason'    => $d['status'] === 'rejected' ? 'Incomplete farm documentation submitted.' : null,
                'verified_at'         => in_array($d['status'], ['approved']) ? now() : null,
                'verified_by'         => in_array($d['status'], ['approved']) ? $admin->id : null,
            ]);
        }

        // ─── 10 Buyers ────────────────────────────────────────
        $buyerData = [
            ['name'=>'Dhaka Traders Ltd.',     'email'=>'dhaka@buyer.com',     'phone'=>'01811000001', 'company'=>'Dhaka Traders Ltd.',     'address'=>'45 Karwan Bazar, Dhaka',    'district'=>'Dhaka'],
            ['name'=>'Chittagong Fresh Co.',   'email'=>'ctg@buyer.com',       'phone'=>'01811000002', 'company'=>'Chittagong Fresh Co.',   'address'=>'12 Agrabad, Chittagong',    'district'=>'Chittagong'],
            ['name'=>'Sylhet Agro Market',     'email'=>'sylhet@buyer.com',    'phone'=>'01811000003', 'company'=>'Sylhet Agro Market',     'address'=>'Zindabazar, Sylhet',        'district'=>'Sylhet'],
            ['name'=>'Aman Khan',              'email'=>'aman@buyer.com',      'phone'=>'01811000004', 'company'=>null,                     'address'=>'Mirpur-10, Dhaka',          'district'=>'Dhaka'],
            ['name'=>'Priya Suppliers',        'email'=>'priya@buyer.com',     'phone'=>'01811000005', 'company'=>'Priya Suppliers',        'address'=>'Shantinagar, Sylhet',       'district'=>'Sylhet'],
            ['name'=>'Rajshahi Wholesale Hub', 'email'=>'rajshahi@buyer.com',  'phone'=>'01811000006', 'company'=>'Rajshahi Wholesale Hub', 'address'=>'Shaheb Bazar, Rajshahi',    'district'=>'Rajshahi'],
            ['name'=>'Babu Mondol',            'email'=>'babu@buyer.com',      'phone'=>'01811000007', 'company'=>null,                     'address'=>'Jessore Sadar',             'district'=>'Jessore'],
            ['name'=>'Green Basket Ltd.',      'email'=>'greenbasket@buyer.com','phone'=>'01811000008', 'company'=>'Green Basket Ltd.',     'address'=>'Banani, Dhaka',             'district'=>'Dhaka'],
            ['name'=>'Khulna Food Mart',       'email'=>'khulna@buyer.com',    'phone'=>'01811000009', 'company'=>'Khulna Food Mart',       'address'=>'Boyra, Khulna',             'district'=>'Khulna'],
            ['name'=>'Mofiz Uddin',            'email'=>'mofiz@buyer.com',     'phone'=>'01811000010', 'company'=>null,                     'address'=>'Tangail Sadar',             'district'=>'Tangail'],
        ];

        $buyers = [];
        foreach ($buyerData as $d) {
            $user = User::create([
                'name'     => $d['name'],
                'email'    => $d['email'],
                'password' => Hash::make('password123'),
                'role'     => 'buyer',
                'phone'    => $d['phone'],
            ]);
            $buyers[] = Buyer::create([
                'user_id'      => $user->id,
                'company_name' => $d['company'],
                'address'      => $d['address'],
                'district'     => $d['district'],
            ]);
        }

        // ─── 10 Crops (spread across approved farmers) ────────
        // Only approved farmers: indices 0,1,3,4,5,6,8,9
        $approvedFarmers = [$farmers[0], $farmers[1], $farmers[3], $farmers[4],
                            $farmers[5], $farmers[6], $farmers[8], $farmers[9]];

        $cropData = [
            ['farmer'=>0, 'name'=>'Red Tomato',          'cat'=>'vegetable', 'qty'=>500,  'unit'=>'kg',     'price'=>45,  'status'=>'available', 'harvest'=>7,  'desc'=>'Fresh organically grown red tomatoes, Grade A quality from Sylhet highlands.'],
            ['farmer'=>1, 'name'=>'Potato (Diamond)',     'cat'=>'vegetable', 'qty'=>2000, 'unit'=>'kg',     'price'=>25,  'status'=>'available', 'harvest'=>-5, 'desc'=>'Diamond variety potato from Bogura — best quality in the market.'],
            ['farmer'=>2, 'name'=>'BRRI Dhan-28 Rice',   'cat'=>'grain',     'qty'=>5000, 'unit'=>'kg',     'price'=>35,  'status'=>'upcoming',  'harvest'=>20, 'desc'=>'Boro season BRRI Dhan-28. Pre-orders welcome, ready in 3 weeks.'],
            ['farmer'=>3, 'name'=>'White Onion',         'cat'=>'vegetable', 'qty'=>800,  'unit'=>'kg',     'price'=>60,  'status'=>'available', 'harvest'=>0,  'desc'=>'Freshly harvested white onion, sun-dried and ready for bulk purchase.'],
            ['farmer'=>4, 'name'=>'Green Chili',         'cat'=>'vegetable', 'qty'=>300,  'unit'=>'kg',     'price'=>90,  'status'=>'available', 'harvest'=>3,  'desc'=>'Locally grown hot green chili, ideal for bulk processing.'],
            ['farmer'=>5, 'name'=>'Hilsa Brinjal',       'cat'=>'vegetable', 'qty'=>400,  'unit'=>'kg',     'price'=>55,  'status'=>'available', 'harvest'=>5,  'desc'=>'Large purple brinjal, disease-free. Pesticide-free cultivation.'],
            ['farmer'=>6, 'name'=>'Wheat (Shatabdi)',    'cat'=>'grain',     'qty'=>8000, 'unit'=>'kg',     'price'=>28,  'status'=>'available', 'harvest'=>-10,'desc'=>'Shatabdi variety wheat from Dinajpur, moisture content <12%.'],
            ['farmer'=>7, 'name'=>'Mango (Langra)',      'cat'=>'fruit',     'qty'=>600,  'unit'=>'kg',     'price'=>120, 'status'=>'upcoming',  'harvest'=>45, 'desc'=>'Langra mango from Tangail — pre-book now for the season.'],
            ['farmer'=>0, 'name'=>'Lal Shak (Red Spinach)','cat'=>'vegetable','qty'=>200, 'unit'=>'kg',    'price'=>30,  'status'=>'available', 'harvest'=>2,  'desc'=>'Fresh lal shak, ready for harvest in 2 days.'],
            ['farmer'=>1, 'name'=>'Mustard Seed',        'cat'=>'spice',     'qty'=>1500, 'unit'=>'kg',     'price'=>75,  'status'=>'available', 'harvest'=>-3, 'desc'=>'High oil-content mustard seed from Bogura. Cleaned & bagged.'],
        ];

        $crops = [];
        foreach ($cropData as $d) {
            $crops[] = Crop::create([
                'farmer_id'      => $approvedFarmers[$d['farmer']]->id,
                'crop_name'      => $d['name'],
                'category'       => $d['cat'],
                'quantity'       => $d['qty'],
                'unit'           => $d['unit'],
                'price_per_unit' => $d['price'],
                'harvest_date'   => now()->addDays($d['harvest'])->format('Y-m-d'),
                'available_from' => $d['status'] === 'upcoming' ? now()->addDays($d['harvest'])->format('Y-m-d') : now()->format('Y-m-d'),
                'available_until'=> now()->addDays($d['harvest'] + 30)->format('Y-m-d'),
                'description'    => $d['desc'],
                'status'         => $d['status'],
            ]);
        }

        // ─── 10 Orders ────────────────────────────────────────
        $orderData = [
            // [buyer_idx, crop_idx, qty, offered_price, status, note]
            [0, 0, 200,  42,   'pending',   'Need delivery by end of month.'],
            [1, 1, 1000, null, 'accepted',  'Standard price is fine.'],
            [2, 2, 500,  32,   'pending',   'Pre-booking for next season.'],
            [3, 3, 400,  58,   'accepted',  'Urgent — need within 5 days.'],
            [4, 4, 150,  null, 'rejected',  'Looking for pesticide-free only.'],
            [5, 5, 200,  50,   'pending',   'For restaurant supply, weekly repeat order expected.'],
            [6, 6, 3000, 26,   'accepted',  'Price negotiable for bulk above 2 ton.'],
            [7, 7, 100,  110,  'pending',   'Pre-order for upcoming season.'],
            [8, 8, 80,   null, 'completed', 'Delivered successfully.'],
            [9, 9, 700,  70,   'accepted',  'Need moisture certificate with delivery.'],
        ];

        foreach ($orderData as [$bi, $ci, $qty, $offer, $status, $note]) {
            Order::create([
                'buyer_id'           => $buyers[$bi]->id,
                'crop_id'            => $crops[$ci]->id,
                'requested_quantity' => $qty,
                'offered_price'      => $offer,
                'final_price'        => in_array($status, ['accepted','completed'])
                                          ? ($offer ?? $crops[$ci]->price_per_unit)
                                          : null,
                'note'               => $note,
                'status'             => $status,
                'accepted_at'        => in_array($status, ['accepted','completed']) ? now() : null,
                'completed_at'       => $status === 'completed' ? now() : null,
            ]);
        }
    }
}
