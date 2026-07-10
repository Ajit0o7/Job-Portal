<?php 
session_start();

include '../database_configure.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="jobseeker-style.css?v=<?php echo time(); ?>"/>
<link rel="stylesheet" href="../main-styles.css?v=<?php echo time(); ?>"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<title>JobPortal Seekers Hub</title>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Mobile Responsiveness Overrides */
    @media (max-width: 768px) {
        .responsive-glass-card {
            padding: 1.5rem !important;
            margin: 2rem auto !important;
        }
        .responsive-chart-container {
            height: 400px !important;
        }
        .responsive-title {
            font-size: 1.8rem !important;
        }
    }
</style>
</head>
    <body>
    <div class="nav-container">
        <nav class="glass-nav">
            <div class="logo"> 
                <a href="<?php echo BASE_URL; ?>/home-page"><img src="../img/logo.png" alt="JobPortal Logo"></a>
            </div>
            
            <input type="checkbox" id="menu">
            <label for="menu" class="menu-button">
                <i class="fas fa-bars"></i>
            </label>
            
            <div class="nav-content">
                <ul class="nav-links">
                    <li><a href="<?php echo BASE_URL; ?>/jobseeker/jobseekerHome" class="nav-link active">Seekers Hub</a></li>      
                    <li><a href="joblist" class="nav-link">Find Job</a></li>
                    <li><a href="resumepage" class="nav-link">Resume Here</a></li>
                    <li><a href="salaryexpectation" class="nav-link">Expected Salary</a></li>
                </ul>
                
                <div class="nav-actions">
                    <?php if(!isset($_SESSION['username'])): ?>
                        <a href="signin-page" class="btn btn-primary rounded-pill px-4 fw-semibold emp-btn">Account</a>
                    <?php else: ?>
                        <a href="../logout" class="btn btn-outline-danger rounded-pill px-4 fw-semibold js-btn">Log out</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
        <!-- Modern Header Section -->
        <section class="banner">
            <div class="banner-content">
                <h1>Discover Your <span>Dream Job</span> with JobPortal</h1>
                <p>Empowering Job Seekers in the Competitive Market. Join our platform to connect with thousands of employers looking for your skills.</p>
                <div class="banner-buttons">
                    <a href="signup-page"><button class="btn-primary rounded-pill">Join Now</button></a>
                </div>
            </div>
            <div class="banner-image">
                <img src="../img/job-search-networking.jpg" alt="Job Search" style="border-radius: 20px; box-shadow: var(--shadow-lg); width: 100%; max-width: 550px; object-fit: cover;">
            </div>
        </section>
        <!-- Introduction Section -->
        <section class="habt_section">
            <div class="habt_container">
                <div class="habt_image">
                    <img src="../img/job_portal_about.svg" alt="Job Seeker">
                </div>
                <div class="habt_content">
                    <h2>Welcome to JobPortal</h2>
                    <p>Are you tired of endlessly searching through job boards, submitting applications, and never hearing back? Look no further! JobPortal is the ultimate job portal designed specifically for job seekers like you. We leverage intelligent matching to find your perfect fit.</p>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <div style="text-align: center; margin-bottom: 3rem; padding-top: 4rem;">
            <h2 style="font-size: 2.5rem; font-weight: 700; color: var(--text-main);">Why Start Using <span style="color: var(--primary-color);">JobPortal?</span></h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto 2rem;">Discover the features that make our platform the perfect companion for your career journey.</p>
        </div>

        <section style="max-width: 1200px; margin: 0 auto; padding: 0 5%; padding-bottom: 5rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <!-- Card 1 -->
                <div class="glass-card" style="padding: 2.5rem; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-md)';">
                    <div style="background: rgba(42, 157, 244, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <i class="fas fa-brain" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                    </div>
                    <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-main);">Intelligent Job Matching</h3>
                    <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 0;">Find opportunities that perfectly align with your unique skills, experience, and career goals using our smart algorithms.</p>
                </div>
                <!-- Card 2 -->
                <div class="glass-card" style="padding: 2.5rem; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-md)';">
                    <div style="background: rgba(42, 157, 244, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <i class="fas fa-file-alt" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                    </div>
                    <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-main);">Comprehensive Details</h3>
                    <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 0;">Get transparent, detailed information on job roles, requirements, salary expectations, and company culture before you apply.</p>
                </div>
                <!-- Card 3 -->
                <div class="glass-card" style="padding: 2.5rem; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-md)';">
                    <div style="background: rgba(42, 157, 244, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <i class="fas fa-paper-plane" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                    </div>
                    <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-main);">1-Click Application</h3>
                    <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 0;">Say goodbye to tedious forms. Build your resume once and apply to hundreds of jobs instantly with our simplified 1-click apply feature.</p>
                </div>
                <!-- Card 4 -->
                <div class="glass-card" style="padding: 2.5rem; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-md)';">
                    <div style="background: rgba(42, 157, 244, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <i class="fas fa-user-check" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                    </div>
                    <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-main);">Tailored Alerts</h3>
                    <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 0;">Never miss an opportunity. Receive personalized job recommendations and instant alerts the moment a matching role is posted.</p>
                </div>
                <!-- Card 5 -->
                <div class="glass-card" style="padding: 2.5rem; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-md)';">
                    <div style="background: rgba(42, 157, 244, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <i class="fas fa-chart-line" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                    </div>
                    <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-main);">Market Insights</h3>
                    <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 0;">Stay ahead of the curve. Access real-time data on the most demanded skills and industry trends to strategically plan your career growth.</p>
                </div>
                <!-- Card 6 -->
                <div class="glass-card" style="padding: 2.5rem; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-md)';">
                    <div style="background: rgba(42, 157, 244, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <i class="fas fa-users" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                    </div>
                    <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-main);">Premium Network</h3>
                    <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 0;">Connect with a diverse community of verified professionals, industry leaders, and expert career coaches to expand your network.</p>
                </div>
            </div>
        </section>


        <div class="glass-card responsive-glass-card" style="margin: 4rem auto; max-width: 1200px; padding: 3rem; text-align: center;">
            <h2 class="responsive-title" style="font-size: 2.2rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem;">Let's See Which Skill Is More Demanded</h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 3rem; max-width: 800px; margin-left: auto; margin-right: auto;">Analyze the skills currently in demand based on job postings data. The chart below showcases the most sought-after skills in the job market today.</p>
            <div id="skillsChart" class="responsive-chart-container" style="width: 100%; height: 500px; border-radius: var(--border-radius); overflow: hidden; box-shadow: var(--shadow-sm);"></div>
        </div>

        <?php
    $sql = "SELECT * FROM job_postings";
    $result = $conn->query($sql);

    $skillsDemand = []; // Array to store skills and their demand

    if ($result->num_rows > 0) {
        while ($job = $result->fetch_assoc()) {
            $jobSkills = explode(', ', $job['skills']);
            
            foreach ($jobSkills as $skill) {
                if (!isset($skillsDemand[$skill])) {
                    $skillsDemand[$skill] = 1;
                } else {
                    $skillsDemand[$skill]++;
                }
            }
        }
    } else {
        echo "<p>No job postings found in the database.</p>";
    }

    // Sort skillsDemand array in descending order of demand
    arsort($skillsDemand);

    // Get only the top 6 demanded skills
    $topSkills = array_slice($skillsDemand, 0, 6, true);
