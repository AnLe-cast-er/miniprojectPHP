<!DOCTYPE HTML>
<html>
<head>
<style>.error {color: #FF0000;}</style>
</head>
<body>

<?php
    $errors = [];
    $inputs = [];

    // sanitize only (no HTML escaping here)
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        return $data;
    }

    // helper để escape khi in ra HTML
    function e($s) {
        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        for ($i = 1; $i <= 10; $i++) {
            $field = "input$i";
            $raw = $_POST[$field] ?? '';
            $value = test_input($raw);

            // check required on the sanitized value
            if ($value === '') {
                $errors[$field] = "Input $i is required";
            } else {
                // validate on sanitized value
                if (!preg_match("/^[0-9]+$/", $value)) {
                    $errors[$field] = "Only numbers (0-9) allowed";
                } else {
                    $inputs[$i] = $value; // safe numeric string
                }
            }
        }

        if (empty($errors)) {
            // Group 1: sort desc
            $group1 = $inputs;
            rsort($group1);
            $g1 = implode(" - ", $group1);

            // Group 2: divisible by 3 desc
            $group2 = array_filter($inputs, fn($n) => $n % 3 == 0);
            rsort($group2);
            $g2 = $group2 ? implode(" - ", $group2) : "No numbers divisible by 3";

            // Group 3: divisible by 5 asc
            $group3 = array_filter($inputs, fn($n) => $n % 5 == 0);
            sort($group3);
            $g3 = $group3 ? implode(" - ", $group3) : "No numbers divisible by 5";
        }
    }
?>

<h2>PHP Group</h2>
<form method="POST">

<?php for ($i = 1; $i <= 10; $i++): ?>
    <?php
        // chuẩn bị giá trị hiển thị: nếu có POST thì sanitize rồi escape khi in
        $raw = $_POST["input$i"] ?? '';
        $display = $raw !== '' ? e(test_input($raw)) : '';
    ?>
    Input <?= $i ?>:
    <input type="text" name="input<?= $i ?>" value="<?php echo $display; ?>">
    <span class="error"><?php echo $errors["input$i"] ?? ''; ?></span>
    <br><br>
<?php endfor; ?>

<input type="submit" value="Submit">

</form>

<?php
// Hiển thị các input hợp lệ (đã validate & sanitize) — vẫn escape khi in ra
if (!empty($inputs)) {
    echo "<h2>Your Input number:</h2>";
    for ($i = 1; $i <= 10; $i++) {
        if (isset($inputs[$i])) {
            echo "Input $i: " . e($inputs[$i]) . "<br>";
        }
    }
}
?>

<h2>Groups Result:</h2>

<b>Group 1 (desc):</b>
<?php echo isset($g1) ? e($g1) : "No result yet"; ?>
<br>

<b>Group 2 (%3 desc):</b>
<?php echo isset($g2) ? e($g2) : "No result yet"; ?>
<br>

<b>Group 3 (%5 asc):</b>
<?php echo isset($g3) ? e($g3) : "No result yet"; ?>
<br>

</body>
</html>
