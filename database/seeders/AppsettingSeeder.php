<?php

namespace Database\Seeders;

use App\Models\AppSettings;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = ['Home','Services','Services Type','Fleet','Fleet Category','About','Countries','Terms'];
        foreach ($settings as $key => $value) {
           $data = $this->create_setting_page($value);

           AppSettings::create($data);
        }
    }

    private function create_setting_page($type)
    {
        $inputs = [];
        if($type == 'Home')
        {
            $inputs = [
                'text'=>'Home',
                'value'=>json_encode([
                    'title_about_us'=>'',
                    'alt_image_one'=>'',
                    'image_one_title'=>'',
                    'image_one_description'=>'',
                    'alt_image_two'=>'',
                    'image_two_title'=>'',
                    'image_two_description'=>'',
                    'description_about_us'=>'',
                    'title_our_services'=>'',
                    'description_our_services'=>'',
                    'what_makes_us_title'=>'',
                    'what_makes_us_description'=>'',
                    'icon_what_makes_us_one_title'=>'',
                    'icon_what_makes_us_one_description'=>'',
                    'icon_what_makes_us_two_title'=>'',
                    'icon_what_makes_us_two_description'=>'',
                    'icon_what_makes_us_three_title'=>'',
                    'icon_what_makes_us_three_description'=>'',
                    'icon_what_makes_us_four_title'=>'',
                    'icon_what_makes_us_four_description'=>'',
                    'icon_what_makes_us_five_title'=>'',
                    'icon_what_makes_us_five_description'=>'',
                    'icon_what_makes_us_six_title'=>'',
                    'icon_what_makes_us_six_description'=>'',
                ])
            ];
        }
        if($type == 'Services')
        {
            $inputs = [
                'text'=>'Services',
                'value'=>json_encode([
                    
                ])
            ];
        }
        if($type == 'Services Type')
        {
            $inputs = [
                'text'=>'Services Type',
                'value'=>json_encode([
                    
                ])
            ];
        }
        if($type == 'Fleet')
        {
            $inputs = [
                'text'=>'Fleet',
                'value'=>json_encode([
                    'content'=>'te',
                    'fleetSeoHeader'=>''
                ])
            ];
        }
        if($type == 'Fleet Category')
        {
            $inputs = [
                'text'=>'Fleet',
                'value'=>json_encode([
                    
                ])
            ];
        }
        if($type == 'About')
        {
            $inputs = [
                'text'=>'About',
                'value'=>json_encode([
                    'section_one_title'=>'',
                    'section_one_description'=>'',
                    'section_one_paragraph_one_title'=>'',
                    'section_one_paragraph_one_description'=>'',
                    'section_one_paragraph_two_title'=>'',
                    'section_one_paragraph_two_description'=>'',
                    'section_two_title'=>'',
                    'section_two_description'=>'',
                    'section_two_paragraph_one_title'=>'',
                    'section_two_paragraph_one_description'=>'',
                    'section_two_paragraph_two_title'=>'',
                    'section_two_paragraph_two_description'=>'',
                    'section_three_title'=>'',
                    'section_three_description'=>'',
                ])
            ];
        }
        if($type == 'Countries')
        {
            $inputs = [
                'text'=>'Countries',
                'value'=>json_encode([
                    'content' => 'Content',
                    'seoTitleValue' =>'Seo Title',
                    'seoDescriptionValue' => 'Seo Description',
                    'seoKeyPhraseValue' => 'Seo Key Phrase Value',
                    'countrySeoHeader'=> 'countrySeoHeader',
                ])
            ];
        }

        if($type == 'Terms')
        {
            $inputs = [
                'text'=>'Terms',
                'value'=>json_encode([
                    'terms' => '',
                    'policy' =>'',
                ])
            ];
        }

        return $inputs;
    }

    
}
