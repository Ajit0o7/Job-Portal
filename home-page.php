<?php 
 include 'session.php';
?>
<?php include 'header.php'; ?>

        <section class="banner" style="padding-top: 140px; min-height: 85vh; padding-bottom: 8rem;">
            <div class="banner-content">
                <span class="badge-pill">🚀 The #1 Job Platform in Nepal</span>
                <h1 style="font-size: 3.8rem; line-height: 1.2; margin-bottom: 1.5rem; font-weight: 800;">Your <span class="text-gradient">Dream Job</span> Awaits</h1>
                <p style="font-size: 1.2rem; opacity: 0.9; margin-bottom: 2rem;">We specialize in connecting you with the most suitable job opportunities that align with your skills and expertise.</p>
                <div class="banner-buttons">
                    <a href="contact.php"><button class="btn-secondary rounded-pill">Contact Us</button></a>
                    <a href="jobseeker/jobseekerHome.php"><button class="btn-primary rounded-pill">Find Job</button></a>
                </div>
            </div>
            <div class="banner-image">
                <img src="img/job_portal_about.svg" alt="Job Search" style="width: 100%; max-width: 600px;">
            </div>
        </section>

        <!-- Floating Stats Bar -->
        <div class="stats-bar">
            <div class="stat-item">
                <h3>10k+</h3>
                <p>Active Jobs</p>
            </div>
            <div class="stat-item">
                <h3>500+</h3>
                <p>Top Companies</p>
            </div>
            <div class="stat-item">
                <h3>24/7</h3>
                <p>Dedicated Support</p>
            </div>
        </div>

        <div class="search_content" style="padding-top: 4rem;">
            <h2>What Are You Searching For?</h2>
        </div>

        <section class="category" style="padding-top: 2rem; padding-bottom: 5rem;">
            <div class="category-grid">
                <div class="buy glass-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                    <img class="buyimg" src="img/modern_job_seeker.png" alt="Find Job" style="width: 100%; height: 350px; object-fit: cover; border-bottom: 1px solid rgba(255,255,255,0.1); border-radius: 0;" />
                    <div class="buy-text-container" style="padding: 2.5rem; text-align: center;">
                        <p class="categoryfont" style="font-size: 1.8rem; color: var(--text-main); margin-bottom: 1rem;">Find me a job</p>
                        <p class="subfont" style="color: var(--text-muted); margin-bottom: 2rem;">
                            Empowering job seekers to find their perfect fit and make their career aspirations a reality. Let AI match you with top employers.
                        </p>
                        <a href="jobseeker/jobseekerHome" class="w-100"><button class="btn-primary rounded-pill w-100" style="padding: 12px; font-weight: 600;">Search Jobs</button></a>
                    </div>
                </div>
                
                <div class="buy glass-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                    <img class="buyimg" src="img/modern_employer.png" alt="Find Employee" style="width: 100%; height: 350px; object-fit: cover; border-bottom: 1px solid rgba(255,255,255,0.1); border-radius: 0;" />
                    <div class="buy-text-container" style="padding: 2.5rem; text-align: center;">
                        <p class="categoryfont" style="font-size: 1.8rem; color: var(--text-main); margin-bottom: 1rem;">Find me an employee</p>
                        <p class="subfont" style="color: var(--text-muted); margin-bottom: 2rem;">
                            Empower your organization with the right talent to drive innovation. Hire from a pool of thousands of verified professionals.
                        </p>
                        <a href="employer/employerHome" class="w-100"><button class="btn-primary rounded-pill w-100" style="padding: 12px; font-weight: 600;">Hire Talent</button></a>
                    </div>
                </div>  
            </div>
        </section>

        <section class="habt_section" style="background: var(--bg-color); padding: 5rem 10%;">
            <div class="habt_container">
                <div class="habt_image">
                    <img src="img/job-finding.webp" alt="Job Portal Image" style="border-radius: 24px; box-shadow: var(--shadow-lg);">
                </div>
                <div class="habt_content">
                    <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1.5rem;">Why Choose <span class="text-gradient">JobPortal?</span></h2>
                    <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 1rem;">Our job portal is designed to connect job seekers with the best career opportunities. Whether you are looking for your dream job or seeking candidates to fill your company’s vacancies, we offer a seamless experience tailored to your needs.</p>
                    
                    <div class="feature-blurbs">
                        <div class="feature-blurb"><i class="fas fa-check-circle"></i> Verified Employers</div>
                        <div class="feature-blurb"><i class="fas fa-bolt"></i> Instant Apply</div>
                        <div class="feature-blurb"><i class="fas fa-chart-line"></i> Career Tracking</div>
                        <div class="feature-blurb"><i class="fas fa-robot"></i> Smart AI Matching</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="quote" style="padding: 6rem 20px; background: radial-gradient(circle at center, rgba(59, 130, 246, 0.05) 0%, transparent 70%); border: none;">
            <div class="testimonial-card glass-card">
                <img src="https://ui-avatars.com/api/?name=W+A&background=0D8ABC&color=fff&size=128" alt="William Arthur Ward" class="testimonial-avatar">
                <i class="fas fa-quote-left testimonial-quote-icon"></i>
                <p class="quotedesign" style="font-size: 1.5rem; font-style: italic; color: var(--text-main); margin-bottom: 1.5rem; line-height: 1.8;">
                    "Gratitude can transform common days into thanksgivings, turn routine jobs into joy, and change ordinary opportunities into blessings."
                </p>
                <p class="quote-author" style="font-weight: 700; color: var(--primary-color); font-size: 1.1rem; margin-bottom: 0;">William Arthur Ward</p>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Renowned Author</p>
            </div>
        </section>

<?php include 'footer.php'; ?>