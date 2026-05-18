    // language
    document.addEventListener('DOMContentLoaded', function() {
        const langButton = document.querySelector('.lang');
        if (langButton) {  // Check if the element exists
            langButton.addEventListener('click', function(e) {
                e.preventDefault();
                const dropdownMenu = this.nextElementSibling;
                dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
            });
        }
    
        // Close dropdown if clicked outside
        window.addEventListener('click', function(e) {
            if (!e.target.matches('.lang')) {
                const dropdowns = document.querySelectorAll('.dropdown-menu');
                dropdowns.forEach(function(dropdown) {
                    dropdown.style.display = 'none';
                });
            }
        });
    });


   

    
    function validateFields(fields) {
        for (const [key, value] of Object.entries(fields)) {
            if (!value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'שגיאה',
                    text: `אנא הזן את ${key === 'fullname' ? 'שמך' : key === 'email' ? 'האימייל שלך' : key === 'subject' ? 'הנושא' : 'ההודעה'}`,
                    confirmButtonText: 'אישור',
                    confirmButtonColor: '#fa65b1'
                });
                return false;
            }
        }
        return true; // All fields are valid
    }

    // Pull hidden attribution fields (rendered by includes/attribution-capture.php)
    function getAttribution() {
        const id = (x) => (document.getElementById(x) || {}).value || '';
        return {
            landing_page:      id('hlm_landing_page'),
            original_referrer: id('hlm_original_referrer'),
            utm_source:        id('hlm_utm_source'),
            utm_medium:        id('hlm_utm_medium'),
            utm_campaign:      id('hlm_utm_campaign'),
            utm_term:          id('hlm_utm_term'),
            utm_content:       id('hlm_utm_content'),
            search_query:      id('hlm_search_query'),
            hp_website:        (document.querySelector('[name="hp_website"]') || {}).value || ''
        };
    }

    async function lead(e) {
        e.preventDefault();

        const phoneEl = document.getElementById('phone');
        const fields = {
            fullname: document.getElementById('name').value.trim(),
            email:    document.getElementById('email').value.trim(),
            phone:    phoneEl ? phoneEl.value.trim() : '',
            subject:  document.getElementById('subject').value.trim(),
            message:  document.getElementById('message').value.trim(),
            location: await getUserLocation(),
            lead:     true,
            ...getAttribution()
        };

        // Require name + (email OR phone)
        if (!fields.fullname) {
            return Swal.fire({ icon: 'warning', title: 'שגיאה', text: 'אנא הזן את שמך', confirmButtonText: 'אישור', confirmButtonColor: '#fa65b1' });
        }
        if (!fields.email && !fields.phone) {
            return Swal.fire({ icon: 'warning', title: 'שגיאה', text: 'אנא הזן מייל או טלפון', confirmButtonText: 'אישור', confirmButtonColor: '#fa65b1' });
        }

        // GA4 conversion (tracked here, not just on thanks.php, so it fires
        // even if the user navigates away before the page loads)
        if (typeof window.trackLeadSubmit === 'function') {
            window.trackLeadSubmit({ source: fields.utm_source || 'organic' });
        }

        sendEmail(fields);
    }


    
    async function getUserLocation() {
        let location = "Unknown location"; 
    
        try {
          const response = await fetch('https://ipinfo.io/json?token=08515a51bbad4c');
          const data = await response.json();
          location = data.city || location;
        } catch (error) {
          console.log("Error fetching user location:", error);
        }
    
        return location;
    }

    async function quotation(e) {
        e.preventDefault();

        const location = await getUserLocation();

        const mail = document.getElementById('mail').value.trim();
    
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    
        if (!mail) {
            Swal.fire({
                icon: 'warning',
                title: 'שגיאה',
                text: 'אנא הזן את האימייל שלך',
                confirmButtonText: 'אישור',
                confirmButtonColor: '#fa65b1'
            });
            return false;
        }
    

        if (!emailPattern.test(mail)) {
            Swal.fire({
                icon: 'warning',
                title: 'שגיאה',
                text: 'אנא הזן אימייל תקין',
                confirmButtonText: 'אישור',
                confirmButtonColor: '#fa65b1'
            });
            return false;
        }
    
   
        const fields = {
            email: mail,
            location: location,
            quotation: true,
            ...getAttribution()
        };

        if (typeof window.trackLeadSubmit === 'function') {
            window.trackLeadSubmit({ source: fields.utm_source || 'organic' });
        }

        sendEmail(fields);
    }
    
    async function sendEmail(fields) {
   
        document.getElementById('loader').style.display = 'block';

        fetch('./server/function.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(fields) // Send the entire fields object
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            } else {
                return Swal.fire({
                    icon: 'success',
                    title: 'תודה',
                    text: '! המייל נשלח בהצלחה ',
                    confirmButtonText: 'אישור',
                    confirmButtonColor: '#fa65b1'
                });
            }
        })
        .then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'thanks.php';
            }
        })
        .catch(error => {
            // Handle error response here
            Swal.fire({
                icon: 'error',
                title: 'שגיאה',
                text: 'אירעה שגיאה בעת שליחת המייל',
                confirmButtonText: 'אישור',
                confirmButtonColor: '#fa65b1'
            });
        }) 
        .finally(() => {
            document.getElementById('loader').style.display = 'none';
        });
    }
    
    // nagish li 
    window.addEventListener('load', function() {
        let element = document.querySelector('#NagishLiBar');
        let elementStrip = document.querySelector('#NagishLiBarStrip');
        
        if (element) {
            element.style.removeProperty('inset');
        }

        if (elementStrip) {
            // Set background color with !important
            elementStrip.style.setProperty('background-color', '#a578bb', 'important');
            
            // Remove box-shadow
            elementStrip.style.setProperty('box-shadow', 'none', 'important'); 
            
            // Set border radius for the top right corner
            elementStrip.style.setProperty('border-top-right-radius', '10px', 'important');
        }
    });

    // Define the function that checks for 'active' class and updates styles
    function updateActiveLinkStyles() {
        const navLinks = document.querySelectorAll('.nav a');

        navLinks.forEach(link => {
            // Check if the 'active' class exists
            if (link.classList.contains('active')) {
                // Remove 'text-secondary' class from the link
                link.classList.remove('text-secondary');
                
                // Add inline color style
                link.style.color = '#fa65b1';

                // Remove 'text-secondary' class from the icon inside the link, if exists
                const icon = link.querySelector('i');
                if (icon) {
                    icon.classList.remove('text-secondary');
                }
            } else {
                // Reset color and classes for other links (optional)
                link.style.color = '';  // Reset to default color
                const icon = link.querySelector('i');
                if (icon) {
                    icon.classList.add('text-secondary');
                }
            }
        });
    }

    // Call updateActiveLinkStyles on any click in the nav bar
    document.querySelectorAll('.nav a').forEach(link => {
        link.addEventListener('click', function() {
            // Remove 'active' class from all links
            document.querySelectorAll('.nav a').forEach(link => link.classList.remove('active'));

            // Add 'active' class to the clicked link
            this.classList.add('active');
            
            // Update styles based on the active link
            updateActiveLinkStyles();
        });
    });

    // Initial call to set the correct styles on page load
    updateActiveLinkStyles();



