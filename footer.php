<footer style="background-color: #0b1120; color: #cbd5e1; padding: 4rem 0 0 0; font-family: 'Inter', sans-serif;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem;">
        
        <!-- Brand & About -->
        <div>
            <a href="<?php echo BASE_URL; ?>/home-page.php"><img src="img/site-logo.png" alt="Job Portal Logo" style="height: 75px; margin-bottom: 1.5rem; filter: brightness(0) invert(1);"></a>
            <p style="line-height: 1.6; font-size: 0.95rem; color: #94a3b8;">
                Connecting top talent with the best employers. Start your career journey or find your perfect match with us today.
            </p>
        </div>
        
        <!-- Quick Links -->
        <div>
            <h4 style="color: #ffffff; font-size: 1.1rem; font-weight: 600; margin-bottom: 1.5rem;">Quick Links</h4>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.8rem;">
                <li><a href="<?php echo BASE_URL; ?>/home-page.php" style="color: #94a3b8; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#94a3b8'">Home</a></li>
                <li><a href="<?php echo BASE_URL; ?>/about.php" style="color: #94a3b8; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#94a3b8'">About Us</a></li>
                <li><a href="<?php echo BASE_URL; ?>/contact.php" style="color: #94a3b8; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#94a3b8'">Contact</a></li>
            </ul>
        </div>

        <!-- Portals -->
        <div>
            <h4 style="color: #ffffff; font-size: 1.1rem; font-weight: 600; margin-bottom: 1.5rem;">Portals</h4>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.8rem;">
                <li><a href="<?php echo BASE_URL; ?>/jobseeker/jobseekerHome.php" style="color: #94a3b8; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#94a3b8'">Job Seeker Hub</a></li>
                <li><a href="<?php echo BASE_URL; ?>/employer/employerHome.php" style="color: #94a3b8; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#94a3b8'">Employer Hub</a></li>
                <li><a href="<?php echo BASE_URL; ?>/admin/index.php" style="color: #94a3b8; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#94a3b8'">Admin Portal</a></li>
            </ul>
        </div>

        <!-- Contact Info -->
        <div>
            <h4 style="color: #ffffff; font-size: 1.1rem; font-weight: 600; margin-bottom: 1.5rem;">Contact Us</h4>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.8rem; color: #94a3b8; font-size: 0.95rem;">
                <li><i class="fas fa-phone" style="margin-right: 10px; color: #3b82f6;"></i> +1 234 567 890</li>
                <li><i class="fas fa-envelope" style="margin-right: 10px; color: #3b82f6;"></i> info@jobportal.com</li>
                <li><i class="fas fa-map-marker-alt" style="margin-right: 10px; color: #3b82f6;"></i> 123 Job Portal St, City</li>
            </ul>
        </div>

    </div>
    
    <!-- Copyright Bar -->
    <div style="border-top: 1px solid #1e293b; margin-top: 3rem; padding: 1.5rem 0; text-align: center; background-color: #0f172a;">
        <p style="margin: 0; color: #64748b; font-size: 0.9rem;">&copy; <?php echo date("Y"); ?> Job Portal. All rights reserved.</p>
    </div>
</footer>
</body>
</html>