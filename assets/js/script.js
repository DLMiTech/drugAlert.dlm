let body = document.body;


let sideBar = document.querySelector('.side-bar');
document.querySelector('#menu-btn').onclick = () =>{
    sideBar.classList.toggle('active');
    body.classList.toggle('active');
}




function updateDateTimeAndGreeting() {
    // Get current date and time
    let now = new Date();

    // Format date (e.g., "14 January 2022")
    let date = now.toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' });

    // Format time (e.g., "22:45:04")
    let time = now.toLocaleTimeString('en-US', { hour12: true });

    // Update date and time in HTML
    document.getElementById('date').textContent = date;
    document.getElementById('time').textContent = time;

    // Get current hour
    let hour = now.getHours();

    // Determine greeting based on the current hour
    let greeting;
    if (hour >= 5 && hour < 12) {
        greeting = "Good Morning";
    } else if (hour >= 12 && hour < 18) {
        greeting = "Good Afternoon";
    } else {
        greeting = "Good Evening";
    }

    // Update greeting in HTML
    document.querySelector('.greeting').textContent = greeting;
}


// Initial call to update date, time, and greeting
updateDateTimeAndGreeting();

// Update date, time, and greeting every second
setInterval(updateDateTimeAndGreeting, 1000);



