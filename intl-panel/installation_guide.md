# Installation & Production Deployment Guide

This guide details the setup, database configurations, and operational workflows for the **International Courier ERP & Booking Management System**.

## System Prerequisites
- PHP 7.4 to PHP 8.x (PHP 7.4.30 recommended)
- MySQL 5.7 or MySQL 8.0
- Web Server (Apache with `mod_rewrite` enabled)
- Web Browser (Chrome, Firefox, Safari)

---

## 1. Local Development Setup
1. **Copy Files**: Ensure all files are inside `d:\Xampp\htdocs\c-syn\intl-panel\`.
2. **Database Import**:
   - Start Apache and MySQL from XAMPP.
   - Open phpMyAdmin (`http://localhost/phpmyadmin/`).
   - Create a new database named `intl_courier_db` with collation `utf8mb4_general_ci`.
   - Select the database and import the file [database.sql](file:///d:/Xampp/htdocs/c-syn/intl-panel/database.sql) which is in the root directory.
3. **Verify Settings**:
   - Database connection settings are pre-configured in [database.php](file:///d:/Xampp/htdocs/c-syn/intl-panel/application/config/database.php) (Host: `localhost`, Username: `root`, Password: ``, Database: `intl_courier_db`).
   - Clean URLs are active via the root [.htaccess](file:///d:/Xampp/htdocs/c-syn/intl-panel/.htaccess).

---

## 2. Seed Login Credentials
The database import seeds default roles, permissions, branches, courier partners, rate matrices, and a Super Admin account.

### Portal Logins:
- **Role**: Super Admin
  - **Email**: `admin@couriersyn.com`
  - **Password**: `admin123`
- **Role**: Exporter / Customer (Create by registering on the portal)
  - Go to `http://localhost/c-syn/intl-panel/register`
  - Register as an Individual or Business.

---

## 3. Operational Workflows & Tests

### Scenario A: Booking and Verification
1. **Staff Login**: Log in as `admin@couriersyn.com`.
2. **Shipment Booking**:
   - Go to **Shipment Bookings** -> Click **New Booking**.
   - Fill Sender, Receiver, Routing Slabs. Select a Customer Account.
   - Input Box dimensions (Length, Width, Height, Weight). Chargeable weight auto-calculates as the greater of actual or volumetric ($L \times W \times H / 5000$).
   - Click **Look up rates** to fetch estimated shipping charges from the matrix.
   - Dynamic Item Contents: Enter goods, HS codes, and values.
   - Check **Generate Pickup Request** to mock courier dispatch.
   - Click **Create & Save Booking**. Shipment status becomes `Booking Created` & `Verification Pending`.
3. **Customer Authorization**:
   - Log in as the Customer.
   - The dashboard displays a pending verification alert. Click **Sign & Verify Wizard**.
   - **Wizard Step 1**: Review consignment details. Click Continue.
   - **Wizard Step 2**: View prohibited warnings. Agree to Declaration checkbox and scroll Terms & Conditions scrollbox to the bottom to agree.
   - **Wizard Step 3**: Draw your digital signature inside the dashed canvas, click **Lock Signature** to save.
   - **Wizard Step 4**: Check the flashing dashboard banner for the mock verification OTP, type the code (e.g. `123456` or the random code), and click **Authorize & Release**.
4. **KYC Approval (Staff Only)**:
   - Exporters must submit KYC files (Passport, GST, trade licenses) from their portal side under **Upload KYC Docs**.
   - Admin goes to **KYC Requests**, reviews documents, and selects **Approve KYC**.
5. **Transit Movement Updates**:
   - Once both KYC is Approved and the Customer completes signature/OTP verification, the shipment releases to `Ready For Dispatch` / `Released For Transit`.
   - Staff can now post transit movement milestones (e.g., `Received At Origin Hub`, `Customs Clearance`, `Delivered`). Non-verified shipments block tracking updates.

---

## 4. REST API Endpoint Specifications
All REST endpoints are housed in `application/controllers/Api.php` and use stateless token authentication.

- **Token Generation (Login)**:
  - **Endpoint**: POST `http://localhost/c-syn/intl-panel/api/v1/login`
  - **Params**: `email`, `password`
  - **Response**: Returns a Bearer authentication token.
- **Header Authentication**:
  - For all secured endpoints, attach: `Authorization: Bearer <TOKEN>`
- **Calculating Rates**:
  - **Endpoint**: POST `http://localhost/c-syn/intl-panel/api/v1/rates`
  - **Params**: `origin_country_id`, `destination_country_id`, `service_type`, `chargeable_weight`
- **Public AWB Tracking**:
  - **Endpoint**: POST `http://localhost/c-syn/intl-panel/api/v1/tracking`
  - **Params**: `awb_number`

---

## 5. Production Deployment Guide
1. **Host Server Setup**: Ensure PHP 7.4+ and Apache mod_rewrite is enabled on the server.
2. **Path Variables Configuration**:
   - Open [config.php](file:///d:/Xampp/htdocs/c-syn/intl-panel/application/config/config.php). Set `$config['base_url']` to your production domain (e.g. `https://erp.yourdomain.com/`).
3. **Database Migration**:
   - Create production MySQL database.
   - Import `database.sql`.
   - Update production username/password inside `application/config/database.php`.
4. **Environment Mode**:
   - Open `index.php` in the root.
   - Locate `define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');`
   - Change `'development'` to `'production'` to suppress PHP error outputs in front of clients.
5. **Write Permissions**:
   - Ensure the folders `/assets/signatures/`, `/assets/kyc_documents/`, and `/assets/shipment_documents/` have write permissions (e.g. `chmod 755` or `777` depending on the hosting environment).
