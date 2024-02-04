<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
            
  <sitemap>
    
      <loc>{{ route('reservations.choose_location') }}</loc>
      <lastmod>2023-07-08</lastmod>

</sitemap>
<sitemap>

  <loc>{{ route('user.forget_password') }}</loc>
  <lastmod>2023-07-08</lastmod>

</sitemap>
<sitemap>

  <loc>{{ route('frontEnd.blogs.index') }}</loc>
  <lastmod>2023-07-08</lastmod>
</sitemap>
<sitemap>

  <loc>{{ route('account.profile') }}</loc>
  <lastmod>2023-07-08</lastmod>

</sitemap>
<sitemap>

  <loc>{{ route('user.register') }}</loc>
  <lastmod>2023-07-08</lastmod>

</sitemap>
<sitemap>

  <loc>{{ route('fleet-category') }}</loc>
  <lastmod>2023-07-08</lastmod>

</sitemap>

<sitemap>

  <loc>{{ route('affiliate_application') }}</loc>
  <lastmod>2023-07-08</lastmod>

</sitemap>
<sitemap>

  <loc>{{ route('chauffeur_application') }}</loc>
  <lastmod>2023-07-08</lastmod>

</sitemap>
<sitemap>

  <loc>{{ route('frontEnd.about_us') }}</loc>
  <lastmod>2023-07-08</lastmod>

</sitemap>
<sitemap>

  <loc>{{ route('terms') }}</loc>
  <lastmod>2023-07-08</lastmod>

</sitemap>
<sitemap>

  <loc>{{  route('policy') }}</loc>
  <lastmod>2023-07-08</lastmod>

</sitemap>

<sitemap>
  <loc>{{ route('faqs') }}</loc>
  <lastmod>2023-07-08</lastmod>
</sitemap>

<sitemap>
  <loc>{{ route('contactForm') }}</loc>
  <lastmod>2023-07-08</lastmod>
</sitemap>

@foreach($fleets as $item)
    <sitemap>
        <loc>{{ route('fleet-category',$item->slug) }}</loc>
        <lastmod>{{ $item->created_at->format('Y-m-d') }}</lastmod>
    </sitemap>
@endforeach
@foreach($services as $item)
  <sitemap>
      <loc>{{ route('frontEnd.blogs.details',$item->slug) }}</loc>
      <lastmod>{{ $item->created_at->format('Y-m-d') }}</lastmod>
  </sitemap>
@endforeach

</sitemapindex>
