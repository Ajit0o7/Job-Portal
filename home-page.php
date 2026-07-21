<?php 
 include 'session.php';
?>
<?php include 'header.php'; ?>

<!-- 2. Hero Section (Action-Oriented) -->
<section class="banner" style="padding-top: 140px; min-height: 70vh; padding-bottom: 5rem; justify-content: center; text-align: center; flex-direction: column; padding-left: 20px; padding-right: 20px;">
    <div class="banner-content" style="max-width: 800px; margin: 0 auto; text-align: center;">
        <span class="badge-pill" style="margin: 0 auto 1.5rem auto;">🚀 The #1 Job Platform in Nepal</span>
        <h1 style="font-size: 3.8rem; line-height: 1.2; margin-bottom: 1.5rem; font-weight: 800;">Your <span class="text-gradient">Dream Job</span> Awaits</h1>
        <p style="font-size: 1.2rem; opacity: 0.9; margin-bottom: 3rem;">We specialize in connecting you with the most suitable job opportunities that align with your skills and expertise.</p>
        
        <!-- Search Bar -->
        <form action="jobseeker/joblist.php" method="GET" class="hero-search-bar" style="display: flex; background: rgba(255,255,255,0.9); padding: 8px; border-radius: 50px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 3rem; align-items: center; max-width: 600px; margin-left: auto; margin-right: auto;">
            <div style="flex: 1; display: flex; align-items: center; padding: 10px 20px;">
                <i class="fas fa-search text-muted" style="margin-right: 10px;"></i>
                <input type="text" name="keyword" placeholder="Job Title, Skill, or Keyword..." style="border: none; outline: none; background: transparent; width: 100%; font-size: 1.1rem; color: var(--text-main);">
            </div>
            <button type="submit" class="btn btn-primary rounded-pill" style="padding: 12px 30px; font-size: 1.1rem; border: none; white-space: nowrap;">Search</button>
        </form>

        <!-- Stats -->
        <div style="display: flex; justify-content: center; gap: 4rem; margin-top: 2rem; flex-wrap: wrap;">
            <div style="text-align: center;">
                <h3 style="font-size: 2.2rem; font-weight: 800; color: var(--text-main); margin-bottom: 0;">10k+</h3>
                <p style="color: var(--text-muted); font-size: 1rem;">Active Jobs</p>
            </div>
            <div style="text-align: center;">
                <h3 style="font-size: 2.2rem; font-weight: 800; color: var(--text-main); margin-bottom: 0;">500+</h3>
                <p style="color: var(--text-muted); font-size: 1rem;">Top Companies</p>
            </div>
            <div style="text-align: center;">
                <h3 style="font-size: 2.2rem; font-weight: 800; color: var(--text-main); margin-bottom: 0;">24/7</h3>
                <p style="color: var(--text-muted); font-size: 1rem;">Support</p>
            </div>
        </div>
    </div>
</section>

<!-- 3. Social Proof -->
<section style="background: var(--bg-alt); padding: 2.5rem 20px; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); text-align: center;">
    <p style="color: var(--text-muted); font-size: 0.95rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1.5rem;">Trusted by top companies worldwide</p>
    <div style="display: flex; justify-content: center; gap: 2.5rem; align-items: center; flex-wrap: wrap; opacity: 0.6; filter: grayscale(100%);">
        <i class="fab fa-google" style="font-size: 2.5rem; color: var(--text-main);"></i>
        <i class="fab fa-microsoft" style="font-size: 2.5rem; color: var(--text-main);"></i>
        <i class="fab fa-amazon" style="font-size: 2.5rem; color: var(--text-main);"></i>
        <i class="fab fa-apple" style="font-size: 2.5rem; color: var(--text-main);"></i>
        <i class="fab fa-meta" style="font-size: 2.5rem; color: var(--text-main);"></i>
        <i class="fab fa-stripe" style="font-size: 2.5rem; color: var(--text-main);"></i>
    </div>
</section>

