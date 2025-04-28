# Hotel Management System Prompts

## 1. Create Database Files for Room and RoomType Models

- **Models**:
  - Create models for `Room` and `RoomType`.

- **Migrations**:
  - Add typical columns for hotel rooms and room types.
  - Each room type should have a different price per night.

- **Factories**:
  - Use the `fake()` helper (not `$this->faker`).
  - Optimize queries for better performance.

- **Seeders**:
  - Add typical room types for a hotel.
  - Create 10-20 rooms for each type.

- **Best Practices**:
  - Use `foreignIdFor()` instead of `foreignId()` in migrations.
  - Use `onDeleteCascade()` instead of `onDelete('cascade')`.
  - If you have an enum type, create an enum file in `App\Enum` and use it for values.

---

## 2. Change Room Status Column to `is_available`

- Change the `status` column in the `rooms` table to a boolean column `is_available` with a default value of `true`.
- Update migrations, models, factories, and seeders accordingly.
- In the seeder:
  - Ensure 90% of rooms are available.
  - Use a logical sequence for room numbers and floors:
    - Room numbers should increment.
    - The first digit should correspond to the floor number.
  - Assign room types to rooms randomly.

---

## 3. Generate Filament Resource for Room Types

- Use the command:  
  `php artisan make:filament-resource --generate`
- **Table Columns**:
  - Name
  - Price per night
  - Capacity
- **Form**:
  - Use `CheckboxList` for amenities.

---

## 4. Change RoomType Amenities to a Separate Model

- Create a new `Amenity` model.
- Establish a many-to-many relationship between `RoomType` and `Amenity`.
- Generate a seeder for amenities.
- Update the `RoomType` factory to use values from the amenities seeder.
- Update the Filament resource form to use `CheckboxList` for multiple relationships.

---

## 5. Generate a Tailwind Homepage

- Replace the default Laravel homepage with a new `welcome.blade.php`.
- **Page Elements**:
  - A big photo background (use a free image from Unsplash or similar).
  - A form to search for rooms:
    - Fields: `date_from` (default: tomorrow), `date_to` (default: today + 5 days), and `number_of_people`.
  - A footer with:
    - Email
    - Phone number
    - Links to social profiles
- Use existing components wherever possible.
- Use Vite with Laravel 12 syntax (not Tailwind from CDN).

---

## 6. Create a Search RoomsController

- Create an invokable `SearchRoomsController`.
- Update the `search-rooms` route to use this controller.
- **Controller Method**:
  - Return all `RoomType` records with amenities that fit the requested guest capacity.
  - Order results by price per night (ascending).
- **Blade View**:
  - Display room types in separate blocks/sections.
  - For each room type, show:
    - Name
    - Price per night
    - List of amenities
    - A "Book Now" link leading to a new `booking` route with parameters:
      - `date_from`
      - `date_to`
      - `guests`
      - `room_type_id`
  - Reuse the header/footer from `welcome.blade.php`.

---

## 7. Add a "Size" Column to RoomType

- Add a `size` column (in square meters) to the `RoomType` table.
- Update the seeder with appropriate values.
- Display the size in the room search results near the amenities list.

---

## 8. Add a Photo Field to RoomType

- Use the `Spatie Media Library` package.
- **Model**:
  - Specify a media thumbnail size for room types.
- **Filament Resource**:
  - Add a form field to manage photos.
- **Search Results**:
  - Display a placeholder thumbnail if no photo is uploaded.

---

## 9. Create a BookingController

- **Methods**:
  - `create()`:  
    - Return a Blade view with a booking form.
    - Reuse the header/footer and background image from `welcome.blade.php`.
    - **Form Fields**:
      - Check-in and check-out dates (disabled, with hidden input values).
      - Number of guests (disabled, with hidden input values).
      - Customer name and email (editable).
    - **Price Calculation**:
      - Calculate the total price based on the date range and price per night.
      - Display the total price with a note that payment is made upon arrival.
      - Use a `PricingService` for calculations.
  - `store()`:  
    - Validate the request using a `Request` class.
    - Create a new `Customer` (use `firstOrCreate`).
    - Create a `Booking` assigned to the customer.
    - Select the first available room for the requested room type and dates.
    - If no room is available, return with a validation error.
    - On success, redirect to a confirmation page.

---

## 10. Handle Booking Confirmation

- Add a confirmation method to the `BookingController`.
- Create a confirmation route.
- **Confirmation Page**:
  - Reuse the header/footer and background image from `welcome.blade.php`.
  - Display a message: "Booking successful."
  - Add a link to the homepage.

---

## 11. Suggest Alternative Booking Options

- If no room is available for 1 guest:
  - Suggest a larger room.
  - Display a message: "No rooms for 1 guest, but we found a larger room."
- If no single room can fit all guests:
  - Suggest booking multiple rooms.
  - Display a message: "No single room available for X guests. You can book multiple rooms to fit everyone."
- Ensure proper handling of booking logic and pricing calculations.

---

## 12. Multiple Room Booking Enhancement

- Allow booking multiple rooms for the same date and customer.
- Display the price per night for each room.
- Calculate and display the total cost for all selected rooms.

---

## 13. Manage Hotel Settings

- Use the `spatie/laravel-settings` package to manage:
  - Hotel name
  - Email
  - Phone number
  - Address
  - Social links (Facebook, Instagram, Twitter)
- Create a Filament page (not a resource) to edit these settings.
- Use these settings in the footer for email, phone, address, and social links.

---

## 14. Generate Pest Tests

- Write Pest tests for all routes in `routes/web.php`.
- Divide tests into separate files based on their purpose.
- For routes with parameters:
  - Test various parameter combinations and expected results.
  - Include validation tests.
- Use `RefreshDatabase` for all tests.
- Update `phpunit.xml` to use an SQLite in-memory database.

---

## 15. Create a Contact Page

- **Model**:
  - Create a `Contact` model with fields:
    - Subject
    - Email
    - Message
    - File upload (use `Spatie Media Library`).
- **Page**:
  - Create a contact page with the same header/footer as the search-room page.
  - Register the page in the web routes.
  - **Form Fields**:
    - Email
    - Subject
    - Message
    - File upload
  - Use a Livewire component for the form.
  - Add a rate limit: 5 contacts per hour.
- **Filament Resource**:
  - Add a resource to list and view contacts with `Infolist` components.

---

## 16. Write a README.md File

- **Structure**:
  - Project Title
  - Introduction (2-3 sentences)
  - Features
  - Installation Instructions (placeholder for now)
  - Usage Examples
  - Demo/Screenshots (use the `/screenshot` folder for images)
  - Technologies Used
  - Contributing Guidelines
  - License
  - Contact Information
- **Project Details**:
  - Name: Hotel-Booking
  - License: MIT
