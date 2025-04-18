# Slideshow Implementation for Login and Signup Pages

This document provides instructions for the slideshow feature added to the login and signup pages.

## Files Added/Modified

- **New Files:**
  - `public/js/slideshow.js`: JavaScript code for the slideshow functionality
  - `public/css/slideshow.css`: CSS styling for the slideshow
  - `public/images/slideshow/placeholder.txt`: Instructions for adding slideshow images

- **Modified Files:**
  - `app/views/admin/login.php`: Added slideshow to admin login page
  - `app/views/client/login.php`: Added slideshow to client login page
  - `app/views/client/signup.php`: Added slideshow to client signup page

## How to Add Images

1. Create or place at least 3 images in the `public/images/slideshow/` directory
2. Name the images:
   - You can use the existing `bus3.jpg` from the parent directory for the first slide
   - Add `slide2.jpg` and `slide3.jpg` to the slideshow directory
3. Images should be approximately 800x500 pixels or maintain a similar widescreen ratio
4. The slideshow is already configured to use these image paths

## Testing the Slideshow

1. Navigate to the admin login page (/admin/login)
2. Navigate to the client login page (/home/login)
3. Navigate to the client signup page (/home/signup)
4. Verify that:
   - The slideshow transitions between images automatically every 4 seconds
   - The navigation dots at the bottom right work when clicked
   - The slideshow captions display properly
   - The slideshow is responsive and fits well on different screen sizes

## Customizing the Slideshow

### To change the transition timing:
- Open `public/js/slideshow.js`
- Modify the `setTimeout(showSlides, 4000);` line (4000ms = 4 seconds)

### To change the slideshow captions:
- Open the login/signup PHP files
- Modify the text in the `<div class="slideshow-text">...</div>` elements

### To add more slides:
1. Add more images to the slideshow directory
2. Add additional `slideshow-slide` divs to each PHP file
3. Add additional navigation dots
4. The slideshow will automatically include the new slides 