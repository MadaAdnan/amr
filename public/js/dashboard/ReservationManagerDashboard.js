class  ReservationManagerDashboard
{
    constructor(pickUpCords = null,dropOffCords = null,reservation = null) 
    {
      this.reservation = reservation;
      this.pickUpCords = pickUpCords;
      this.dropOffCords = dropOffCords;
      this.googlePlaceService = new google.maps.places.PlacesService(document.createElement('div'));
    }

    /**
     * Get Location
     *  
     * doc: return the location details throw google api 
     * 
     * @param String locationType (dropOff/pickUp)
     * @param String searchQuery Search in google api
     * 
     * 
     * @return Array of location's
     */

    async getLocation(locationType, searchQuery) {
      return new Promise((resolve, reject) => {
          this.googlePlaceService.textSearch(
              {
                  query: searchQuery,
              },
              (res, status) => {
                  if (res.length === 0 || status !== google.maps.places.PlacesServiceStatus.OK) {
                      //remove data according to the location type
                      if (locationType === 'pickUp') {
                          this.pickUpCords = null;
                      } else {
                          this.dropOffCords = null;
                      }
                      console.log('get locations: ', res);
                      resolve(null);
                  } else {
                      console.log('get locations: ', res);
                      resolve(res);
                  }
              }
          );
      });
  }
      
    

    

    

  }
  