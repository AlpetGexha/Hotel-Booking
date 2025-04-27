<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateHotelSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('hotel.name', 'Hotel Driada');
        $this->migrator->add('hotel.email', 'agexha@gmail.com');
        $this->migrator->add('hotel.phone', '+383 44 567 631');
        $this->migrator->add('hotel.address', 'Wesley L. Clark Nr.53 Gjakova XK, 50000');
        $this->migrator->add('hotel.facebook', 'https://www.linkedin.com/in/alpet-gexha-499b071a3/');
        $this->migrator->add('hotel.instagram', 'https://www.linkedin.com/in/alpet-gexha-499b071a3/');
        $this->migrator->add('hotel.twitter', 'https://www.linkedin.com/in/alpet-gexha-499b071a3/');
        $this->migrator->add('hotel.linkedin', 'https://www.linkedin.com/in/alpet-gexha-499b071a3/');
        $this->migrator->add('hotel.github', 'https://github.com/AlpetGexha');
        $this->migrator->add('hotel.cv', 'https://drive.google.com/file/d/1_DJJ-VEHEJOel5Ot5IAPXZia-ma0YHSb/view?usp=sharing');
    }

    public function down(): void
    {
        $this->migrator->delete('hotel.name');
        $this->migrator->delete('hotel.email');
        $this->migrator->delete('hotel.phone');
        $this->migrator->delete('hotel.address');
        $this->migrator->delete('hotel.facebook');
        $this->migrator->delete('hotel.instagram');
        $this->migrator->delete('hotel.twitter');
        $this->migrator->delete('hotel.linkedin');
        $this->migrator->delete('hotel.github');
        $this->migrator->delete('hotel.cv');
    }
}
