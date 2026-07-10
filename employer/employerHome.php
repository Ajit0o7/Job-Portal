<?php 
session_start();
include '../database_configure.php';
$eid12 = isset($_SESSION['username']) ? $_SESSION['username'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../main-styles.css?v=<?php echo time(); ?>"/>
    <title>JobPortal - Employer Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .google-visualization-title { font-size: 26px !important; font-weight: 800 !important; font-family: 'Inter', sans-serif !important; fill: var(--text-main) !important; }
        .google-visualization-subtitle { font-size: 16px !important; font-family: 'Inter', sans-serif !important; fill: var(--text-muted) !important; }
        svg > g > text[text-anchor="middle"] { font-family: 'Inter', sans-serif !important; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-up {
            opacity: 0;
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }
        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }

        .hover-lift {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .hover-lift:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            border-color: var(--primary-color);
        }

        .bg-orb {
            position: absolute;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(42, 157, 244, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
            top: -200px;
            right: -200px;
        }
        .bg-orb-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(46, 204, 113, 0.05) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
            bottom: 10%;
            left: -150px;
        }

        .brand-strip {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 4rem;
            padding: 3rem 0;
            opacity: 0.4;
            filter: grayscale(100%);
            flex-wrap: wrap;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            background: rgba(255,255,255,0.4);
        }
        .brand-strip i {
            font-size: 2.8rem;
            color: var(--text-main);
            transition: all 0.3s ease;
        }
        .brand-strip i:hover {
            opacity: 1;
            filter: grayscale(0%);
            color: var(--primary-color);
            transform: scale(1.1);
        }

        /* Mobile Responsiveness Overrides */
        @media (max-width: 768px) {
            .responsive-glass-card {
                padding: 1.5rem !important;
            }
            .responsive-chart-container {
                padding: 0.5rem !important;
                height: 450px !important;
            }
            .responsive-title {
                font-size: 2rem !important;
            }
        }
    </style>
</head>
<body style="position: relative; overflow-x: hidden;">
    <div class="bg-orb"></div>
    <div class="bg-orb-2"></div>

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
                    <li><a href="<?php echo BASE_URL; ?>/employer/employerHome" class="nav-link active">Employer Hub</a></li>      
                    <li><a href="listjob" class="nav-link">List Out Job</a></li>
                    <?php if($eid12): ?>
                        <li><a href="manage-jobs" class="nav-link">Update Job</a></li>
                        <li><a href="checkapplication" class="nav-link">Listed Jobs</a></li>
                    <?php endif; ?>
                </ul>
                
                <div class="nav-actions">
                    <?php if(!$eid12): ?>
                        <a href="signin-page" class="btn btn-primary rounded-pill px-4 fw-semibold emp-btn">Sign in</a>
                    <?php else: ?>
                        <a href="../logout" class="btn btn-outline-danger rounded-pill px-4 fw-semibold js-btn">Log out</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>

    <!-- Modern Header Section -->
    <section class="banner animate-up" style="padding-top: 160px; min-height: 85vh; padding-bottom: 5rem; justify-content: center; text-align: left; position: relative; z-index: 1;">
        <div class="banner-content" style="flex: 1; padding-right: 2rem;">
            <span class="badge-pill" style="margin-bottom: 1.5rem; display: inline-flex; align-items: center; gap: 8px; border: 1px solid rgba(42, 157, 244, 0.3); background: rgba(255,255,255,0.7); backdrop-filter: blur(10px);">
                <i class="fas fa-rocket" style="color: var(--primary-color);"></i> Next-Gen Hiring Platform
            </span>
            <h1 style="font-size: 4.5rem; font-weight: 900; margin-bottom: 1.5rem; line-height: 1.1; letter-spacing: -1px;">
                Hire the <span class="text-gradient">Top 1%</span><br> of Global Talent
            </h1>
            <div class="banner-buttons" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="listjob" style="text-decoration: none;">
                    <button class="btn btn-primary rounded-pill" style="padding: 16px 45px; font-size: 1.15rem; box-shadow: 0 10px 20px rgba(42, 157, 244, 0.3); transition: transform 0.2s;">
                        <i class="fas fa-plus-circle" style="margin-right: 8px;"></i> Post a Job Now
                    </button>
                </a>
                <?php if(!$eid12): ?>
                    <a href="signup-page" style="text-decoration: none;">
                        <button class="btn btn-secondary rounded-pill" style="padding: 16px 45px; font-size: 1.15rem; background: white; border: 1px solid var(--border-color); color: var(--text-main);">
                            <i class="fas fa-arrow-right" style="margin-right: 8px;"></i> Get Started Free
                        </button>
                    </a>
                <?php endif; ?>
            </div>
            
            <div style="margin-top: 3rem; display: flex; gap: 2rem; color: var(--text-muted); font-size: 0.95rem; font-weight: 600;">
                <div><i class="fas fa-check-circle" style="color: #2ecc71;"></i> No credit card required</div>
                <div><i class="fas fa-check-circle" style="color: #2ecc71;"></i> Instant applicant matching</div>
            </div>
        </div>
        <div class="banner-image animate-up delay-1" style="flex: 1; text-align: right; position: relative;">
            <div style="position: absolute; top: -20px; right: -20px; width: 100%; height: 100%; background: var(--primary-color); border-radius: 30px; opacity: 0.1; transform: rotate(3deg); z-index: -1;"></div>
            <img src="../img/modern_employer.png" alt="Employer Search" style="width: 100%; max-width: 650px; object-fit: cover; border-radius: 30px; box-shadow: 0 30px 60px rgba(0,0,0,0.12); border: 1px solid rgba(255,255,255,0.5);">
        </div>
    </section>

    <!-- Trusted Brands Strip -->
    <div class="brand-strip animate-up delay-2">
        <span style="font-weight: 700; font-size: 1.1rem; color: var(--text-muted); letter-spacing: 1px; text-transform: uppercase;">Trusted by Top Teams:</span>
        <i class="fab fa-google"></i>
        <i class="fab fa-amazon"></i>
        <i class="fab fa-microsoft"></i>
        <i class="fab fa-airbnb"></i>
        <i class="fab fa-stripe"></i>
    </div>

    <!-- Features Section -->
    <div style="background: linear-gradient(to bottom, transparent, rgba(246, 248, 253, 0.8)); padding-top: 7rem; padding-bottom: 7rem; position: relative;">
        <div style="text-align: center; margin-bottom: 5rem;" class="animate-up">
            <span class="badge-pill" style="background: rgba(42, 157, 244, 0.1); color: var(--primary-color);">Why JobPortal?</span>
            <h2 style="font-size: 3rem; font-weight: 900; color: var(--text-main); margin-top: 1rem;">Everything you need to hire at scale</h2>
        </div>

        <section class="category" style="padding: 0 10%; max-width: 1400px; margin: 0 auto;">
            <div class="category-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 3rem;">
                
                <div class="buy glass-card hover-lift animate-up delay-1" style="padding: 3rem; border-radius: 24px; background: rgba(255,255,255,0.7);">
                    <div style="width: 70px; height: 70px; background: linear-gradient(135deg, rgba(42, 157, 244, 0.2), rgba(42, 157, 244, 0.05)); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; border: 1px solid rgba(42, 157, 244, 0.2);">
                        <i class="fas fa-brain" style="color: var(--primary-color); font-size: 2rem;"></i>
                    </div>
                    <h3 style="color: var(--text-main); font-size: 1.6rem; font-weight: 800; margin-bottom: 1rem;">Intelligent AI Matching</h3>
                    <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.7; margin-bottom: 0;">Our proprietary algorithm parses candidate skills and intelligently ranks them against your job requirements instantly.</p>
                </div>
                
                <div class="buy glass-card hover-lift animate-up delay-2" style="padding: 3rem; border-radius: 24px; background: rgba(255,255,255,0.7);">
                    <div style="width: 70px; height: 70px; background: linear-gradient(135deg, rgba(46, 204, 113, 0.2), rgba(46, 204, 113, 0.05)); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; border: 1px solid rgba(46, 204, 113, 0.2);">
                        <i class="fas fa-id-card" style="color: #2ecc71; font-size: 2rem;"></i>
                    </div>
                    <h3 style="color: var(--text-main); font-size: 1.6rem; font-weight: 800; margin-bottom: 1rem;">Deep Candidate Insights</h3>
                    <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.7; margin-bottom: 0;">Access highly detailed candidate profiles, complete with verified work histories, portfolio links, and skill assessments.</p>
                </div>
                
                <div class="buy glass-card hover-lift animate-up delay-3" style="padding: 3rem; border-radius: 24px; background: rgba(255,255,255,0.7);">
                    <div style="width: 70px; height: 70px; background: linear-gradient(135deg, rgba(243, 156, 18, 0.2), rgba(243, 156, 18, 0.05)); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; border: 1px solid rgba(243, 156, 18, 0.2);">
                        <i class="fas fa-chart-pie" style="color: #f39c12; font-size: 2rem;"></i>
                    </div>
                    <h3 style="color: var(--text-main); font-size: 1.6rem; font-weight: 800; margin-bottom: 1rem;">Real-Time Market Data</h3>
                    <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.7; margin-bottom: 0;">Make competitive salary offers and targeted hiring decisions based on live analytics pulled directly from active job seekers.</p>
                </div>
            </div>
        </section>
    </div>

    <!-- Live Market Data Section (Google Chart) -->
    <div style="background: var(--bg-color); padding-top: 5rem; padding-bottom: 8rem; position: relative;">
        <div class="glass-card animate-up responsive-glass-card" style="margin: 0 auto; max-width: 1200px; padding: 4rem; text-align: center; border-radius: 30px; box-shadow: 0 40px 80px rgba(0,0,0,0.08); background: rgba(255,255,255,0.9); backdrop-filter: blur(20px);">
            <span class="badge-pill" style="margin-bottom: 1.5rem; background: rgba(231, 76, 60, 0.1); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.2);"><i class="fas fa-fire"></i> Live Data Analytics</span>
            <h2 class="responsive-title" style="font-size: 3rem; font-weight: 900; color: var(--text-main); margin-bottom: 1.5rem; letter-spacing: -1px;">Most Sought-After Skills</h2>
            <p style="color: var(--text-muted); font-size: 1.2rem; margin-bottom: 4rem; max-width: 800px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                Analyze the skills currently in demand across our entire platform based on active job seekers. Use these insights to optimize your job listings and stay ahead of market trends.
            </p>
            <div id="skillsChart" class="responsive-chart-container" style="width: 100%; height: 550px; border-radius: 20px; overflow: hidden; background: white; border: 1px solid var(--border-color); padding: 2rem; box-shadow: inset 0 2px 10px rgba(0,0,0,0.02);"></div>
        </div>
    </div>

    <?php
    $sql = "SELECT skill FROM seekerresume WHERE skill IS NOT NULL AND skill != ''";
    $result = $conn->query($sql);
    $skillsDemand = [];

    if ($result->num_rows > 0) {
        while ($job = $result->fetch_assoc()) {
            $jobSkills = array_filter(array_map('trim', preg_split("/[\r\n,]+/", $job['skill'])));
            
            foreach ($jobSkills as $skill) {
                if ($skill === "") continue;
                $skill = ucwords(strtolower($skill));
                if (!isset($skillsDemand[$skill])) {
                    $skillsDemand[$skill] = 1;
                } else {
                    $skillsDemand[$skill]++;
                }
            }
        }
    }
    $conn->close();

    arsort($skillsDemand);
    $skillsDemand = array_slice($skillsDemand, 0, 10, true);
    ?>

    <!-- Google Charts Integration -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Skill');
            data.addColumn('number', 'Candidates with Skill');
            data.addColumn({type: 'string', role: 'style'});

            data.addRows([
                <?php
                $colors = ['#2a9df4', '#1171ba', '#3498db', '#2980b9', '#3b5998', '#1abc9c', '#16a085', '#2ecc71', '#27ae60', '#f39c12'];
                $i = 0;
                foreach ($skillsDemand as $skill => $demand) {
                    $color = $colors[$i % count($colors)];
                    echo "['" . addslashes($skill) . "', $demand, 'color: $color'],";
                    $i++;
                }
                ?>
            ]);

            var options = {
                title: 'Top Skills Among Active Candidates',
                titleTextStyle: { fontSize: window.innerWidth < 768 ? 16 : 24, bold: true, color: '#1a1f36' },
                backgroundColor: 'transparent',
                chartArea: { width: window.innerWidth < 768 ? '50%' : '65%', height: '80%' },
                hAxis: {
                    title: 'Verified Candidates',
                    minValue: 0,
                    textStyle: { fontSize: 13, color: '#6b7280' },
                    titleTextStyle: { fontSize: 13, italic: false, color: '#6b7280', bold: true }
                },
                vAxis: {
                    textStyle: { fontSize: window.innerWidth < 768 ? 12 : 15, color: '#1a1f36', bold: true }
                },
                legend: { position: 'none' },
                animation: {
                    startup: true,
                    duration: 1500,
                    easing: 'out'
                }
            };

            var chart = new google.visualization.BarChart(document.getElementById('skillsChart'));
            
            // Draw chart initially
            chart.draw(data, options);
            
            // Redraw chart dynamically on window resize to ensure responsiveness
            window.addEventListener('resize', function() {
                // Update dynamic options on resize
                options.titleTextStyle.fontSize = window.innerWidth < 768 ? 16 : 24;
                options.chartArea.width = window.innerWidth < 768 ? '50%' : '65%';
                options.vAxis.textStyle.fontSize = window.innerWidth < 768 ? 12 : 15;
                chart.draw(data, options);
            });
        }
    </script>

    <section class="animate-up delay-1" style="background: linear-gradient(135deg, #1171ba, #2a9df4); padding: 7rem 20px; text-align: center; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50%; left: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -50%; right: -10%; width: 600px; height: 600px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%;"></div>
        
        <h2 style="font-size: 3.5rem; font-weight: 900; margin-bottom: 1.5rem; position: relative; z-index: 1;">Ready to transform your team?</h2>
        <p style="font-size: 1.35rem; opacity: 0.9; margin-bottom: 3.5rem; max-width: 700px; margin-left: auto; margin-right: auto; line-height: 1.6; position: relative; z-index: 1;">Join JobPortal now to find the right talent for your company and advance your team's success with our smart matching tools.</p>
        <a href="<?php echo BASE_URL; ?>/employer/listjob" style="text-decoration: none; position: relative; z-index: 1;">
            <button class="btn btn-primary rounded-pill" style="background: white; color: #1171ba; padding: 18px 50px; font-size: 1.25rem; font-weight: 800; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.2); transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                Post a Job and Start Hiring <i class="fas fa-arrow-right" style="margin-left: 10px;"></i>
            </button>
        </a>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
