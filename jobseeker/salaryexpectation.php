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
            <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Enter your skills, experience, and target role below to calculate your projected market value based on real-time database postings.</p>
        </div>

        <div class="glass-card" id="form-container-id" style="max-width: 620px; margin: 0 auto 3rem auto; padding: 2.5rem; background: rgba(255,255,255,0.9);">
            <form method="post" onsubmit="setScrollFlag()" style="display: flex; flex-direction: column; gap: 1.5rem;">
                <input type="hidden" name="scrollToForm" id="scrollToForm" value="false">
                
                <div>
                    <label for="job_title" style="font-size: 1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; display: block;">
                        <i class="fas fa-id-badge" style="color: var(--primary-color);"></i> Target Job Title / Role <span style="font-weight: normal; color: var(--text-muted); font-size: 0.85rem;">(Optional)</span>
                    </label>
                    <input type="text" id="job_title" name="job_title" placeholder="e.g., Frontend Developer, PHP Developer, Data Analyst" style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1px solid var(--border-color); font-size: 1rem; transition: var(--transition); background: white;">
                </div>

                <div>
                    <label for="skills" style="font-size: 1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; display: block;">
                        <i class="fas fa-laptop-code" style="color: var(--primary-color);"></i> Core Skills
                    </label>
                    <input type="text" id="skills" name="skills" placeholder="e.g., HTML, CSS, PHP, JavaScript" required style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1px solid var(--border-color); font-size: 1rem; transition: var(--transition); background: white;">
                </div>

                <div>
                    <label for="experience" style="font-size: 1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; display: block;">
                        <i class="fas fa-briefcase" style="color: var(--primary-color);"></i> Years of Experience
                    </label>
                    <input type="number" id="experience" name="experience" min="0" max="50" placeholder="e.g., 2" required style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1px solid var(--border-color); font-size: 1rem; transition: var(--transition); background: white;">
                </div>

                <div style="margin-top: 1rem;">
                    <button type="submit" class="btn-primary rounded-pill w-100" style="padding: 14px; font-size: 1.1rem; width: 100%; border: none;"><i class="fas fa-calculator"></i> Calculate Market Value</button>
                </div>
            </form>
        </div>
        <?php
    }

    public function handleFormSubmission() {
        if ($_POST) {
            $userSkills = $_POST['skills'];
            $targetJobTitle = isset($_POST['job_title']) ? trim($_POST['job_title']) : '';
            $experience = (int)$_POST['experience'];
            $userSkillsArray = array_filter(array_map('trim', explode(',', $userSkills)));
    
            // Fetch training data & run Hybrid KNN + Ridge Ensemble
            $resultData = $this->fetchTrainingData($userSkillsArray, $targetJobTitle, $experience);
            $dataset = $resultData['dataset'];
            $globalDataset = $resultData['globalDataset'];
            $topKMatches = $resultData['topKMatches'];
            $rawCount = $resultData['rawCount'];
            $matchType = $resultData['matchType'];
            $unmatchedSkills = $resultData['unmatchedSkills'];
            $matchedSkills = $resultData['matchedSkills'];
            $matchedRatio = $resultData['matchedRatio'];
            $suggestedSkills = $resultData['suggestedSkills'];
    
            echo "<div class='glass-card' style='max-width: 780px; margin: 0 auto 4rem auto; padding: 3rem; text-align: center; background: rgba(255,255,255,0.95); box-shadow: 0 10px 30px rgba(0,0,0,0.08);'>";
            if (!empty($dataset)) {
                // Train Multivariate Ridge Regression model
                $mlr = new MultivariateLinearRegression();
                $mlr->train($dataset, $globalDataset);
                $regPredictedSalary = $mlr->predict($experience, $matchedRatio);

                // Run KNN Cosine Distance Engine
                $knnSalary = $resultData['knnSalary'];
                $knnWeight = (!empty($topKMatches) && $topKMatches[0]['score'] >= 0.50) ? 0.65 : 0.40;

                if ($knnSalary > 0) {
                    $predictedSalary = ($knnWeight * $knnSalary) + ((1 - $knnWeight) * $regPredictedSalary);
                } else {
                    $predictedSalary = $regPredictedSalary;
                }

                $range = $mlr->getSalaryRange($predictedSalary);
                $confidence = $mlr->getConfidenceScore();
                $cleanCount = count($dataset);
                
                echo "<div style='display: inline-block; padding: 10px 22px; border-radius: 50px; background: rgba(52, 152, 219, 0.12); color: #2980b9; font-weight: 600; font-size: 0.95rem; margin-bottom: 1.5rem;'>";
                echo "<i class='fas fa-sparkles' style='margin-right: 8px; color: #3498db;'></i> AI Market Intelligence Engine";
                echo "</div>";

                // Unmatched Skills Warning Banner
                if (!empty($unmatchedSkills)) {
                    echo "<div style='background: rgba(241, 196, 15, 0.12); border: 1px solid rgba(241, 196, 15, 0.4); color: #9a7d0a; border-radius: 14px; padding: 14px 20px; margin-bottom: 1.8rem; text-align: left; font-size: 0.95rem; display: flex; align-items: center; gap: 12px;'>";
                    echo "<i class='fas fa-info-circle' style='font-size: 1.3rem; color: #f39c12;'></i>";
                    echo "<div><strong>Market Note:</strong> Active postings were not found for <strong>" . htmlspecialchars(implode(', ', $unmatchedSkills)) . "</strong>. Valuation is based on: <strong>" . htmlspecialchars(implode(', ', $matchedSkills)) . "</strong>.</div>";
                    echo "</div>";
                }

                // Skill Combination Recommendation Tip Banner
                if (!empty($suggestedSkills)) {
                    echo "<div style='background: rgba(52, 152, 219, 0.08); border: 1px solid rgba(52, 152, 219, 0.3); color: #2980b9; border-radius: 14px; padding: 14px 20px; margin-bottom: 1.8rem; text-align: left; font-size: 0.95rem; display: flex; align-items: center; gap: 12px;'>";
                    echo "<i class='fas fa-lightbulb' style='font-size: 1.3rem; color: #3498db;'></i>";
                    echo "<div><strong>Skill Combination Tip:</strong> Job listings requiring <strong>" . htmlspecialchars(implode(', ', $userSkillsArray)) . "</strong> frequently ask for <strong>" . htmlspecialchars(implode(', ', $suggestedSkills)) . "</strong>. Adding these skills will maximize your profile alignment!</div>";
                    echo "</div>";
                }

                echo "<h3 style='font-size: 2.2rem; color: var(--text-main); font-weight: 700; margin-bottom: 0.5rem;'>Estimated Market Salary</h3>";
                $titleDisplay = !empty($targetJobTitle) ? "for <strong>" . htmlspecialchars($targetJobTitle) . "</strong> (" . htmlspecialchars(implode(', ', $userSkillsArray)) . ")" : "for <strong>" . htmlspecialchars(implode(', ', $userSkillsArray)) . "</strong>";
                echo "<p style='color: var(--text-muted); font-size: 1.05rem; margin-bottom: 2rem;'>Projected market compensation $titleDisplay with <strong>$experience</strong> years of experience.</p>";
                
                echo "<div style='background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; padding: 2.5rem; border-radius: 20px; margin-bottom: 2rem; box-shadow: 0 12px 25px rgba(30,60,114,0.25);'>";
                echo "<span style='text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.85rem; opacity: 0.85; font-weight: 600;'>Estimated Market Value</span>";
                echo "<h2 style='font-size: 3.8rem; font-weight: 800; margin: 0.5rem 0 1rem 0; color: #ffffff; text-shadow: 0 2px 10px rgba(0,0,0,0.2);'>RS. " . number_format(round($predictedSalary)) . "</h2>";
                
                echo "<div style='display: flex; justify-content: center; gap: 2.5rem; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 1.2rem; margin-top: 1rem; font-size: 0.95rem;'>";
                echo "<div><i class='fas fa-arrow-down' style='color: #2ecc71; margin-right: 4px;'></i> Expected Low: <strong>RS. " . number_format(round($range['min'])) . "</strong></div>";
                echo "<div><i class='fas fa-arrow-up' style='color: #e74c3c; margin-right: 4px;'></i> Expected High: <strong>RS. " . number_format(round($range['max'])) . "</strong></div>";
                echo "</div>";
                echo "</div>";

                // Diagnostics / Metadata grid
                echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; text-align: left; margin-bottom: 2.5rem;'>";
                
                echo "<div style='background: #f8fafc; padding: 1.2rem; border-radius: 14px; border: 1px solid rgba(0,0,0,0.05);'>";
                echo "<div style='font-size: 0.85rem; color: var(--text-muted); font-weight: 600;'><i class='fas fa-chart-bar' style='color: #3498db; margin-right: 6px;'></i> Benchmarked Roles</div>";
                echo "<div style='font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-top: 4px;'>$cleanCount <span style='font-size: 0.85rem; font-weight: normal; color: #7f8c8d;'>Listings Evaluated</span></div>";
                echo "</div>";

                echo "<div style='background: #f8fafc; padding: 1.2rem; border-radius: 14px; border: 1px solid rgba(0,0,0,0.05);'>";
                echo "<div style='font-size: 0.85rem; color: var(--text-muted); font-weight: 600;'><i class='fas fa-shield-check' style='color: #2ecc71; margin-right: 6px;'></i> Prediction Reliability</div>";
                echo "<div style='font-size: 1.25rem; font-weight: 700; color: #27ae60; margin-top: 4px;'>" . $confidence['label'] . " <span style='font-size: 0.85rem;'>(" . $confidence['score'] . "%)</span></div>";
                echo "</div>";

                echo "<div style='background: #f8fafc; padding: 1.2rem; border-radius: 14px; border: 1px solid rgba(0,0,0,0.05);'>";
                echo "<div style='font-size: 0.85rem; color: var(--text-muted); font-weight: 600;'><i class='fas fa-layer-group' style='color: #9b59b6; margin-right: 6px;'></i> Skill Alignment</div>";
                echo "<div style='font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-top: 4px;'>" . ucfirst($matchType) . " Match</div>";
                echo "</div>";

                echo "</div>";

                // Benchmark Job Opportunities Section
                if (!empty($topKMatches)) {
                    echo "<div style='text-align: left; background: #f8fafc; padding: 2rem; border-radius: 18px; border: 1px solid rgba(0,0,0,0.06);'>";
                    echo "<div style='margin-bottom: 1.2rem;'>";
                    echo "<h4 style='font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 4px;'><i class='fas fa-briefcase' style='color: #3498db; margin-right: 8px;'></i> Benchmark Job Opportunities</h4>";
                    echo "<p style='font-size: 0.88rem; color: var(--text-muted); margin: 0;'>Active listings in our network with matching requirements that set this market range.</p>";
                    echo "</div>";

                    echo "<div style='display: flex; flex-direction: column; gap: 0.9rem;'>";
                    $top3 = array_slice($topKMatches, 0, 3);
                    foreach ($top3 as $match) {
                        echo "<div style='display: flex; justify-content: space-between; align-items: center; background: white; padding: 1.1rem 1.4rem; border-radius: 14px; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 3px 10px rgba(0,0,0,0.02); transition: transform 0.2s ease;'>";
                        echo "<div>";
                        echo "<div style='font-weight: 700; color: var(--text-main); font-size: 1.05rem; margin-bottom: 4px;'>" . htmlspecialchars($match['title']) . "</div>";
                        echo "<div style='font-size: 0.88rem; color: var(--text-muted); display: flex; align-items: center; gap: 12px;'>";
                        echo "<span><i class='fas fa-building' style='color: #95a5a6; margin-right: 4px;'></i> " . htmlspecialchars($match['employer']) . "</span>";
                        echo "<span><i class='fas fa-briefcase' style='color: #95a5a6; margin-right: 4px;'></i> " . $match['exp'] . " yrs exp</span>";
                        echo "</div>";
                        echo "</div>";
                        
                        echo "<div style='text-align: right; display: flex; align-items: center; gap: 1.2rem;'>";
                        echo "<div>";
                        echo "<div style='font-weight: 800; color: #27ae60; font-size: 1.15rem;'>RS. " . number_format($match['salary']) . "</div>";
                        echo "<div style='font-size: 0.8rem; color: #2980b9; font-weight: 600; background: rgba(52, 152, 219, 0.08); padding: 3px 8px; border-radius: 6px; display: inline-block; margin-top: 2px;'>" . round($match['score'] * 100) . "% Profile Match</div>";
                        echo "</div>";
                        
                        $jobSlug = urlencode(strtolower(str_replace(' ', '-', $match['title'])));
                        $jobUrl = "job/" . $match['job_id'] . "/" . $jobSlug;
                        echo "<a href='" . $jobUrl . "' target='_blank' class='btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold' style='font-size: 0.85rem;'>View Job <i class='fas fa-external-link-alt' style='font-size: 0.75rem; margin-left: 3px;'></i></a>";
                        echo "</div>";

                        echo "</div>";
                    }
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<i class='fas fa-search-minus' style='font-size: 3.5rem; color: var(--text-muted); margin-bottom: 1.5rem;'></i>";
                echo "<h3 style='font-size: 1.8rem; color: var(--text-main); font-weight: 700; margin-bottom: 1rem;'>Insufficient Data</h3>";
                echo "<p style='color: var(--text-muted); font-size: 1.1rem; margin: 0;'>We couldn't find enough job matches for <strong>" . htmlspecialchars(implode(', ', $userSkillsArray)) . "</strong> to make a reliable projection.</p>";
            }
            echo "</div>";
        }
    }

    private function fetchTrainingData($userSkillsArray, $targetJobTitle, $userExperience) {
        $rawDataset = [];
        $globalDataset = [];
        $matchType = 'exact';
        $matchedSkills = [];
        $unmatchedSkills = [];

        // 0. Detect which entered skills exist anywhere in the database
        foreach ($userSkillsArray as $skill) {
            $checkSql = "SELECT COUNT(*) as cnt FROM job_postings WHERE skills LIKE '%" . $this->conn->real_escape_string($skill) . "%'";
            $checkRes = $this->conn->query($checkSql);
            if ($checkRes && ($row = $checkRes->fetch_assoc()) && $row['cnt'] > 0) {
                $matchedSkills[] = $skill;
            } else {
                $unmatchedSkills[] = $skill;
            }
        }

        $totalUserSkills = count($userSkillsArray);
        $matchedRatio = $totalUserSkills > 0 ? (count($matchedSkills) / $totalUserSkills) : 1.0;

        // Fetch global market dataset for baseline trend scaling
        $globalSql = "SELECT j.job_id, j.title, j.workexperience, j.salary, j.skills, e.Fullname_E 
                      FROM job_postings j 
                      LEFT JOIN employerlogin e ON j.employer_id = e.employer_id";
        $globalRes = $this->conn->query($globalSql);
        $allJobs = [];
        if ($globalRes && $globalRes->num_rows > 0) {
            while ($row = $globalRes->fetch_assoc()) {
                $allJobs[] = $row;
                $globalDataset[] = [
                    'exp' => (float)$row['workexperience'],
                    'skill_match' => 0.5,
                    'salary' => (float)$row['salary']
                ];
            }
        }
        $globalDataset = $this->removeOutliersIQR($globalDataset);
        
        // Use matched skills subset for queries if some skills were unmatched
        $querySkills = !empty($matchedSkills) ? $matchedSkills : $userSkillsArray;

        // Optional Job Title Filter Condition
        $titleCondition = "";
        if (!empty($targetJobTitle)) {
            $titleCondition = " AND title LIKE '%" . $this->conn->real_escape_string($targetJobTitle) . "%'";
        }

        // 1. Exact Skill Match query (with optional title filter)
        $conditions = [];
        foreach ($querySkills as $skill) {
            $conditions[] = "skills LIKE '%" . $this->conn->real_escape_string($skill) . "%'";
        }
        $sql = "SELECT workexperience, salary, skills FROM job_postings WHERE " . implode(' AND ', $conditions) . $titleCondition;
        $result = $this->conn->query($sql);
        
        // Fallback: If title filter returned zero rows, drop title condition for dataset query
        if ((!$result || $result->num_rows == 0) && !empty($titleCondition)) {
            $sql = "SELECT workexperience, salary, skills FROM job_postings WHERE " . implode(' AND ', $conditions);
            $result = $this->conn->query($sql);
        }

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $skillMatch = $this->calculateJaccardSimilarity($querySkills, $row['skills']);
                $rawDataset[] = [
                    'exp' => (float)$row['workexperience'],
                    'skill_match' => $skillMatch,
                    'salary' => (float)$row['salary']
                ];
            }
        }
        
        // 2. Fallback: Partial Match query with minimum similarity thresholding
        if (count($rawDataset) < 3 && count($querySkills) > 0) {
            $conditions = [];
            foreach ($querySkills as $skill) {
                $conditions[] = "skills LIKE '%" . $this->conn->real_escape_string($skill) . "%'";
            }
            $sql = "SELECT workexperience, salary, skills FROM job_postings WHERE " . implode(' OR ', $conditions);
            $result = $this->conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                $matchType = 'partial';
                $rawDataset = []; // Reset to include filtered partial matches
                while ($row = $result->fetch_assoc()) {
                    $skillMatch = $this->calculateJaccardSimilarity($querySkills, $row['skills']);
                    if ($skillMatch >= 0.10) {
                        $rawDataset[] = [
                            'exp' => (float)$row['workexperience'],
                            'skill_match' => $skillMatch,
                            'salary' => (float)$row['salary']
                        ];
                    }
                }
            }
        }

        // 3. Fallback: Global market data if still insufficient
        if (empty($rawDataset)) {
            $rawDataset = $globalDataset;
            $matchType = 'general market';
        }

        $rawCount = count($rawDataset);
        $cleanDataset = $this->removeOutliersIQR($rawDataset);

        // 4. Calculate K-Nearest Neighbors (KNN) Cosine Distance Scores across all jobs
        $knnResults = $this->calculateKNNMatches($querySkills, $targetJobTitle, $userExperience, $allJobs);

        // 5. Calculate complementary skill recommendations for 1-2 skill queries
        $suggestedSkills = [];
        if (count($userSkillsArray) <= 2 && !empty($allJobs)) {
            $coOccurring = [];
            foreach ($allJobs as $job) {
                $jobSkillsArray = array_filter(array_map('trim', explode(',', $job['skills'])));
                $hasUserSkill = false;
                foreach ($querySkills as $qs) {
                    foreach ($jobSkillsArray as $js) {
                        if (strcasecmp(trim($qs), trim($js)) === 0) {
                            $hasUserSkill = true;
                            break 2;
                        }
                    }
                }
                if ($hasUserSkill) {
                    foreach ($jobSkillsArray as $s) {
                        $sClean = trim($s);
                        $isAlreadyUserSkill = false;
                        foreach ($userSkillsArray as $us) {
                            if (strcasecmp(trim($us), $sClean) === 0) {
                                $isAlreadyUserSkill = true;
                                break;
                            }
                        }
                        if (!$isAlreadyUserSkill && !empty($sClean)) {
                            $key = ucwords(strtolower($sClean));
                            $coOccurring[$key] = ($coOccurring[$key] ?? 0) + 1;
                        }
                    }
                }
            }
            arsort($coOccurring);
            $suggestedSkills = array_slice(array_keys($coOccurring), 0, 3);
        }

        return [
            'dataset' => $cleanDataset,
            'globalDataset' => $globalDataset,
            'topKMatches' => $knnResults['topKMatches'],
            'knnSalary' => $knnResults['knnSalary'],
            'rawCount' => $rawCount,
            'matchType' => $matchType,
            'unmatchedSkills' => $unmatchedSkills,
            'matchedSkills' => $matchedSkills,
            'matchedRatio' => $matchedRatio,
            'suggestedSkills' => $suggestedSkills
        ];
    }

    private function calculateKNNMatches($userSkills, $targetJobTitle, $userExperience, $allJobs) {
        if (empty($allJobs)) return ['topKMatches' => [], 'knnSalary' => 0];

        $userSkillsLower = array_map('strtolower', $userSkills);
        $userSkillsVector = array_count_values($userSkillsLower);
        $userMagnitude = 0;
        foreach ($userSkillsVector as $cnt) {
            $userMagnitude += pow($cnt, 2);
        }

        $scoredJobs = [];
        foreach ($allJobs as $job) {
            $jobSkills = array_filter(array_map('trim', explode(',', strtolower($job['skills']))));
            
            // Skill Cosine Similarity
            $cosineScore = $this->cosineSimilarityVector($userSkillsVector, $userMagnitude, $jobSkills);

            // Title Similarity
            $titleScore = 0.5;
            if (!empty($targetJobTitle)) {
                $titleScore = $this->calculateTitleSimilarity($targetJobTitle, $job['title']);
            }

            // Experience Proximity Score (1 - delta/10)
            $expDelta = abs($userExperience - (float)$job['workexperience']);
            $expScore = max(0, 1 - ($expDelta / 10.0));

            // Composite Weighted KNN Score
            if (!empty($targetJobTitle)) {
                $compositeScore = (0.45 * $cosineScore) + (0.35 * $titleScore) + (0.20 * $expScore);
            } else {
                $compositeScore = (0.70 * $cosineScore) + (0.30 * $expScore);
            }

            $scoredJobs[] = [
                'job_id' => $job['job_id'],
                'title' => $job['title'],
                'employer' => !empty($job['Fullname_E']) ? $job['Fullname_E'] : 'Verified Hiring Manager',
                'salary' => (float)$job['salary'],
                'exp' => (int)$job['workexperience'],
                'score' => $compositeScore
            ];
        }

        // Sort scored jobs by composite score descending
        usort($scoredJobs, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Top K=5 nearest neighbors
        $topK = array_slice($scoredJobs, 0, 5);

        // Distance weighted KNN salary formula
        $sumWeight = 0;
        $weightedSalarySum = 0;
        foreach ($topK as $kJob) {
            if ($kJob['score'] > 0.05) {
                $weightedSalarySum += $kJob['score'] * $kJob['salary'];
                $sumWeight += $kJob['score'];
            }
        }

        $knnSalary = $sumWeight > 0 ? ($weightedSalarySum / $sumWeight) : 0;

        return [
            'topKMatches' => $topK,
            'knnSalary' => $knnSalary
        ];
    }

    private function cosineSimilarityVector($userVector, $userMag, $jobSkills) {
        if ($userMag == 0 || empty($jobSkills)) return 0;
        $jobVector = array_count_values($jobSkills);
        
        $dotProduct = 0;
        $jobMag = 0;
        foreach ($userVector as $skill => $count) {
            $dotProduct += $count * ($jobVector[$skill] ?? 0);
        }
        foreach ($jobVector as $count) {
            $jobMag += pow($count, 2);
        }

        if ($jobMag == 0) return 0;
        return $dotProduct / (sqrt($userMag) * sqrt($jobMag));
    }

    private function calculateTitleSimilarity($title1, $title2) {
        if (empty($title1) || empty($title2)) return 0.5;
        $t1Tokens = array_filter(explode(' ', strtolower(preg_replace('/[^a-z0-9 ]/i', '', $title1))));
        $t2Tokens = array_filter(explode(' ', strtolower(preg_replace('/[^a-z0-9 ]/i', '', $title2))));

        if (empty($t1Tokens) || empty($t2Tokens)) return 0.5;

        $intersection = count(array_intersect($t1Tokens, $t2Tokens));
        $union = count(array_unique(array_merge($t1Tokens, $t2Tokens)));

        return $union > 0 ? ($intersection / $union) : 0.5;
    }

    private function calculateJaccardSimilarity($userSkills, $jobSkillsStr) {
        if (empty($jobSkillsStr)) return 0.5;
        
        $jobSkills = array_filter(array_map('trim', explode(',', strtolower($jobSkillsStr))));
        $userSkillsLower = array_map('strtolower', $userSkills);

        if (empty($jobSkills) || empty($userSkillsLower)) return 0.5;

        $intersection = count(array_intersect($userSkillsLower, $jobSkills));
        $userCount = count($userSkillsLower);
        $jobCount = count($jobSkills);

        // Compute balanced overlap score: User coverage of Job * Job coverage of User
        $userCoverage = $userCount > 0 ? ($intersection / $userCount) : 0;
        $jobCoverage  = $jobCount > 0  ? ($intersection / $jobCount)  : 0;

        // Weighted Harmonic Mean of coverage
        if ($userCoverage + $jobCoverage == 0) return 0.2;
        return (2 * $userCoverage * $jobCoverage) / ($userCoverage + $jobCoverage);
    }

    private function removeOutliersIQR($dataset) {
        $n = count($dataset);
        if ($n < 4) return $dataset; // IQR requires at least 4 items for meaningful quartiles

        $salaries = array_column($dataset, 'salary');
        sort($salaries);

        $q1 = $this->getPercentile($salaries, 0.25);
        $q3 = $this->getPercentile($salaries, 0.75);
        $iqr = $q3 - $q1;

        $lowerBound = max(0, $q1 - 1.5 * $iqr);
        $upperBound = $q3 + 1.5 * $iqr;

        $filtered = [];
        foreach ($dataset as $row) {
            if ($row['salary'] >= $lowerBound && $row['salary'] <= $upperBound) {
                $filtered[] = $row;
            }
        }

        return !empty($filtered) ? $filtered : $dataset;
    }

    private function getPercentile($sortedArray, $percentile) {
        $count = count($sortedArray);
        $index = $percentile * ($count - 1);
        $fraction = $index - floor($index);
        $lower = floor($index);
        $upper = ceil($index);
        return $sortedArray[$lower] + $fraction * ($sortedArray[$upper] - $sortedArray[$lower]);
    }
}