<!-- 5. Featured Jobs -->
<section style="padding: 4rem 10% 6rem 10%; background: var(--bg-alt);">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 2.5rem; font-weight: 700; color: var(--text-main);">Featured <span class="text-gradient">Jobs</span></h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; margin-top: 1rem; margin-bottom: 0;">Hand-picked opportunities from top employers.</p>
        </div>
        <a href="jobseeker/joblist.php"><button class="btn btn-outline-primary rounded-pill px-4" style="border: 1px solid var(--primary-color);">View All Jobs <i class="fas fa-arrow-right" style="margin-left: 8px;"></i></button></a>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2rem;">
        <?php
        include 'database_configure.php';
        $sql = "SELECT p.job_id, p.title, p.location, p.salary, p.status, e.Fullname_E 
                FROM job_postings p
                JOIN employerlogin e ON p.employer_id = e.employer_id
                WHERE p.status = 'open' 
                ORDER BY p.date_posted DESC 
                LIMIT 3";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $job_id = $row['job_id'];
                $title = htmlspecialchars($row['title']);
                $company = htmlspecialchars($row['Fullname_E']);
                $location = htmlspecialchars($row['location']);
                $salary = htmlspecialchars($row['salary']);
                ?>
                <div class="glass-card" style="display: flex; flex-direction: column; justify-content: space-between; padding: 2rem;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                            <div style="width: 50px; height: 50px; background: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                                <i class="fas fa-briefcase" style="font-size: 1.8rem; color: var(--primary-color);"></i>
                            </div>
                            <span style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Open</span>
                        </div>
                        <h3 style="font-size: 1.4rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem;"><?php echo $title; ?></h3>
                        <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1rem;"><i class="fas fa-building" style="margin-right: 5px;"></i> <?php echo $company; ?> &nbsp; <i class="fas fa-map-marker-alt" style="margin-left: 10px; margin-right: 5px;"></i> <?php echo $location; ?></p>
                        <p style="font-weight: 600; color: var(--text-main); margin-bottom: 1.5rem;"><i class="fas fa-money-bill-wave text-muted" style="margin-right: 5px;"></i> RS. <?php echo number_format($salary); ?> / month</p>
                    </div>
                    <a href="jobseeker/job/<?php echo $job_id; ?>/<?php echo urlencode(strtolower(str_replace(' ', '-', $title))); ?>" class="w-100"><button class="btn btn-primary rounded-pill w-100" style="padding: 10px; border: none;">Apply Now</button></a>
                </div>
                <?php
            }
        } else {
            echo "<p style='color: var(--text-muted);'>No featured jobs available at the moment.</p>";
        }
        ?>
    </div>
</section>

<!-- 6. Employer Pitch -->
<section id="companies" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 6rem 10%; text-align: center; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; left: -50px; width: 200px; height: 200px; background: rgba(59, 130, 246, 0.2); border-radius: 50%; filter: blur(50px);"></div>
    <div style="position: absolute; bottom: -50px; right: -50px; width: 300px; height: 300px; background: rgba(139, 92, 246, 0.2); border-radius: 50%; filter: blur(60px);"></div>
    
    <div style="position: relative; z-index: 2; max-width: 800px; margin: 0 auto;">
        <h2 style="font-size: 2.8rem; font-weight: 800; color: #ffffff; margin-bottom: 1.5rem;">Are you an employer looking for <span style="color: #60a5fa;">top talent?</span></h2>
        <p style="color: #94a3b8; font-size: 1.2rem; margin-bottom: 3rem;">We use Smart AI Matching to connect you with verified professionals instantly. Skip the endless screening and hire the best candidates faster.</p>
        <a href="employer/employerHome.php"><button class="btn btn-primary rounded-pill" style="padding: 15px 40px; font-size: 1.1rem; box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4); border: none;">Post a Job Now</button></a>
    </div>
</section>

<!-- 7. Testimonials -->
<section style="padding: 6rem 10%; background: var(--bg-color);">
    <div style="text-align: center; margin-bottom: 4rem;">
        <h2 style="font-size: 2.5rem; font-weight: 700; color: var(--text-main);">Success <span class="text-gradient">Stories</span></h2>
        <p style="color: var(--text-muted); font-size: 1.1rem; margin-top: 1rem;">Don't just take our word for it.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2.5rem;">
        <!-- Review 1 -->
        <div class="glass-card" style="padding: 2.5rem; position: relative;">
            <i class="fas fa-quote-right" style="position: absolute; top: 2rem; right: 2.5rem; font-size: 3rem; color: rgba(59, 130, 246, 0.1);"></i>
            <div style="display: flex; gap: 4px; color: #f59e0b; margin-bottom: 1.5rem; font-size: 1.1rem;">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p style="color: var(--text-main); font-size: 1.1rem; line-height: 1.7; margin-bottom: 2rem; font-style: italic;">"I had been looking for a mid-level frontend role for months. Within two days of setting up my profile on JobPortal, I was matched with an amazing tech startup. The AI matching is remarkably accurate."</p>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <img src="https://ui-avatars.com/api/?name=Sarah+J&background=3b82f6&color=fff" alt="Sarah J." style="width: 50px; height: 50px; border-radius: 50%;">
                <div>
                    <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 0;">Sarah Jenkins</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0;">Frontend Developer</p>
                </div>
            </div>
        </div>
        
        <!-- Review 2 -->
        <div class="glass-card" style="padding: 2.5rem; position: relative;">
            <i class="fas fa-quote-right" style="position: absolute; top: 2rem; right: 2.5rem; font-size: 3rem; color: rgba(59, 130, 246, 0.1);"></i>
            <div style="display: flex; gap: 4px; color: #f59e0b; margin-bottom: 1.5rem; font-size: 1.1rem;">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p style="color: var(--text-main); font-size: 1.1rem; line-height: 1.7; margin-bottom: 2rem; font-style: italic;">"As a growing marketing agency, finding verified talent fast is our biggest bottleneck. We posted a role here and hired a phenomenal copywriter within a week. The applicant quality is top-tier."</p>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <img src="https://ui-avatars.com/api/?name=David+C&background=10b981&color=fff" alt="David C." style="width: 50px; height: 50px; border-radius: 50%;">
                <div>
                    <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 0;">David Chen</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0;">Hiring Manager, CreativeEdge</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>