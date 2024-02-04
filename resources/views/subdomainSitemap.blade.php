<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    
    
            @foreach($categories as $item)
            <sitemap>
                
                    <loc>{{ route('frontEnd.blogs.category',$item->slug) }}</loc>
                    <lastmod>{{ $item->created_at->format('Y-m-d') }}</lastmod>

            </sitemap>
        @endforeach
        @foreach($data as $item)
        <sitemap>
            
                <loc>{{ route('frontEnd.blogs.details',$item->slug) }}</loc>
                <lastmod>{{ $item->date->format('Y-m-d') }}</lastmod>
        </sitemap>
        @endforeach

</sitemapindex>

