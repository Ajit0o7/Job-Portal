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

    public function cosineSimilarity($userSkills, $jobSkills) {
        $userSkillsVector = array_count_values($userSkills);
        $jobSkillsVector = array_count_values($jobSkills);

        $dotProduct = 0;
        $userMagnitude = 0;
        $jobMagnitude = 0;

        foreach ($userSkillsVector as $skill => $count) {
            $dotProduct += $count * ($jobSkillsVector[$skill] ?? 0);
            $userMagnitude += pow($count, 2);
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
                    $cosineScore = $this->cosineSimilarity($userSkills, $jobSkills);
                    $experienceScore = $this->calculateExperienceScore($userExperience, $job['workexperience']);
    
                    if ($experienceScore > 0) {
                        $hasExperienceMatch = true;
                    }
    
                    // Combine the skill and experience scores for the final recommendation score
                    $finalScore = (0.7 * $cosineScore) + (0.3 * $experienceScore);
    
                    // Ensure matching skills are unique
                    $matchingSkills = array_unique($matchingSkills); // Ensure unique skills
                    $job['matching_skills'] = ucwords(implode(', ', $matchingSkills)); // Convert to string
                    $job['experience_score'] = number_format($experienceScore * 100, 2);
                    $job['cosine_similarity'] = number_format($cosineScore * 100, 2);
                    $job['final_score'] = number_format($finalScore * 100, 2);
                    $job['experience_match'] = $experienceScore; // Store experience score for sorting
                    $job['experience_difference'] = $userExperience - $job['workexperience']; // Calculate experience difference
                    $recommendedJobs[] = $job;
                }
            }
        }
    
        // Sort the recommended jobs based on experience match conditions and final scores
        usort($recommendedJobs, function ($a, $b) use ($userExperience) {
            // Check if either job requires more experience than the user has
            if ($a['workexperience'] > $userExperience && $b['workexperience'] > $userExperience) {
                return 1; // Both jobs are unsuitable, maintain their order
            }
        
            if ($a['workexperience'] > $userExperience) {
                return 1; // Job A is unsuitable, push it to the end
            }
        
            if ($b['workexperience'] > $userExperience) {
                return -1; // Job B is unsuitable, push it to the end
            }
        
            // Calculate experience differences
            $experienceDifferenceA = $userExperience - $a['workexperience'];
            $experienceDifferenceB = $userExperience - $b['workexperience'];
        
            // Handle equal experience differences
            if ($experienceDifferenceA === 0 && $experienceDifferenceB === 0) {
                return $b['workexperience'] <=> $a['workexperience'];
            }
        
            // Sort when both experiences are less than user experience
            if ($userExperience > $a['workexperience'] && $userExperience > $b['workexperience']) {
                return $experienceDifferenceA <=> $experienceDifferenceB;
            }
        
            // Sort when both experiences are greater than user experience
            if ($userExperience <= $a['workexperience'] && $userExperience <= $b['workexperience']) {
                return $experienceDifferenceB <=> $experienceDifferenceA; 
            }
        
            // Final comparison if not covered above
            return $experienceDifferenceA <=> $experienceDifferenceB; 
        });
    
        return [$recommendedJobs, $hasExperienceMatch];
    }
    
    


    private function calculateExperienceScore($userExperience, $jobExperience) {
        // Basic comparison to check if the user's experience meets or exceeds the job requirement
        if ($userExperience >= $jobExperience) {
            return 1; // Perfect match
        }
        
        // Otherwise, calculate a proportionate score
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
    <link rel="stylesheet" href="../main-styles.css?v=<?php echo time(); ?>"/>
    <link rel="stylesheet" href="jobseeker-style.css?v=<?php echo time(); ?>"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>JobPortal Find Job</title>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* CSS replaced by global main-styles.css */
    </style>
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('title');

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
                <a href="<?php echo BASE_URL; ?>/home-page"><img src="../img/logo.png" alt="JobPortal Logo"></a>
            </div>
            
            <input type="checkbox" id="menu">
            <label for="menu" class="menu-button">
                <i class="fas fa-bars"></i>
            </label>
            
            <div class="nav-content">
                <ul class="nav-links">
                    <li><a href="<?php echo BASE_URL; ?>/jobseeker/jobseekerHome" class="nav-link">Seekers Hub</a></li>      
                    <li><a href="joblist" class="nav-link active">Find Job</a></li>
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
                $resumeQuery = "SELECT * FROM seekerresume WHERE Seeker_id = $userId";
                $resumeResult = $conn->query($resumeQuery);

                if ($resumeResult->num_rows > 0) {
                    $userData = $resumeResult->fetch_assoc();
                    $userSkills = array_map('strtolower', array_map('trim', preg_split("/[\r\n,]+/", $userData['skill'], -1, PREG_SPLIT_NO_EMPTY)));
                    $userExperience = (int)$userData['Workexp']; // Assuming 'experience' column represents years of experience
                    list($recommendedJobs, $hasExperienceMatch) = $jobSeeker->recommendJobs($userSkills, $userExperience);

                    if (!$hasExperienceMatch) {
                        // echo "<p style='color: red; text-align: center; width:100%;'>No matching years of experience found yet. Please check the job recommendations for matching skills.</p>";
                    }

                    if (!empty($recommendedJobs)) {
                        foreach ($recommendedJobs as $job) { ?>
                            <div class="glass-card buy" style="align-items: flex-start; text-align: left; padding: 2rem;">
                                <div style="margin-bottom: 1.5rem; width: 100%;">
                                    <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem;"><?= $job['title']; ?></h3>
                                    <p style="color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-building" style="color: var(--primary-color); width: 25px;"></i> <?= $job['Fullname_E']; ?></p>
                                    <p style="color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-money-bill-wave" style="color: var(--primary-color); width: 25px;"></i> <?= $job['salary']; ?></p>
                                    <p style="color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-map-marker-alt" style="color: var(--primary-color); width: 25px;"></i> <?= $job['location']; ?></p>
                                    <hr style="border-top: 1px solid var(--border-color); margin: 1rem 0;">
                                    <p style="color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.9rem;"><strong>Match:</strong> <?= $job['final_score']; ?>%</p>
                                    <p style="color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.9rem; line-height: 1.4;"><strong>Skills:</strong> <?= $job['matching_skills']; ?></p>
                                </div>
                                <a href="job/<?= $job['job_id']; ?>/<?= urlencode(strtolower(str_replace(' ', '-', $job['title']))); ?>" class="w-100 banner-buttons" style="margin-top: auto;"><button class="btn-primary rounded-pill w-100">View Details</button></a>
                            </div>
                        <?php }
                    } else {
                        echo "<p>No job recommendations found.</p>";
                    }
                } else {
                    echo "<p>Please complete your <a href='resumepage'>resume</a> to see recommended jobs.</p>";
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
                <input type="text" name="title" placeholder="Search by Job Title..." value="<?= isset($_GET['title']) ? $_GET['title'] : ''; ?>" style="flex: 1; padding: 12px 20px; border-radius: 50px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.5); font-size: 1.1rem; outline: none; color: var(--text-main);">
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
            }

            $fetch = "SELECT j.job_id, j.title, j.location, j.salary, j.status, e.employer_id, e.Fullname_E 
                    FROM job_postings AS j 
                    JOIN employerlogin AS e 
                    ON j.employer_id = e.employer_id 
                    WHERE j.status = 1";

            if (!empty($titleSearch)) {
                $fetch .= " AND j.title LIKE '%$titleSearch%'";
            }

            $queryfetch = mysqli_query($conn, $fetch);

            while ($result = mysqli_fetch_assoc($queryfetch)) { ?>
                <div class="glass-card buy" style="align-items: flex-start; text-align: left; padding: 2rem;">
                    <div style="margin-bottom: 1.5rem; width: 100%;">
                        <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem;"><?= $result['title']; ?></h3>
                        <p style="color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-building" style="color: var(--primary-color); width: 25px;"></i> <?= $result['Fullname_E']; ?></p>
                        <p style="color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-money-bill-wave" style="color: var(--primary-color); width: 25px;"></i> <?= $result['salary']; ?></p>
                        <p style="color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-map-marker-alt" style="color: var(--primary-color); width: 25px;"></i> <?= $result['location']; ?></p>
                    </div>
                    <a href="job/<?= $result['job_id']; ?>/<?= urlencode(strtolower(str_replace(' ', '-', $result['title']))); ?>" class="w-100 banner-buttons" style="margin-top: auto;"><button class="btn-primary rounded-pill w-100">View Details</button></a>
                </div>
            <?php } ?>
        </div>

        <!-- Pagination -->
        <!-- <div class="pagination">
            <?php
            // $totalPages = $jobSeeker->getTotalPages();
            // for ($i = 1; $i <= $totalPages; $i++) {
            //     echo "<a href='joblist?page=$i'>$i</a>";
            // }
            ?>
        </div> -->

    </div>

    <?php include 'footer.php' ?>
</body>
</html>