/**
 * Multivariate Linear Regression Engine with Global Market Growth Fallback
 * Model: Salary = meanY + b1 * (sqrt(Exp) - meanX1) + b2 * (SkillMatch - meanX2)
 */
class MultivariateLinearRegression {
    private $meanX1 = 0; // mean sqrt(exp)
    private $meanX2 = 0; // mean skill_match
    private $meanY = 0;  // mean salary
    private $b1 = 0;     // Slope for sqrt(experience)
    private $b2 = 0;     // Slope for skill match ratio
    private $sampleCount = 0;
    private $rSquared = 0.85;

    public function train($dataset, $globalDataset = []) {
        $this->sampleCount = count($dataset);
        if ($this->sampleCount === 0) return;

        // Calculate centroid means
        $sumX1 = 0; $sumX2 = 0; $sumY = 0;
        foreach ($dataset as $row) {
            $sumX1 += sqrt(max(0, $row['exp']));
            $sumX2 += (float)$row['skill_match'];
            $sumY  += (float)$row['salary'];
        }

        $this->meanX1 = $sumX1 / $this->sampleCount;
        $this->meanX2 = $sumX2 / $this->sampleCount;
        $this->meanY  = $sumY / $this->sampleCount;

        // Realistic slope caps based on mean salary (prevents wild mathematical explosions)
        $maxB1Cap = max(3000, $this->meanY * 0.25); // Max experience boost per sqrt(year)
        $maxB2Cap = max(5000, $this->meanY * 0.35); // Max skill match boost

        // Calculate variance of experience feature
        $varX1 = 0;
        foreach ($dataset as $row) {
            $varX1 += pow(sqrt(max(0, $row['exp'])) - $this->meanX1, 2);
        }

        // Try training Regularized OLS (Ridge Regression) if N > 1 and variance > 0.01
        $trainedSkillSlope = false;
        if ($this->sampleCount > 1 && $varX1 > 0.01) {
            $N = $this->sampleCount;
            $lambda = 0.05; // Ridge regularization factor to stabilize matrix determinant
            $sumX1Sq = $lambda; $sumX2Sq = $lambda; $sumX1X2 = 0;
            $sumX1Y = 0; $sumX2Y = 0;

            foreach ($dataset as $row) {
                $x1 = sqrt(max(0, $row['exp']));
                $x2 = (float)$row['skill_match'];
                $y  = (float)$row['salary'];

                $sumX1Sq += $x1 * $x1;
                $sumX2Sq += $x2 * $x2;
                $sumX1X2 += $x1 * $x2;
                $sumX1Y  += $x1 * $y;
                $sumX2Y  += $x2 * $y;
            }

            $detA = $N * ($sumX1Sq * $sumX2Sq - $sumX1X2 * $sumX1X2)
                  - $sumX1 * ($sumX1 * $sumX2Sq - $sumX1X2 * $sumX2)
                  + $sumX2 * ($sumX1 * $sumX1X2 - $sumX1Sq * $sumX2);

            if (abs($detA) > 1e-5) {
                $detB1 = $N * ($sumX1Y * $sumX2Sq - $sumX1X2 * $sumX2Y)
                       - $sumY * ($sumX1 * $sumX2Sq - $sumX1X2 * $sumX2)
                       + $sumX2 * ($sumX1 * $sumX2Y - $sumX1Y * $sumX2);

                $detB2 = $N * ($sumX1Sq * $sumX2Y - $sumX1Y * $sumX1X2)
                       - $sumX1 * ($sumX1 * $sumX2Y - $sumX1Y * $sumX2)
                       + $sumY * ($sumX1 * $sumX1X2 - $sumX1Sq * $sumX2);

                $calculatedB1 = $detB1 / $detA;
                $calculatedB2 = $detB2 / $detA;

                if ($calculatedB1 > 100) {
                    $this->b1 = min($maxB1Cap, max(0, $calculatedB1));
                    $trainedSkillSlope = true;
                }
                if ($calculatedB2 > 0) {
                    $this->b2 = min($maxB2Cap, max(0, $calculatedB2));
                }
            }
        }

        // Global Market Growth Scaling Fallback
        if (!$trainedSkillSlope) {
            $globalSlope = $this->calculateGlobalExperienceSlope($globalDataset);
            $defaultFloor = max(3500, $this->meanY * 0.10);
            $this->b1 = min($maxB1Cap, max($globalSlope, $defaultFloor));
        }

        // Cap b2 if unset
        if ($this->b2 <= 0) {
            $this->b2 = min($maxB2Cap, $this->meanY * 0.15);
        }

        $this->calculateRSquared($dataset);
    }

