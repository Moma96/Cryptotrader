<?php
    $error = $this->session->flashdata('error');
    if ($error) {
        echo "<div id=\"error-alert\" class=\"alert alert-danger\">
            <strong>$error</strong>
            <span class=\"alert-closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span>
        </div>";
    }
    $success = $this->session->flashdata('success');
    if ($success) {
        echo "<div id=\"success-alert\" class=\"alert alert-success\">
            <strong>$success</strong>
            <span class=\"alert-closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span>
        </div>";
    }
?>

