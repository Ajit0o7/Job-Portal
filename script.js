// Sample job data
const jobs = [
    { title: 'Software Engineer', company: 'Tech Corp', location: 'New York', description: 'Develop software solutions.' },
    { title: 'Project Manager', company: 'Business Inc.', location: 'San Francisco', description: 'Lead projects from start to finish.' },
    { title: 'Web Developer', company: 'Web Solutions', location: 'Remote', description: 'Build and maintain websites.' },
];

// Function to display job listings
function displayJobs() {
    const jobListings = document.getElementById('job-listings');
    if(!jobListings) return;
    
    jobs.forEach(job => {
        const jobElement = document.createElement('div');
        jobElement.classList.add('job');
        jobElement.innerHTML = `
            <h4>${job.title}</h4>
            <p><strong>Company:</strong> ${job.company}</p>
            <p><strong>Location:</strong> ${job.location}</p>
            <p>${job.description}</p>
        `;
        jobListings.appendChild(jobElement);
    });
}

// Event listener for search button
const searchBtn = document.getElementById('search-button');
if(searchBtn) {
    searchBtn.addEventListener('click', () => {
        const searchInput = document.getElementById('job-search');
        if(!searchInput) return;
        
        const query = searchInput.value.toLowerCase();
        const filteredJobs = jobs.filter(job => job.title.toLowerCase().includes(query));
        const jobListings = document.getElementById('job-listings');
        jobListings.innerHTML = ''; // Clear existing listings
        
        filteredJobs.forEach(job => {
            const jobElement = document.createElement('div');
            jobElement.classList.add('job');
            jobElement.innerHTML = `
                <h4>${job.title}</h4>
                <p><strong>Company:</strong> ${job.company}</p>
                <p><strong>Location:</strong> ${job.location}</p>
                <p>${job.description}</p>
            `;
            jobListings.appendChild(jobElement);
        });
    });
}

// Load jobs on page load
window.onload = displayJobs;

/* --- UI Enhancements & Animations --- */
document.addEventListener('DOMContentLoaded', () => {
    // Add smooth reveal animation on scroll for elements with the .buy class (category cards)
    const cards = document.querySelectorAll('.buy');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = 1;
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    cards.forEach(card => {
        card.style.opacity = 0;
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease-out';
        observer.observe(card);
    });
    
    // Close mobile menu when clicking outside
    const menuToggle = document.getElementById('menu');
    document.addEventListener('click', (e) => {
        if(menuToggle && menuToggle.checked) {
            const nav = document.querySelector('nav ul');
            const menuBtn = document.querySelector('.menu-button');
            if(!nav.contains(e.target) && !menuBtn.contains(e.target)) {
                menuToggle.checked = false;
            }
        }
    });
});
