# Web Sys Quiz 2 Writeup

## Thoughts and Approach

### Thoughts on IA and Developing Robust Privacy Systems
When designing the IA for this assignment, I recognized how the code could be split between client-side and server-side. 
Examples of client-side code includes login/registration portals and the ability to make or view projects. This information is accessed by the client to fulfill the site's functionality and purpose. However, this functionality cannot work without secure server-side code. 

auth.php is a great example of server-side code: It validates login/registration attempts with information that cannot be served to the client, ie existing credentials. This privacy separation keeps credentials safe and tracks which users interact with the application.

By partitioning the code in this manner, I can ensure clients have all the functionality they need to interact with the application while securing their information from potential threat actors.