?>

<!-- Example using Google Charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Skill');
        data.addColumn('number', 'Demand');

        data.addRows([
            <?php
            foreach ($topSkills as $skill => $demand) {
                echo "['$skill', $demand],";
            }
            ?>
        ]);

        var options = {
            title: 'Top Skills Demand Chart',
            backgroundColor: 'transparent',
            colors: ['#4CAF50'],
            hAxis: {
                title: 'Demand',
                minValue: 0,
                textStyle: {
                    color: '#333',
                    fontSize: 12,
                },
                titleTextStyle: {
                    color: '#333',
                    fontSize: 14,
                },
                gridlines: {
                    color: 'transparent',
                }
            },
            vAxis: {
                textStyle: {
                    fontSize: 12,
                    color: '#333',
                },
                titleTextStyle: {
                    fontSize: 14,
                    color: '#333',
                },
            },
            chartArea: {
                backgroundColor: '#fff',
                width: window.innerWidth < 768 ? '50%' : '70%',
                height: '80%',
            },
            bar: { groupWidth: '60%' },
            legend: {
                position: 'top',
                alignment: 'center',
                textStyle: {
                    color: '#4CAF50',
                    fontSize: window.innerWidth < 768 ? 12 : 14,
                }
            },
            tooltip: {
                textStyle: {
                    fontSize: 13,
                },
                isHtml: true,
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('skillsChart'));
        chart.draw(data, options);
        
        // Redraw chart dynamically on window resize to ensure responsiveness
        window.addEventListener('resize', function() {
            // Update dynamic options on resize
            options.chartArea.width = window.innerWidth < 768 ? '50%' : '70%';
            options.legend.textStyle.fontSize = window.innerWidth < 768 ? 12 : 14;
            chart.draw(data, options);
        });
    }
</script>
    <!-- Call to Action Section -->
    <section class="habt_section" style="text-align: center; padding-top: 0;">
        <div class="habt_container" style="display: block; max-width: 800px; margin: 0 auto;">
            <h2 style="font-size: 3rem; color: var(--text-main); margin-bottom: 1.5rem;">Lets Find <span style="color: var(--primary-color);">Job</span></h2>
            <p style="font-size: 1.2rem; color: var(--text-muted); margin-bottom: 2.5rem;">Join JobPortal today and unlock a world of possibilities to propel your career forward!</p>
            <div class="banner-buttons" style="justify-content: center;">
                <a href="../contact"><button class="btn-primary rounded-pill">Contact Us</button></a>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>