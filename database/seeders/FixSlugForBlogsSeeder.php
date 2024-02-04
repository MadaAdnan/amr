<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Services;
use Illuminate\Database\Seeder;

class FixSlugForBlogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = Post::where('slug','!=',null)->get();
        foreach ($data as $value) {
           $blog = Post::find($value->id);
           $slug = $this->generate_slug($blog->slug);
           $serviceSlug = Services::where([['slug',$slug],['id','!=',$value->id]])->first();
           if($serviceSlug)
           {
            $slug = $slug.rand(1, 100);
           }
           $blog->update([
                'slug'=>$slug
           ]);
        }

        $data = Services::where('slug','!=',null)->get();
        foreach ($data as $value) {

           $services = Services::find($value->id);
           $slug = $this->generate_slug($services->slug);
           $serviceSlug = Services::where([['slug',$slug],['id','!=',$value->id]])->first();
           if($serviceSlug)
           {
            $slug = $slug.rand(1, 100);
           }
           $services->update([
                'slug'=>$slug
           ]);
        }
    }

    private function generate_slug($slug)
    {
        return preg_replace('/\s+/', '-', $slug);

    }
}
