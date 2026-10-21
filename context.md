Database:
1. admin_users
id
name
email
password
2. products
id
name
link
image
category_id
size
price
selling_price
created_by
created_at
updated_at
updated_by
Mandatory: name, link, selling_price
3. customers
id
name
phone
created_by
created_at
Mandatory: name, phone
Constraint: phone must be Unique.
4. thana
id
name
division_id
5. orders
id
order_no
customer_id
order_date
exp_delv_date
address
thana_id
status
created_by
created_at
updated_by
updated_at
Constraint: order_no must be Unique.
6. order_details
id
order_id
product_id
quantity
making_price
asking_price
selling_price
7. status
id
tag
name



folder structure:

app/
├── Modules/
│   ├── Admin/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── ProductController.php
│   │   │   ├── CustomerController.php
│   │   │   └── OrderController.php
│   │   │
│   │   ├── Models/
│   │   ├── Services/
│   │   ├── Requests/
│   │   ├── Repositories/
│   │   ├── Middleware/
│   │   └── Routes/
│   │       └── web.php
│   │
│   └── Store/
│       ├── Controllers/
│       │   ├── HomeController.php
│       │   ├── ProductController.php
│       │   ├── CartController.php
│       │   ├── CheckoutController.php
│       │   └── OrderController.php
│       │
│       ├── Models/
│       ├── Services/
│       ├── Requests/
│       ├── Repositories/
│       ├── Middleware/
│       └── Routes/
│           └── web.php
│
├── Models/
│   ├── Product.php
│   ├── Customer.php
│   ├── Order.php
│   ├── OrderDetail.php
│   ├── Thana.php
│   └── Status.php
│
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
│
├── Providers/
├── Services/
└── ...
    
resources/
└── views/
    ├── admin/
    │   ├── layouts/
    │   ├── dashboard/
    │   ├── products/
    │   ├── customers/
    │   └── orders/
    │
    └── store/
        ├── layouts/
        ├── products/
        ├── cart/
        ├── checkout/
        └── orders/

routes/
├── web.php
├── console.php
├── admin.php
└── store.php

database/
├── migrations/
├── seeders/
└── factories/


admin login page 
baseurl/admin/login
admin prefix alwys be admin/
it all admin routes will be inside auth middleware
only users of admin_users can access those

admin login page will contains email and password no registration page will be there
password should be stored in hash admin_users.password field
admin page desgin is public\ShopAdmin — E-commerce Admin Panel.html
alwys use slect 2 library for dropdown input fields

controller only requst response handle korbe r service e sob bussiness logic thakbe

database is in postgresql
