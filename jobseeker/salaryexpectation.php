<?php
session_start();
include '../database_configure.php';

class ResumePage {
    private $conn;
    private $username;

    public function __construct($connection) {
        $this->conn = $connection;
        $this->checkSession();
    }

    private function checkSession() {
        if (!isset($_SESSION['username'])) {
            echo "<script type='text/javascript'>alert('Please sign in first'); location.replace('" . BASE_URL . "/jobseeker/jobseekerHome');</script>";
            exit();
        }
        $this->username = $_SESSION['username'];
    }

    public function renderForm() {
        ?>
        <div style="text-align: center; margin-bottom: 2rem; padding-top: 2rem;">
            <h2 style="font-size: 2.5rem; font-weight: 700; color: var(--text-main);">Salary <span class="text-gradient">Predictor</span></h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Enter your skills and experience below to calculate your projected market value based on real-time data.</p>
        </div>

        <div class="glass-card" id="form-container-id" style="max-width: 600px; margin: 0 auto 3rem auto; padding: 2.5rem; background: rgba(255,255,255,0.9);">
            <form method="post" onsubmit="setScrollFlag()" style="display: flex; flex-direction: column; gap: 1.5rem;">
                <input type="hidden" name="scrollToForm" id="scrollToForm" value="false">
                
                <div>
                    <label for="skills" style="font-size: 1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; display: block;"><i class="fas fa-laptop-code" style="color: var(--primary-color);"></i> Core Skills</label>
                    <input type="text" id="skills" name="skills" placeholder="e.g., HTML, CSS, PHP, JavaScript" required style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1px solid var(--border-color); font-size: 1rem; transition: var(--transition); background: white;">
                </div>

                <div>
                    <label for="experience" style="font-size: 1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; display: block;"><i class="fas fa-briefcase" style="color: var(--primary-color);"></i> Years of Experience</label>
                    <input type="number" id="experience" name="experience" min="0" max="50" placeholder="e.g., 2" required style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1px solid var(--border-color); font-size: 1rem; transition: var(--transition); background: white;">
                </div>

                <div style="margin-top: 1rem;">
                    <button type="submit" class="btn-primary rounded-pill w-100" style="padding: 14px; font-size: 1.1rem; width: 100%; border: none;"><i class="fas fa-calculator"></i> Calculate Value</button>
                </div>
            </form>
        </div>
        <?php
    }

    public function handleFormSubmission() {
        if ($_POST) {
            $userSkills = $_POST['skills'];
            $experience = (int)$_POST['experience'];
            $userSkillsArray = array_map('trim', explode(',', $userSkills));
    
            // Fetch training data matching the user's skills
            $resultData = $this->fetchTrainingData($userSkillsArray);
            $dataset = $resultData['dataset'];
            $matchType = $resultData['matchType'];
    
            echo "<div class='glass-card' style='max-width: 700px; margin: 0 auto 4rem auto; padding: 3rem; text-align: center; background: rgba(255,255,255,0.95);'>";
            if (!empty($dataset)) {
                // Train the DecisionTree model on matching job postings
                $dt = new DecisionTree();
                $tree = $dt->buildTree($dataset);
                $predictedSalary = $dt->predict($tree, [$experience]);
                
                echo "<i class='fas fa-brain' style='font-size: 3.5rem; color: #3498db; margin-bottom: 1.5rem;'></i>";
                echo "<h3 style='font-size: 1.8rem; color: var(--text-main); font-weight: 700; margin-bottom: 1rem;'>AI Salary Projection</h3>";
                
                if ($matchType === 'exact') {
                    echo "<p style='color: var(--text-muted); font-size: 1.1rem; margin-bottom: 1.5rem;'>Based on a machine learning model trained on jobs requiring <strong>" . htmlspecialchars(implode(', ', $userSkillsArray)) . "</strong>, your projected salary with <strong>$experience</strong> years of experience is:</p>";
                } else {
                    echo "<p style='color: var(--text-muted); font-size: 1.1rem; margin-bottom: 1.5rem;'>We couldn't find enough jobs matching all specified skills. Based on related matches (containing one or more of your skills), your projected salary with <strong>$experience</strong> years of experience is:</p>";
                }
                
                echo "<h2 class='text-gradient' style='font-size: 4rem; font-weight: 800; margin: 0;'>RS. " . number_format(round($predictedSalary)) . "</h2>";
            } else {
                echo "<i class='fas fa-search-minus' style='font-size: 3.5rem; color: var(--text-muted); margin-bottom: 1.5rem;'></i>";
                echo "<h3 style='font-size: 1.8rem; color: var(--text-main); font-weight: 700; margin-bottom: 1rem;'>Insufficient Data</h3>";
                echo "<p style='color: var(--text-muted); font-size: 1.1rem; margin: 0;'>We couldn't find enough job matches for <strong>" . htmlspecialchars(implode(', ', $userSkillsArray)) . "</strong> to make a reliable projection.</p>";
            }
            echo "</div>";
        }
    }

    private function fetchTrainingData($userSkillsArray) {
        $dataset = [];
        $matchType = 'exact';
        
        // 1. Try with AND condition (exact skill match)
        $conditions = [];
        foreach ($userSkillsArray as $skill) {
            $conditions[] = "skills LIKE '%" . $this->conn->real_escape_string($skill) . "%'";
        }
        $sql = "SELECT workexperience, salary FROM job_postings WHERE " . implode(' AND ', $conditions);
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dataset[] = [(int)$row['workexperience'], (float)$row['salary']];
            }
        }
        
        // 2. Fallback: If no matches with AND, try with OR condition (partial skill match)
        if (empty($dataset) && count($userSkillsArray) > 1) {
            $conditions = [];
            foreach ($userSkillsArray as $skill) {
                $conditions[] = "skills LIKE '%" . $this->conn->real_escape_string($skill) . "%'";
            }
            $sql = "SELECT workexperience, salary FROM job_postings WHERE " . implode(' OR ', $conditions);
            $result = $this->conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                $matchType = 'partial';
                while ($row = $result->fetch_assoc()) {
                    $dataset[] = [(int)$row['workexperience'], (float)$row['salary']];
                }
            }
        }
        
        return ['dataset' => $dataset, 'matchType' => $matchType];
    }
}

