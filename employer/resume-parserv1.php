<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    header("Location: ../index.php");
    exit;
}

class CVParser1 {
    private $conn;
    private $employerId;
    private $jobId;

    public function __construct($connection, $employerId, $jobId) {
        $this->conn = $connection;
        $this->employerId = $employerId;
        $this->jobId = $jobId;
    }

    public function extractEntities($data) {
        $entities = [];
        $entities['names'] = $data['FullName'];
        $entities['emails'] = $data['EmailAddress'];
        $entities['phones'] = $data['Contact'];
        
        $entities['skills'] = array_map('trim', explode(',', $data['skill']));
        return $entities;
    }

    public function parseSections($data) {
        $sections = [];
        $sections['education'] = $data['Education'];
        $sections['experience'] = $data['Workexp'];
        return $sections;
    }

    public function calculateRelevance($jobSkills, $candidateSkills, $candidateSections) {
        $score = 0;

        foreach ($candidateSkills as $skill) {
            if (in_array($skill, $jobSkills)) {
                $score++;
            }
        }

        if (stripos($candidateSections['education'], 'Bachelor') !== false) {
            $score++;
        } elseif (stripos($candidateSections['education'], 'Master') !== false) {
            $score += 2; 
        }

        return $score;
    }

    public function process() {
        $select = "SELECT s.Fullname_S, s.Email_S, j.description, j.requirements, j.location, j.skills, 
                          r.pdffile, r.sk_id, r.FullName, r.EmailAddress, r.Contact, 
                          r.Country, r.Provience, r.City, r.Address, r.Education, r.Workexp, 
                          r.skill, a.date_applied, a.status, s.Seeker_id 
                   FROM seekerlogin AS s 
                   LEFT JOIN application AS a ON s.Seeker_id = a.Seeker_id AND a.job_id = $this->jobId
                   JOIN job_postings AS j ON j.job_id = $this->jobId 
                   JOIN seekerresume AS r ON s.Seeker_id = r.Seeker_id 
                   WHERE a.Seeker_id IS NULL AND j.employer_id = $this->employerId";
    
        $query = mysqli_query($this->conn, $select);
        $rankedResults = [];
    
        if (mysqli_num_rows($query) > 0) {
            while ($data = mysqli_fetch_assoc($query)) {
                $entities = $this->extractEntities($data);
                $sections = $this->parseSections($data);
                
                $jobSkills = ['PHP', 'JavaScript', 'HTML', 'CSS', 'MySQL', 'Java', 'React', 'Node.js', 'Django', 'Ruby', 'Laravel', 'Angular', 'Vue.js', 'TypeScript', 'Swift', 'Kotlin', 'C#', 'C++', 'Go', 'Rust', 'GraphQL', 'RESTful APIs', 'WordPress', 'Docker', 'Kubernetes', 'AWS', 'Azure', 'Git', 'jQuery', 'Bootstrap', 'SASS/SCSS', 'Vuex', 'Firebase', 'WebSocket']; 
                
                $score = $this->calculateRelevance($jobSkills, $entities['skills'], $sections);

                // Only add candidates with a score greater than 0
                if ($score > 0) {
                    $rankedResults[] = array_merge($data, ['score' => $score]);
                }
            }
        } else {
            echo "No candidates found for the specified job.";
            return [];
        }
    
        usort($rankedResults, function($a, $b) {
            return $b['score'] - $a['score'];
        });
    
        return $rankedResults;
    }

    public function displayResults($rankedCVs) {
        if(empty($rankedCVs)) {
            echo "<div style='grid-column: 1 / -1; text-align: center; padding: 3rem; background: rgba(255,255,255,0.6); border-radius: 15px; border: 1px dashed var(--border-color); color: var(--text-muted);'><p>No recommended candidates found yet.</p></div>";
            return;
        }
        foreach ($rankedCVs as $results) {
            $name = htmlspecialchars($results['FullName']);
            $initial = strtoupper(substr($name, 0, 1));
            echo '<a href="candidate/' . htmlspecialchars($results['Seeker_id']) . '" style="text-decoration: none;">
                    <div class="glass-card hover-lift" style="padding: 2rem; border-radius: 15px; display: flex; flex-direction: column; transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 4px solid #f1c40f;">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 1.5rem;">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #f1c40f, #f39c12); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold;">
                                '.$initial.'
                            </div>
                            <div>
                                <h5 style="margin: 0; font-size: 1.2rem; font-weight: 700; color: var(--text-main);">'.$name.'</h5>
                                <span style="color: var(--text-muted); font-size: 0.85rem;"><i class="fas fa-envelope"></i> '.htmlspecialchars($results['EmailAddress']).'</span>
                            </div>
                        </div>
                        <div style="border-top: 1px solid var(--border-color); padding-top: 1rem; font-size: 0.9rem; color: var(--text-muted); display: flex; justify-content: space-between;">
                            <span><i class="fas fa-phone" style="color: var(--primary-light);"></i> '.htmlspecialchars($results['Contact']).'</span>
                            <span class="badge-pill" style="background: rgba(241, 196, 15, 0.1); color: #f39c12; font-size: 0.75rem; padding: 3px 8px;"><i class="fas fa-star"></i> Rec</span>
                        </div>
                    </div>
                  </a>';
        }
    }
}

// Assuming you have already established the connection $conn
$id = $_SESSION['username'];
$jid = $_GET['j_id'];

$cvParser = new CVParser1($conn, $id, $jid);
$rankedCVs = $cvParser->process();
$cvParser->displayResults($rankedCVs);
