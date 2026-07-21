<?php
session_start();
include '../database_configure.php';

class JobSeeker {
    private $conn;
    private $searchLimit = 9;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function checkSession() {
        // Uncomment this if session validation is required
        // if (!isset($_SESSION['username'])) {
        //     echo "<script type='text/javascript'>alert('Please sign in first'); location.replace('http://jobportal.loc/jobseeker/jobseekerHome');</script>";
        //     exit;
        // }
    }

    public function getUserId() {
        return $_SESSION['username'];
    }

    public function fetchJobPostings($page) {
        $currentPage = max(0, ($page - 1) * $this->searchLimit);
        $sql = "SELECT * FROM `job_postings` LIMIT $currentPage, $this->searchLimit";
        return $this->conn->query($sql);
    }

    public function getTotalPages() {
        $fetch = "SELECT COUNT(*) as total FROM `job_postings`";
        $result = $this->conn->query($fetch);
        $totalResult = $result->fetch_assoc();
        return ceil($totalResult['total'] / $this->searchLimit);
    }

    // OPTIMIZATION 1: Modified to accept pre-calculated user vectors to save server resources
    public function cosineSimilarity($userSkillsVector, $userMagnitude, $jobSkills) {
        $jobSkillsVector = array_count_values($jobSkills);
        
        $dotProduct = 0;
        $jobMagnitude = 0;

        foreach ($userSkillsVector as $skill => $count) {
            $dotProduct += $count * ($jobSkillsVector[$skill] ?? 0);
        }

        foreach ($jobSkillsVector as $count) {
            $jobMagnitude += pow($count, 2);
        }

        if ($userMagnitude == 0 || $jobMagnitude == 0) {
            return 0;
        }

        return $dotProduct / (sqrt($userMagnitude) * sqrt($jobMagnitude));
    }

    public function recommendJobs($userSkills, $userExperience) {
        $recommendedJobs = [];
        $hasExperienceMatch = false;

        // PRE-CALCULATE USER MATH OUTSIDE THE LOOP (Massive performance boost)
        $userSkillsVector = array_count_values($userSkills);
        $userMagnitude = 0;
        foreach ($userSkillsVector as $count) {
            $userMagnitude += pow($count, 2);
        }
    
        $sql = "SELECT j.job_id, j.title, j.location, j.skills, j.requirements, j.salary, j.workexperience, j.status, e.employer_id, e.Fullname_E 
                FROM job_postings AS j 
                JOIN employerlogin AS e ON j.employer_id = e.employer_id 
                WHERE j.status = 1";
        $result = $this->conn->query($sql);
    
        if ($result->num_rows > 0) {
            while ($job = $result->fetch_assoc()) {
                $jobSkills = array_map('strtolower', array_map('trim', explode(',', $job['skills'])));
                $matchingSkills = array_intersect($userSkills, $jobSkills);
                $matchingSkillsCount = count($matchingSkills);
    
                if ($matchingSkillsCount > 0) {
                    // Pass the pre-calculated user math into the function
                    $cosineScore = $this->cosineSimilarity($userSkillsVector, $userMagnitude, $jobSkills);
                    $experienceScore = $this->calculateExperienceScore($userExperience, $job['workexperience']);
    
                    if ($experienceScore > 0) {
                        $hasExperienceMatch = true;
                    }
    
                    // Combine the skill and experience scores for the final recommendation score
                    $finalScore = (0.7 * $cosineScore) + (0.3 * $experienceScore);
    
                    // Ensure matching skills are unique
                    $matchingSkills = array_unique($matchingSkills); 
                    $job['matching_skills'] = ucwords(implode(', ', $matchingSkills)); 
                    $job['experience_score'] = number_format($experienceScore * 100, 2);
                    $job['cosine_similarity'] = number_format($cosineScore * 100, 2);
                    
                    // Store final score as a float for sorting, format it for display later
                    $job['raw_final_score'] = $finalScore; 
                    $job['final_score'] = number_format($finalScore * 100, 2);
                    
                    $job['experience_match'] = $experienceScore; 
                    $job['experience_difference'] = $userExperience - $job['workexperience']; 
                    
                    $recommendedJobs[] = $job;
                }
            }
        }
    
        // OPTIMIZATION 2: Trust the final score for sorting
        // This simply ranks the jobs from highest final score to lowest.
        usort($recommendedJobs, function ($a, $b) {
            // Sort descending based on the raw final score
            return $b['raw_final_score'] <=> $a['raw_final_score'];
        });
    
        return [$recommendedJobs, $hasExperienceMatch];
    }
    
