1.

You're creating a Hotel management system in Laravel for a single hotel. Create the database files for Room and
RoomType models:

Models
—
Migrations with typical columns for hotel rooms and room types, each room type should have a different price per night. -
Factories using faker library and Laravel fake() helper and not $this->faker
also use the most optimize way to get the query faster
—
Seeders for typical Room types for a hotel and 10-20 rooms for each type
—
on larvel migration whenever u have e foreignId use foreignIdFor() and whenever u have onDelete('cascade') use onDeleteCascade
—
if u have an enum type also make that enum file on App\Enum and use the enum for value

2.

Change column status in rooms to boolean column is_available, default true. Change migrations, models, factories and seeders accordingly. In seeder, 90% of rooms should be available.
Also, in seeders for room numbers and floors, use a proper logical sequence instead of fake data. Room numbers should go in incremental order, and the first digit should correspond to their floor number. Assign room type to each room randomly.

3.

Generate Filament resource to manage Room Types. Show table columns name, price per night, capacity. For the Form, for amenities use CheckboxList.
when using the php artisan make:filament-resource use the flag "--generate"

4.

Change RoomType amenities from json to be a separate Model Amenity with a many- to-many relationship to RoomType.
Also, generate a Seeder for Amenities and change the factory of RoomType accordingly to use the values from that Seeder.
Also, change Filament Resource form method to use CheckboxList with multiple relationship.

5.

Generate a Tailwind homepage for a hotel and place it into welcome.blade.php instead of the default Laravel homepage.
Elements of the page:

- A big photo background: use some free image from Unsplash or other platform
- Form to search the room by fields of date from (default tomorrow) and date to (default today + 5 days) and number of people staying
- A footer with main information like email, phone number and links to social profiles
- Use the existing component as much as u can
In Blade, use Vite with Laravel 12 syntax and not Tailwind from CDN

6.

Create a Search RoomsController invokable controller, change the search-rooms route to use it.
In Controller method, return all Room Type records with amenities that fit the capacity of guests requested, ordered by room type price per night, ascending.
Build Blade View file to show all those room types, in separate blocks/sections. Use Tailwind and re-use header/ footer from welcome.blade.php. On that page, for each room type, show name, price per night, list of amenities and a link "Book Now" that leads to the new route of "booking" with the parameters of date from/to, guests (from requests) and room type ID. Create that new Route with dosent return anything yet

7.

Add a column of "size" (in square meters) to RoomType. Add appropriate values to the seeder. Show that size in the rooms search results, near amenities list.

8.

We need a photo field for Room Type to replace the placeholder we have. For that,let's use a package Spatie Media Library.

In the Model, specify media thumbnail size so it would fit nicely on the rooms-search results list.

In Filament, add the form field in the Room Type resource to manage this photo.

In the search-rooms results, add the code for some placeholder thumbnail no-photo, if there's no photo uploaded for the room type.

9.

Create a BookingController with methods create() and store(). Replace
the "booking" route with create() method of this Controller.

Use te Requsts Class to make the validation

That method should return the Blade view with the form for the
booking, with layout almost identical to the welcome.blade.php, re-
using the same header/footer and background image component.

That form should contain fields: check-in and check-out date, number
of guests (all three disabled but with hidden input values), and then
non-disabled fields of customer name and email.

Also, somewhere on the page, there should be a calculation of how
many nights and how much price to pay, multiplying the date range by
the price per night. Specify on the page that the payment is made upon
arrival. Calculate the price in a specific PricingService, used in the
Controller.

Move the getNightsCount() from request into PricingService, and reuse the Service to replace the $request->getNightsCount()

Form action should be the store() method of the Controller, empty for
now, will build its code later.

10.

Fill in the store() method of BookingController, creating the
new Customer (firstOrCreate) with name and email, and
then creating the Booking assigned to that customer. Use
Laravel validation and database transaction.

For the Booking room_id, choose the first room for that
room_type_id, available within the requested dates. Make
that request outside of database transaction, if no room
found - return back with a validation error.

If booking successful, redirect to a new confirmation page.
Add the booking confirmation method in the Controller and
the Routes. It should just show text page, with the same
header, footer and background image as the
welcome.blade.php. In the middle section, it should just say
"Booking successful" and add a link to the homepage.

11.

If the booking is for 1 guest and no room with capacity = 1 is available, suggest a room with capacity > 1 and display a message like: "No rooms for 1 guest, but we found a larger room."

If the booking is for more than 1 guest and no single room can fit all guests (capacity >= guest_count), suggest booking multiple available rooms to fit the total number of guests. Display a message like: "No single room available for X guests. You can book multiple rooms to fit everyone."

Use clean Eloquent queries, modular if possible.

12.

When u show the Alternative Options Available
make a option in the end to book the rooms (this need to be a minimal guest opstions)

That go to create rooms with mulultiple rooms, same date, same customer just with more then 1 rooms book,

For multiple rooms, display the price per night for each room and calculate the total cost for all selected rooms based on the chosen dates.
Ensure that the total sum of the rooms' prices for the selected nights is calculated correctly and displayed.

Use clean Eloquent queries and ensure proper handling of booking logic, including the calculation of total pricing.

13.

I need to manage hotel fields: hotel name, email, phone number, address, and social links for facebook, instagram and twitter.

Use a spatie/laravel-settings for this - publish the config as needed

Also create a Filament page (not resource, just the edit page) with the form to manage the values of those fields. But first Filament Plugin: Spatie Settings

Finally, use the values of those fields in the footer for email chone addre dress and so social links

14.

Generate Pest tests for all the routes in the routes/web.php. Divide those tests into separate Pest files by their purpose. If some route has parameters, create different Pest methods forchecking various combinations of those parameters and expected results, including validation.

Use RefreshDatabase for all tests, but change phpunit.xml to use SQLite memory database.

15.

Make a Model for Contact with filed subject,email,message this contain Spaie media libraty
Create a contact page with same header and footer like search-room from component, register on web route, the form have a field email, subject, and message, and upload file (spatie media library) the form need to be a livewire component

Form has a rate limit of 5 contact per 1 hour

Add a Filament resouce just the list and view with nice looking infolist compoents

16.

Write a professional, well-structured README.md file for my project.
The README should follow best practices: include a Project Title, a friendly Introduction explaining what the project does and why it matters, a clear list of Features, detailed Installation Instructions, Usage examples with commands or code snippets, an optional Demo/Screenshot section, a list of Technologies Used, guidelines for Contributing, the License, and a Contact section.

Here's important information about my project:

- Project Name: Hotel-Booking

- Short description: [A 2-3 sentence intro]

- Main features: [List all the major features]

- Installation steps: [Leve this as a placeholder I deal with this]

- Technologies/libraries used: []

- Screenshots [Use the /screenshot folder on the root of the proejct to insert the image, order by the olders one]

- License: [MIT]
