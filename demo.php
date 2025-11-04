<!DOCTYPE HTML>
<html>
<head>
<style>.error {color: #FF0000;}</style>
</head>
<body>

<?php
    $errors = [];
    $inputs = [];
    $result = [];

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        for ($i = 1; $i <= 10; $i++) {
            $field = "input$i";
                if (empty($_POST[$field]) === "") {
                    $errors[$field] = "Input $i is required";
                } else {
                    if (!preg_match("/^[0-9]+$/", $_POST[$field])) {
                        $errors[$field] = "Only numbers (0-9) allowed";
                    } else {
                        $inputs[$i] = test_input($_POST[$field]);
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
    Input <?= $i ?>: 
    <input type="text" name="input<?= $i ?>" value="<?= $_POST["input$i"] ?? '' ?>">
    <span class="error"><?= $errors["input$i"] ?? '' ?></span>
    <br><br>
<?php endfor; ?>

<input type="submit" value="Submit">


<?php
echo "<h2>Your Input number:</h2>";
for ($i = 1; $i <= 10; $i++) {
    $field = "input$i";
    
    if (isset($_POST[$field]) && empty($errors[$field]) && $_POST[$field] !== "") {
        echo "Input $i: " . test_input($_POST[$field]) . "<br>";
    } 
    // else {
    //     echo "Input $i: Invalid or empty<br>";
    // }
}
?>

</form>
<h2>Groups Result:</h2>

<b>Group 1 (desc):</b> 
<?php echo isset($g1) ? $g1 : "No result yet"; ?>
<br>

<b>Group 2 (%3 desc):</b> 
<?php echo isset($g2) ? $g2 : "No result yet"; ?>
<br>

<b>Group 3 (%5 asc):</b> 
<?php echo isset($g3) ? $g3 : "No result yet"; ?>
<br>

</body>
</html>