    private function calculateGlobalExperienceSlope($globalDataset) {
        if (empty($globalDataset) || count($globalDataset) < 2) return 5000;

        $n = count($globalDataset);
        $sumX = 0; $sumY = 0;
        foreach ($globalDataset as $row) {
            $sumX += sqrt(max(0, $row['exp']));
            $sumY += (float)$row['salary'];
        }
        $meanX = $sumX / $n;
        $meanY = $sumY / $n;

        $num = 0; $den = 0;
        foreach ($globalDataset as $row) {
            $x = sqrt(max(0, $row['exp']));
            $y = (float)$row['salary'];
            $num += ($x - $meanX) * ($y - $meanY);
            $den += pow($x - $meanX, 2);
        }

        if ($den > 1e-4) {
            $slope = $num / $den;
            return max(2500, min(12000, $slope));
        }
        return 5000;
    }

    private function calculateRSquared($dataset) {
        if (count($dataset) < 2) {
            $this->rSquared = 0.75;
            return;
        }

        $ssTotal = 0;
        $ssRes = 0;

        foreach ($dataset as $row) {
            $actual = $row['salary'];
            $predicted = $this->predict($row['exp'], $row['skill_match']);
            $ssTotal += pow($actual - $this->meanY, 2);
            $ssRes += pow($actual - $predicted, 2);
        }

        if ($ssTotal > 0) {
            $r2 = 1 - ($ssRes / $ssTotal);
            $this->rSquared = max(0.55, min(0.95, $r2));
        } else {
            $this->rSquared = 0.75;
        }
    }

