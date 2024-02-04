/**
* Lavish Ride
*
* @author @LavishRide
* @version 2.0
**/

/* ===================
Table Of Content
======================
01 Create IntTelInput
02 Add Custom Phone Validation
03 Strong Password Validation
04 Get Csrf Token
05 Fetch Data
06 Show selected image
====================== */



/**
 * 01 - Create IntTelInput
 * 
 * CreateIntlTelInput
 * 
 * @param {HTML INPUT} phoneInput 
 * 
 * @returns object
 */
function createIntlTelInput(phoneInput)
{
   //init the intlTelInput
   const init =  window.intlTelInput(phoneInput, {
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.15/build/js/utils.js",
        showSelectedDialCode:true,
        separateDialCode: true,
        preferredCountries:[
            'US'
        ]

    });

    //create custom validations
   addCustomPhoneValidation(init);

    return init;
}

/**
 * 02 - Add Custom Phone Validation
 * 
 * Add Custom Phone Validation
 * 
 * @param {HTML INPUT} input 
 * 
 * @return boolean
 */
function addCustomPhoneValidation(input)
{
    //phone validation
    return $.validator.addMethod("phoneValidation", function(value, element) {
        // Modify this regex pattern based on your phone number validation requirements
        const isValid = input.isValidNumber();
        return isValid;
    },"Please enter a valid phone number.");

}

/**
 * 03 - Strong Password Validation
 * 
 * Add Strong Password Validation
 * 
 */

function strongPasswordValidation()
{
   return $.validator.addMethod("strongPassword", function(value, element) {
        // Modify this regex pattern based on your password strength criteria
        var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return this.optional(element) || passwordPattern.test(value);
    }, "Password must contain at least 8 characters, including one uppercase letter, one lowercase letter, one number, and one special character.");
}

/**
 * 04 - Get Csrf Token
 * 
 * get the csrf token 
 * 
 */
function getCsrfToken() {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    return metaTag ? metaTag.content : null;
}


/**
 * 05 - Fetch Data
 * 
 * fetch data
 * 
 * doc: send get post request for the whole application
 * 
 */
function fetchData(url, method = 'GET', body = null) {
    return new Promise((resolve, reject) => {
        const csrfToken = getCsrfToken();

        // Check if CSRF token is available
        if (!csrfToken) {
            reject('CSRF token not found.');
            return;
        }

        fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: body ? JSON.stringify(body) : null,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => resolve(data))
        .catch(error => reject(error));
    });
}

/**
 * 06 - Show Selected Image Input
 * 
 * Show Selected Image Input
 * 
 * doc: show the selected image in the front end
 * 
 */

function showSelectedImageInput(event,id) {
    const image_url = URL.createObjectURL(event.target.files[0]);
    currentImageUrl = image_url;
    document.getElementById(id).src = image_url;
    return;
}
  




