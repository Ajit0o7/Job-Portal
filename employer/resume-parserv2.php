<?php
require 'vendor/autoload.php';

use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;

class CVParser {
    private $conn;
    private $employerId;
    private $jobId;

    public function __construct($connection, $employerId, $jobId) {
        $this->conn = $connection;
        $this->employerId = $employerId;
        $this->jobId = $jobId;
    }

    public function extractTextFromPDF($filePath) {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        return $pdf->getText();
    }

    public function extractTextFromDOCX($filePath) {
        $phpWord = IOFactory::load($filePath);
        $text = '';
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . "\n";
                }
            }
        }
        return $text;
    }

    public function extractEntities($text) {
        $entities = [];
        if (preg_match_all('/\b(Mr|Mrs|Miss|Ms|Dr|Prof)\.?\s+[A-Z][a-zA-Z]*\s+[A-Z][a-zA-Z]*\b/i', $text, $matches)) {
            $entities['names'] = $matches[0];
        }
        if (preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i', $text, $matches)) {
            $entities['emails'] = $matches[0];
        }
        if (preg_match('/\+?[0-9]{1,4}?[-.\s]?(\(?\d{1,4}?\)?[-.\s]?)?(\d[-.\s]?){7,13}/', $text, $matches)) {
            $entities['phones'] = $matches[0];
        }
        if (preg_match_all('/\b\d{1,2}[-\/]\d{1,2}[-\/]\d{2,4}\b|\b\d{4}\b/', $text, $matches)) {
            $entities['dates'] = $matches[0];
        }

        $skills = ['PHP', 'JavaScript', 'HTML', 'CSS', 'MySQL', 'Java', 'Python', 'React', 'Node.js', 'Django', 'Ruby', 'Laravel', 'Angular', 'Vue.js', 'TypeScript', 'Swift', 'Kotlin', 'C#', 'C++', 'Go', 'Rust', 'GraphQL', 'RESTful APIs', 'WordPress', 'Docker', 'Kubernetes', 'AWS', 'Azure', 'Git', 'jQuery', 'Bootstrap', 'SASS/SCSS', 'Vuex', 'Firebase', 'WebSocket'];
        foreach ($skills as $skill) {
            if (stripos($text, $skill) !== false) {
                $entities['skills'][] = $skill;
            }
        }
        return $entities;
    }

    public function parseSections($text) {
        $sections = [];
        $sections['education'] = $this->extractSection($text, 'Education');
        $sections['experience'] = $this->extractSection($text, 'Experience');
        $sections['skills'] = $this->extractSection($text, 'Skills');

        $sections['degrees'] = $this->extractDegrees($sections['education']);
        $sections['jobs'] = $this->extractJobs($sections['experience']);

        return $sections;
    }

    private function extractSection($text, $sectionTitle) {
        $pattern = '/' . preg_quote($sectionTitle) . ':?\s*(.*?)(?=\n[A-Z]|\z)/is';
        if (preg_match($pattern, $text, $matches)) {
            return trim($matches[1]);
        }
        return '';
    }

    private function extractDegrees($educationText) {
        $degrees = [];
        $pattern = '/(Bachelor|Master|Ph\.D|B\.Sc|M\.Sc|MBA|Associate|Diploma|Certificate)\s*(of|in)?\s*([A-Za-z\s]+)?/i';
        if (preg_match_all($pattern, $educationText, $matches)) {
            $degrees = array_map('trim', $matches[0]);
        }
        return $degrees;
    }

    private function extractJobs($experienceText) {
        $jobs = [];
        $pattern = '/(Software Engineer|Developer|Manager|Consultant|Analyst|Administrator|Coordinator|Intern|Assistant)\b/i';
        if (preg_match_all($pattern, $experienceText, $matches)) {
            $jobs = array_map('trim', $matches[0]);
        }
        return $jobs;
    }

    private function tokenizeText($text) {
        $text = strtolower($text);
        $text = preg_replace('/[^\w\s]/', '', $text);
        return array_filter(explode(' ', $text));
    }

    public function calculateRelevance($jobTokens, $cvTokens, $cvSections) {
        $score = 0;

        foreach ($cvTokens as $token) {
            if (in_array($token, $jobTokens)) {
                $score++;
            }
        }

        $desiredDegrees = ['Bachelor', 'Master', 'Ph.D', 'M.Sc', 'B.Sc'];
        foreach ($cvSections['degrees'] as $degree) {
            foreach ($desiredDegrees as $desired) {
                if (stripos($degree, $desired) !== false) {
                    $score += 1; 
                }
            }
        }

        $desiredJobs = ['Developer', 'Engineer', 'Manager', 'Software Engineer', 'Consultant', 'Analyst'];
        foreach ($cvSections['jobs'] as $job) {
            foreach ($desiredJobs as $desired) {
                if (stripos($job, $desired) !== false) {
                    $score += 1; 
                }
            }
        }

        return $score;
    }

    public function processMultipleCVs($files, $dbResults) {
        $results = [];
        foreach ($files as $file) {
            $fileType = pathinfo($file, PATHINFO_EXTENSION);
            if ($fileType === 'pdf') {
                $cvText = $this->extractTextFromPDF($file);
            } elseif ($fileType === 'docx') {
                $cvText = $this->extractTextFromDOCX($file);
            } else {
                continue; 
            }

            $entities = $this->extractEntities($cvText);
            $sections = $this->parseSections($cvText);

            $fullName = ''; 
            foreach ($dbResults as $dbResult) {
                if ($dbResult['pdffile'] === $file) {
                    $fullName = $dbResult['Fullname_S'];
                    $Email = $dbResult['Email_S'];
                    $dateapplied = $dbResult['date_applied'];
                    $seeker_id = $dbResult['Seeker_id'];
                    break; 
                }
            }

            $results[] = [
                'file' => $file,
                'entities' => $entities,
                'sections' => $sections,
                'Fullname_S' => $fullName,
                'Email_S' => $Email,
                'date_applied' => $dateapplied,
                'Seeker_id' => $seeker_id,
            ];
        }
        return $results;
    }

    public function rankCVs($cvResults, $jobDescription) {
        $rankedResults = [];
        $jobTokens = $this->tokenizeText($jobDescription);

        foreach ($cvResults as $result) {
            $flatEntities = array_map(function($entity) {
                return is_array($entity) ? implode(' ', $entity) : $entity;
            }, $result['entities']);

            $cvText = implode(' ', $flatEntities);
            $cvTokens = $this->tokenizeText($cvText);
            $score = $this->calculateRelevance($jobTokens, $cvTokens, $result['sections']);

            $result['score'] = $score;
            $rankedResults[] = $result;
        }

        usort($rankedResults, function($a, $b) {
            return $b['score'] - $a['score'];
        });

        return $rankedResults;
    }

    public function process() {
        $select = "SELECT s.Fullname_S, s.Email_S, j.description, j.requirements, j.location, j.skills, r.pdffile, r.sk_id, r.FullName, r.EmailAddress, r.Contact, r.Country, r.Provience, r.City, r.Address, r.Education, r.Workexp, r.skill, a.date_applied, a.status, s.Seeker_id 
                   FROM seekerlogin AS s 
                   JOIN application AS a ON s.Seeker_id = a.Seeker_id 
                   JOIN job_postings AS j ON a.job_id = j.job_id 
                   JOIN seekerresume AS r ON s.Seeker_id = r.Seeker_id 
                   WHERE a.employer_id = $this->employerId AND j.job_id = $this->jobId AND a.status='applied'";

        $query = mysqli_query($this->conn, $select);

        $files = [];
        $dbResults = [];
        $jobDescription = '';

        if (mysqli_num_rows($query) > 0) {
            while ($result = mysqli_fetch_assoc($query)) {
                $files[] = $result['pdffile'];
                $dbResults[] = $result;

                $jobDescription .= "Job Description: " . $result['description'] . "<br>" .
                                   "Requirements: " . $result['requirements'] . "<br>" .
                                   "Location: " . $result['location'] . "<br>" .
                                   "Skills: " . $result['skills'] . "<br><br>";
            }
        } else {
            echo "<div style='grid-column: 1 / -1; text-align: center; padding: 3rem; background: rgba(255,255,255,0.6); border-radius: 15px; border: 1px dashed var(--border-color); color: var(--text-muted);'><p>No candidates available for AI matching.</p></div>";
            return [];
        }

        $allResults = $this->processMultipleCVs($files, $dbResults);
        $rankedCVs = $this->rankCVs($allResults, $jobDescription);
        return $rankedCVs;
    }

    public function displayResults($rankedCVs) {
        if(empty($rankedCVs)) {
            echo "<div style='grid-column: 1 / -1; text-align: center; padding: 3rem; background: rgba(255,255,255,0.6); border-radius: 15px; border: 1px dashed var(--border-color); color: var(--text-muted);'><p>No AI matches found yet.</p></div>";
            return;
        }
        foreach ($rankedCVs as $results) {
            $name = htmlspecialchars($results['Fullname_S']);
            $initial = strtoupper(substr($name, 0, 1));
            echo '<a href="candidate/' . htmlspecialchars($results['Seeker_id']) . '" style="text-decoration: none;">
                    <div class="glass-card hover-lift" style="padding: 2rem; border-radius: 15px; display: flex; flex-direction: column; transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 4px solid #9b59b6;">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 1.5rem;">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #9b59b6, #8e44ad); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold;">
                                '.$initial.'
                            </div>
                            <div>
                                <h5 style="margin: 0; font-size: 1.2rem; font-weight: 700; color: var(--text-main);">'.$name.'</h5>
                                <span style="color: var(--text-muted); font-size: 0.85rem;"><i class="fas fa-envelope"></i> '.htmlspecialchars($results['Email_S']).'</span>
                            </div>
                        </div>
                        <div style="border-top: 1px solid var(--border-color); padding-top: 1rem; font-size: 0.9rem; color: var(--text-muted); display: flex; justify-content: space-between; align-items: center;">
                            <span><i class="fas fa-calendar" style="color: var(--primary-light);"></i> '.date('M d, Y', strtotime($results['date_applied'])).'</span>
                            <span class="badge-pill" style="background: rgba(155, 89, 182, 0.1); color: #8e44ad; font-weight: 800; font-size: 0.8rem;"><i class="fas fa-bolt"></i> Match: '.htmlspecialchars($results['score']).'</span>
                        </div>
                    </div>
                  </a>';
        }
    }
}

$id = $_SESSION['username'];
$jid = $_GET['j_id'];

$cvParser = new CVParser($conn, $id, $jid);
$rankedCVs = $cvParser->process();
$cvParser->displayResults($rankedCVs);
?>