class DecisionTree {
    private $maxDepth;
    private $minSize;

    public function __construct($maxDepth = 10, $minSize = 2) {
        $this->maxDepth = $maxDepth;
        $this->minSize = $minSize;
    }

    public function buildTree($dataset, $depth = 0) {
        if (empty($dataset)) return 0;
        $salaries = array_column($dataset, 1);
        if ($depth >= $this->maxDepth || count($dataset) <= $this->minSize) return $this->mean($salaries);

        [$bestAttribute, $bestValue, $bestMSE] = $this->getBestSplit($dataset);
        if ($bestAttribute === null) return $this->mean($salaries);

        [$left, $right] = $this->splitDataset($dataset, $bestAttribute, $bestValue);

        return [
            'left' => $this->buildTree($left, $depth + 1),
            'right' => $this->buildTree($right, $depth + 1),
            'attribute' => $bestAttribute,
            'value' => $bestValue
        ];
    }

    // Function to find the best split point
    private function getBestSplit($dataset) {
        $bestMSE = PHP_FLOAT_MAX;
        $bestAttribute = null;
        $bestValue = null;

        // Iterate over every attribute and potential split value
        foreach ($dataset as $row) {
            $value = $row[0];
            [$left, $right] = $this->splitDataset($dataset, 0, $value);
            
            // Skip invalid splits that do not divide the dataset (prevents infinite recursion)
            if (empty($left) || empty($right)) {
                continue;
            }
            
            $currentMSE = $this->mse($left, $right);

            if ($currentMSE < $bestMSE) {
                $bestMSE = $currentMSE;
                $bestAttribute = 0; // We are considering the experience as the attribute here
                $bestValue = $value;
            }
        }

        return [$bestAttribute, $bestValue, $bestMSE];
    }

    // Split the dataset based on the attribute and value
    private function splitDataset($dataset, $attribute, $value) {
        $left = [];
        $right = [];

        foreach ($dataset as $row) {
            if ($row[$attribute] < $value) {
                $left[] = $row;
            } else {
                $right[] = $row;
            }
        }

        return [$left, $right];
    }

    // Mean squared error calculation
    private function mse($left, $right) {
        $totalSize = count($left) + count($right);
        if ($totalSize == 0) {
            return 0;
        }

        $leftVariance = $this->variance(array_column($left, 1));
        $rightVariance = $this->variance(array_column($right, 1));

        return (count($left) / $totalSize) * $leftVariance + (count($right) / $totalSize) * $rightVariance;
    }

    // Variance calculation for an array
    private function variance($array) {
        if (empty($array) || count($array) === 1) {
            return 0;
        }

        $mean = $this->mean($array);
        $sumOfSquares = array_reduce($array, fn($sum, $value) => $sum + pow($value - $mean, 2), 0);

        return $sumOfSquares / (count($array) - 1);
    }

    // Calculate the mean of an array
    private function mean($array) {
        return array_sum($array) / count($array);
    }

    // Predict the outcome for a given row
    public function predict($tree, $row) {
        if (!is_array($tree)) {
            return $tree;
        }

        if ($row[0] < $tree['value']) {
            return $this->predict($tree['left'], $row);
        } else {
            return $this->predict($tree['right'], $row);
        }
    }
}

$page = new ResumePage($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="jobseeker-style.css?v=<?php echo time(); ?>"/>
<link rel="stylesheet" href="../main-styles.css?v=<?php echo time(); ?>"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>JobPortal Seekers Hub</title>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script>
    // Set a flag in local storage when the form is submitted
    function setScrollFlag() {
        localStorage.setItem('scrollToForm', 'true');
    }

    // Check if the flag is set and scroll to the form
    window.onload = function() {
        if (localStorage.getItem('scrollToForm') === 'true') {
            document.getElementById('form-container-id').scrollIntoView({ behavior: 'smooth' });
            localStorage.removeItem('scrollToForm'); // Remove flag after scrolling
        }
    };
</script>

<style>
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
                    <li><a href="<?php echo BASE_URL; ?>/jobseeker/jobseekerHome" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'jobseekerHome.php') ? 'active' : ''; ?>">Seekers Hub</a></li>      
                    <li><a href="joblist" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'joblist.php') ? 'active' : ''; ?>">Find Job</a></li>
                    <li><a href="resumepage" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'resumepage.php' || basename($_SERVER['PHP_SELF']) == 'managecv.php' || basename($_SERVER['PHP_SELF']) == 'resumeform.php') ? 'active' : ''; ?>">Resume Here</a></li>
                    <li><a href="salaryexpectation" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'salaryexpectation.php') ? 'active' : ''; ?>">Expected Salary</a></li>
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

<div style="padding-top: 120px; padding-bottom: 4rem; background: linear-gradient(135deg, rgba(246, 248, 253, 0.8) 0%, rgba(241, 246, 249, 0.8) 100%); min-height: 80vh;">
<?php 
$page->renderForm(); 
$page->handleFormSubmission();
?>
</div>
<?php include 'footer.php'; ?>