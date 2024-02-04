<style>
    .news-app-promo {
  box-sizing: border-box;
  background-color: #fff;
  padding: 0.5em;
}
.title-app{
    font-weight: 600;
    color: #51171b;
}
.sub-title{
  color: #00000;
}

.promo-text{
  padding-bottom: 5%;
  padding-top: 1%;
}
.download-button-section{
  margin-bottom: 33px;
}
.download_qr {
    padding: 5px;
    background: #fff;
    border-radius: 5px;
    width: 100px;
    height:100px;
}
.apple_download_button{
    width: 190px;
    margin-left: 10%;
}
.mobile--pic {
   height: 400px;
   margin-bottom: -6px;
}
.paragraph-section{
  padding-top: 75px; 
  padding: 9px;
  
}

.large-screen{
  display: block;
}
.mobile__show{
  display: none;
}
@media (max-width: 1070px) {
            .mobile-hide {
                display: none;
            }
            .sub-title {
                font-size: 12px !important;
                text-align: center;
            }
            .title-app {
              font-size: 2em !important;
              text-align: center;
              font-weight: 500;
            }
            .download_qr{
              display: none;
            }
            .apple_download_button {
              width: 205px;
              margin-left: unset;
            }
            .download-button-section {
                margin-bottom: 33px;
                display: flex;
                justify-content: center;
            }
            .mobile__show{
              display: block;
            }
            .mobile-section-images{
              justify-content: unset !important;
              display: unset !important;
            }

            
        }
</style>
<div id="newsspec-19854-app" class="news-app-promo container">
  <div class="d-flex justify-content-between mobile-section-images">
    <div class="mb-4 paragraph-section">
        <div class="promo-text">
          <h1 class="title-app">An Opulent Ride Awaiting</h1>
          <p class="sub-title">Download the Lavish Ride app to book safe, private rides that redefines the art of travel.</p>
      </div>

      <div class="download-icons-sections text-left download-button-section mb-3">
        <div class="text-left">
          <div>
            <img src="{{ asset('assets_new/google_qrcode.png') }}" class="download_qr" alt="download">
            <a href="{{ config('general.ios_app_link') }}" target="_blank"><img class="apple_download_button" src="{{ asset('assets_new/apple-badge.png') }}" alt="download" ></a> 
          </div>
        </div>
    </div>
    
  </div>
  <div class="w-50 text-center mobile-hide">
    <img class="mobile--pic" src="{{ asset('iphone_mockup1.png') }}" alt="download the app">
  </div>
</div>
<div class="text-center mobile__show">
  <img class="mobile--pic" src="{{ asset('iphone_mockup1.png') }}" alt="download the app">
</div>

   

</div>