    public function predict($experience, $matchedRatio = 1.0) {
        $x1 = sqrt(max(0, $experience));
        
        // Target skill match pivots around meanX2 scaled by candidate's matched skills ratio
        $targetMatch = $this->meanX2 * max(0.2, min(1.0, $matchedRatio));
        $x2 = $targetMatch;
        
        // Pivot around centroid mean with regularized/clamped slopes
        $prediction = $this->meanY + ($this->b1 * ($x1 - $this->meanX1)) + ($this->b2 * ($x2 - $this->meanX2));
        
        // Enforce realistic bounds relative to dataset mean (0.4x min, 2.2x max)
        $lowerBound = max(15000, $this->meanY * 0.40);
        $upperBound = max(60000, $this->meanY * 2.20);

        return min($upperBound, max($lowerBound, $prediction));
    }

    public function getSalaryRange($predictedSalary) {
        $margin = 0.12 + (1 - $this->rSquared) * 0.15;
        return [
            'min' => max(10000, $predictedSalary * (1 - $margin)),
            'max' => $predictedSalary * (1 + $margin)
        ];
    }

    public function getConfidenceScore() {
        $score = round($this->rSquared * 100);
        if ($this->sampleCount >= 10 && $score >= 80) {
            $label = "High";
        } else if ($this->sampleCount >= 3 && $score >= 60) {
            $label = "Moderate (Regularized ML)";
        } else {
            $label = "Baseline Market Model";
        }
        return ['score' => $score, 'label' => $label];
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
    <link rel="icon" type="image/png" href="../img/favicon.png">
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
                    <li><a href="appliedjobs" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'appliedjobs.php') ? 'active' : ''; ?>">Applied Jobs</a></li>
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