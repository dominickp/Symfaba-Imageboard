# Symfaba Imageboard

This is a Symfony-based imageboard, modelled after Futaba style (http://en.wikipedia.org/wiki/Futaba_Channel) imageboards such as Futallaby, Wakaba, and Kusaba X. These types of forums are very simple threads of posts with optional images tied to replies. There is a basic user registration system but these style of imageboards allow users to post anonymously, so I have allowed thread and reply creation without the need for making an account.

### Notable features
- Thread abbreviations show only the most recent few replies if there are a bunch
- Thumbnails of large images to save on client page load time and server bandwidth
- Administrator access allows for deleting of posts and threads. Deleting a thread will delete all tied replies with the help of cascade.
- Login/authentication system is optional
- Nice pagination

### Javascript elements
- Clicking a reply image will expand to show the full size image. You can then click it again to toggle back to the thumbnmail.
- Clicking a reply number will add a response string to the reply box. A reply string starts with ">>".

### Todo
- Catalog mode
- Duplicate image detection with MD5
- Quoting with >
- Ban system

### Note to the grader
The majority of my code can be found in my bundle:
/src/Dominick/ImageboardBundle/

I had to scale back my plans for this project because I ran out of time, but I plan to continue it after the semester. I feel like I could have used more Javascript features but I had to make sure the core elements were working. Pagination and administration ended up taking a lot of time to figure out. You can login as an administrator/mod by using the login "administrator@example.com" with password "dwa152013".