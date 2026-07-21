<?php
$file = 'jobseeker/joblist.php';
$content = file_get_contents($file);

// 1. Update JS
$js_target = "const searchQuery = urlParams.get('title');";
$js_replace = "const searchQuery = urlParams.get('title') || urlParams.get('keyword');";
$content = str_replace($js_target, $js_replace, $content);

// 2. Update input field
$input_target = "<input type=\"text\" name=\"title\" placeholder=\"Search by Job Title...\" value=\"<?= isset(\$_GET['title']) ? \$_GET['title'] : ''; ?>\"";
$input_replace = "<?php \$displaySearch = isset(\$_GET['title']) ? \$_GET['title'] : (isset(\$_GET['keyword']) ? \$_GET['keyword'] : ''); ?>\n                <input type=\"text\" name=\"title\" placeholder=\"Search by Job Title or Skill...\" value=\"<?= htmlspecialchars(\$displaySearch); ?>\"";
$content = str_replace($input_target, $input_replace, $content);

// 3. Update SQL
$sql_target = "\$titleSearch = '';\n            if (isset(\$_GET['title']) && !empty(\$_GET['title'])) {\n                \$titleSearch = mysqli_real_escape_string(\$conn, \$_GET['title']);\n            }";
$sql_replace = "\$titleSearch = '';\n            if (isset(\$_GET['title']) && !empty(\$_GET['title'])) {\n                \$titleSearch = mysqli_real_escape_string(\$conn, \$_GET['title']);\n            } elseif (isset(\$_GET['keyword']) && !empty(\$_GET['keyword'])) {\n                \$titleSearch = mysqli_real_escape_string(\$conn, \$_GET['keyword']);\n            }";
$content = str_replace($sql_target, $sql_replace, $content);

$fetch_target = "\$fetch = \"SELECT j.job_id, j.title, j.location, j.salary, j.status, e.employer_id, e.Fullname_E \n                    FROM job_postings AS j \n                    JOIN employerlogin AS e \n                    ON j.employer_id = e.employer_id \n                    WHERE j.status = 1\";";
$fetch_replace = "\$fetch = \"SELECT j.job_id, j.title, j.location, j.salary, j.status, j.skills, e.employer_id, e.Fullname_E \n                    FROM job_postings AS j \n                    JOIN employerlogin AS e \n                    ON j.employer_id = e.employer_id \n                    WHERE j.status = 1\";";
$content = str_replace($fetch_target, $fetch_replace, $content);

$where_target = "if (!empty(\$titleSearch)) {\n                \$fetch .= \" AND j.title LIKE '%\$titleSearch%'\";\n            }";
$where_replace = "if (!empty(\$titleSearch)) {\n                \$fetch .= \" AND (j.title LIKE '%\$titleSearch%' OR j.skills LIKE '%\$titleSearch%')\";\n            }";
$content = str_replace($where_target, $where_replace, $content);

file_put_contents($file, $content);
echo "joblist updated";
?>