    private function calculateExperienceScore($userExperience, $jobExperience) {
        // Prevent division by zero if a job requires 0 years of experience
        if ($jobExperience <= 0) {
            return 1; // Any user experience is a perfect match for a 0-experience job
        }

        // Perfect match if user meets or exceeds requirements
        if ($userExperience >= $jobExperience) {
            return 1; 
        }
        
        // OPTIMIZATION 3: Smooth penalty instead of an instant fail.
        // If they have 3 years but need 4, they get a 0.75 score.
        return $userExperience / $jobExperience;
    }
}

$jobSeeker = new JobSeeker($conn);
$jobSeeker->checkSession();
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Updated CSS paths to handle standard loading -->
    <link rel="stylesheet" href="../main-styles.css"/>
    <link rel="stylesheet" href="jobseeker-style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>JobPortal Find Job</title>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('title') || urlParams.get('keyword');

            if (searchQuery) {
                document.getElementById("recommednjobstyleq").scrollIntoView({ behavior: "smooth" });
            }
        };
    </script>
</head>
<body>
    <div class="nav-container">
        <nav class="glass-nav">
            <div class="logo"> 
                <a href="<?php echo defined('BASE_URL') ? BASE_URL : '/'; ?>/home-page"><img src="../img/logo.png" alt="JobPortal Logo"></a>
            </div>
            
            <input type="checkbox" id="menu">
            <label for="menu" class="menu-button">
                <i class="fas fa-bars"></i>
            </label>
            
            <div class="nav-content">
                <ul class="nav-links">
                    <li><a href="<?php echo defined('BASE_URL') ? BASE_URL : '/'; ?>/jobseeker/jobseekerHome" class="nav-link">Seekers Hub</a></li>      
                    <li><a href="joblist" class="nav-link active">Find Job</a></li>
                    <li><a href="resumepage" class="nav-link">Resume Here</a></li>
                    <li><a href="salaryexpectation" class="nav-link">Expected Salary</a></li>
                    <li><a href="appliedjobs" class="nav-link">Applied Jobs</a></li>
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
    <section class="banner" style="padding-top: 140px; min-height: 40vh; padding-bottom: 4rem; justify-content: center; text-align: center;">
        <div class="banner-content" style="flex: none; padding-right: 0;">
            <span class="badge-pill" style="margin-bottom: 1rem;">💼 Thousands of Opportunities</span>
            <h1 style="font-size: 3.5rem; font-weight: 800; margin-bottom: 1rem;">Find Your <span class="text-gradient">Next Big Role</span></h1>
            <p style="font-size: 1.2rem; color: var(--text-muted); max-width: 600px; margin: 0 auto;">Discover the best jobs from top employers, tailored to your unique skills.</p>
        </div>
    </section>

    <div class="habt_container" style="display: block; max-width: 1200px; margin: 0 auto; padding-bottom: 4rem;">
        
        <!-- Recommended Jobs Section -->
        <?php if(isset($_SESSION['username'])){ ?>
            <div style="text-align: center; margin-bottom: 3rem;">
                <h2 style="font-size: 2.5rem; font-weight: 700; color: var(--text-main);">Recommended <span class="text-gradient">Jobs</span></h2>
                <p style="color: var(--text-muted); font-size: 1.1rem;">We Provide you the best job that matches your skills and experience.</p>
            </div>
            
            <div class="category-grid" style="margin-bottom: 5rem;">
            <?php
                $userId = $jobSeeker->getUserId();
                $resumeQuery = "SELECT * FROM seekerresume WHERE Seeker_id = '$userId'";
                $resumeResult = $conn->query($resumeQuery);

                if ($resumeResult && $resumeResult->num_rows > 0) {
                    $userData = $resumeResult->fetch_assoc();
                    $userSkills = array_map('strtolower', array_map('trim', preg_split("/[\r\n,]+/", $userData['skill'], -1, PREG_SPLIT_NO_EMPTY)));
                    $userExperience = (int)$userData['Workexp']; 
                    
                    list($recommendedJobs, $hasExperienceMatch) = $jobSeeker->recommendJobs($userSkills, $userExperience);

                    if (!empty($recommendedJobs)) {
                        foreach ($recommendedJobs as $job) { ?>
                            <div class="glass-card buy" style="align-items: flex-start; text-align: left; padding: 2rem;">
                                <div style="margin-bottom: 1.5rem; width: 100%;">
                                    <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem;"><?= htmlspecialchars($job['title']); ?></h3>
                                    <p style="color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-building" style="color: var(--primary-color); width: 25px;"></i> <?= htmlspecialchars($job['Fullname_E']); ?></p>
                                    <p style="color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-money-bill-wave" style="color: var(--primary-color); width: 25px;"></i> <?= htmlspecialchars($job['salary']); ?></p>
                                    <p style="color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-map-marker-alt" style="color: var(--primary-color); width: 25px;"></i> <?= htmlspecialchars($job['location']); ?></p>
                                    <hr style="border-top: 1px solid var(--border-color); margin: 1rem 0;">
                                    <p style="color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.9rem;"><strong>Match:</strong> <?= $job['final_score']; ?>%</p>
                                    <p style="color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.9rem; line-height: 1.4;"><strong>Skills:</strong> <?= htmlspecialchars($job['matching_skills']); ?></p>
                                </div>
                                <a href="job/<?= $job['job_id']; ?>/<?= urlencode(strtolower(str_replace(' ', '-', $job['title']))); ?>" class="w-100 banner-buttons" style="margin-top: auto;"><button class="btn-primary rounded-pill w-100">View Details</button></a>
                            </div>
                        <?php }
                    } else {
                        echo "<p style='width: 100%; text-align: center;'>No job recommendations found for your current skills. Try adding more skills to your resume!</p>";
                    }
                } else {
                    echo "<p style='width: 100%; text-align: center;'>Please complete your <a href='resumepage'>resume</a> to see recommended jobs.</p>";
                }
                ?>
            </div>
        <?php } ?>

        <!-- All Job Listings Section -->
        <div style="text-align: center; margin-bottom: 2rem; padding-top: 2rem;" id="recommednjobstyleq">
            <h2 style="font-size: 2.5rem; font-weight: 700; color: var(--text-main);">All Job <span class="text-gradient">Listings</span></h2>
        </div>
        
        <div class="glass-card" style="max-width: 700px; margin: 0 auto 4rem auto; padding: 1.5rem; border-radius: 50px;">
            <form method="GET" style="display: flex; gap: 10px;">
                <?php $displaySearch = isset($_GET['title']) && $_GET['title'] !== '' ? $_GET['title'] : (isset($_GET['keyword']) ? $_GET['keyword'] : ''); ?>
                <input type="text" name="title" placeholder="Search by Job Title or Skill..." value="<?= htmlspecialchars($displaySearch); ?>" style="flex: 1; padding: 12px 20px; border-radius: 50px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.5); font-size: 1.1rem; outline: none; color: var(--text-main);">
                <div class="banner-buttons">
                    <button type="submit" class="btn-primary rounded-pill" style="padding: 12px 30px;"><i class="fa fa-search"></i> Search</button>
                </div>
            </form>
        </div>
        
        <div class="category-grid">
            <?php
            $titleSearch = '';
            if (isset($_GET['title']) && !empty($_GET['title'])) {
                $titleSearch = mysqli_real_escape_string($conn, $_GET['title']);
            } elseif (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
                $titleSearch = mysqli_real_escape_string($conn, $_GET['keyword']);
            }

            $fetch = "SELECT j.job_id, j.title, j.location, j.salary, j.status, j.skills, e.employer_id, e.Fullname_E 
                    FROM job_postings AS j 
                    JOIN employerlogin AS e 
                    ON j.employer_id = e.employer_id 
                    WHERE j.status = 1";

            if (!empty($titleSearch)) {
                $fetch .= " AND (j.title LIKE '%$titleSearch%' OR j.skills LIKE '%$titleSearch%')";
            }

            $queryfetch = mysqli_query($conn, $fetch);

            if (mysqli_num_rows($queryfetch) > 0) {
                while ($result = mysqli_fetch_assoc($queryfetch)) { ?>
                    <div class="glass-card buy" style="align-items: flex-start; text-align: left; padding: 2rem;">
                        <div style="margin-bottom: 1.5rem; width: 100%;">
                            <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem;"><?= htmlspecialchars($result['title']); ?></h3>
                            <p style="color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-building" style="color: var(--primary-color); width: 25px;"></i> <?= htmlspecialchars($result['Fullname_E']); ?></p>
                            <p style="color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-money-bill-wave" style="color: var(--primary-color); width: 25px;"></i> <?= htmlspecialchars($result['salary']); ?></p>
                            <p style="color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-map-marker-alt" style="color: var(--primary-color); width: 25px;"></i> <?= htmlspecialchars($result['location']); ?></p>
                        </div>
                        <a href="job/<?= $result['job_id']; ?>/<?= urlencode(strtolower(str_replace(' ', '-', $result['title']))); ?>" class="w-100 banner-buttons" style="margin-top: auto;"><button class="btn-primary rounded-pill w-100">View Details</button></a>
                    </div>
                <?php }
            } else {
                echo "<p style='width: 100%; text-align: center;'>No jobs found matching your search.</p>";
            }
            ?>
        </div>

    </div>

    <?php include 'footer.php' ?>
</body>
</html>