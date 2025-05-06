<?php

    session_start();
     
    if(!isset($_SESSION['route'])){
        $_SESSION['route'] = 'submit_blog.php';
    }
    else{
        unset($_SESSION['route']);
    }
    
    
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
    	// Redirect to the login page or handle accordingly
    	header("Location:login.php");
    	exit();
    }


?>


<?php include("includes/app_header.php"); ?>


<?php

include("includes/setup_mysql.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST["description"];
    $mood = $_POST["mood"];
    
    // Handle image upload
    $targetDir = "images/";
    $fileName = basename($_FILES["photo"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath);

    $user_id = $_SESSION['user_id'];

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO blog_posts (user_id, photo, description, mood, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isss", $user_id, $fileName, $description, $mood); 

    if ($stmt->execute()) {
        
         $loyaltyPoints = 5; // Points to add per post

        // Update user's loyalty balance
        $updateStmt = $conn->prepare("UPDATE user SET loyalty_balance = loyalty_balance + ? WHERE id = ?");
        $updateStmt->bind_param("ii", $loyaltyPoints, $user_id);
        $updateStmt->execute();
        $updateStmt->close();
      
        echo "<script>window.location.href='profile.php?task_completed=true';</script>";
    } else {
        echo "<script>alert('Error submitting task.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}

?>

<style>
    /* Custom styles */
    .container {
        max-width: 500px;
        margin: auto;
        padding: 20px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    #preview {
        width: 100%;
        height: 250px;
        border: 2px dashed #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        margin-bottom: 10px;
        border-radius: 5px;
        background-color: #f8f9fa;
        cursor: pointer;
    }
    #capture {
        display: none;
    }
    .mood-options svg {
        width: 50px;
        margin: 5px;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .mood-options img:hover {
        transform: scale(1.1);
    }
</style>
	


<!-- Page Content -->
    <div class="page-content">
        <div class="content-inner pt-0">
			<div class="container p-b50">
			
			
               <h2>📸 Describe the nature object surround you</h2>

              <form action="submit_blog.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate onsubmit="return validateMoodSelection();">
        
                    <!-- Take a Photo -->
                    <label for="capture" class="form-label">Take a Photo:</label>
                    <div id="preview" onclick="document.getElementById('capture').click()">📷 Click to Capture</div>
                    <input type="file" id="capture" name="photo" accept="image/*" capture="environment" required>
            
                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea id="description" name="description" rows="3" class="form-control" required></textarea>
                    </div>
            
                   <div class="mood-options text-center">
                        <input type="hidden" name="mood" id="selectedMood" value="">
                    
                        <svg onclick="setMood(this)" mood="Sad" width="45" height="45" fill="rgb(5, 168, 49)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM159.3 388.7c-2.6 8.4-11.6 13.2-20 10.5s-13.2-11.6-10.5-20C145.2 326.1 196.3 288 256 288s110.8 38.1 127.3 91.3c2.6 8.4-2.1 17.4-10.5 20s-17.4-2.1-20-10.5C340.5 349.4 302.1 320 256 320s-84.5 29.4-96.7 68.7zM144.4 208a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zm192-32a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/>
                        </svg>
                    
                        <svg onclick="setMood(this)" mood="Neutral" width="45" height="45" fill="rgb(5, 168, 49)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM176.4 176a32 32 0 1 1 0 64 32 32 0 1 1 0-64zm128 32a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zM160 336l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/>
                        </svg>
                    
                        <svg onclick="setMood(this)" mood="Happy" width="45" height="45" fill="rgb(5, 168, 49)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM164.1 325.5C182 346.2 212.6 368 256 368s74-21.8 91.9-42.5c5.8-6.7 15.9-7.4 22.6-1.6s7.4 15.9 1.6 22.6C349.8 372.1 311.1 400 256 400s-93.8-27.9-116.1-53.5c-5.8-6.7-5.1-16.8 1.6-22.6s16.8-5.1 22.6 1.6zM144.4 208a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zm192-32a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/>
                        </svg>
                    
                        <svg onclick="setMood(this)" mood="Excited" width="45" height="45" fill="rgb(5, 168, 49)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M10 9v2a1 1 0 0 1-2 0V9a1 1 0 0 1 2 0zm5-1a1 1 0 0 0-1 1v2a1 1 0 0 0 2 0V9a1 1 0 0 0-1-1zm8 4A11 11 0 1 1 12 1a11.013 11.013 0 0 1 11 11zm-2 0a9 9 0 1 0-9 9 9.01 9.01 0 0 0 9-9z"/>
                        </svg>
                    </div>
                    <div id="moodError" class="alert alert-danger d-none mt-2" role="alert">
                        ⚠️ Please select your mood before submitting.
                    </div>
            
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success w-100 mt-3">Submit</button>
            
                </form>
            			
            </div>    
		</div>
    </div>    
    <!-- Page Content End-->

<script>
   
    
    function setMood(selected) {
        // Reset all SVGs to green before setting new one
        document.querySelectorAll(".mood-options svg").forEach(svg => svg.setAttribute("fill", "rgb(5, 168, 49)"));
        
        // Set the selected one to red
        selected.setAttribute("fill", "grey");

        // Update the hidden input field with the selected mood
        document.getElementById("selectedMood").value = selected.getAttribute("mood");
    }

    document.getElementById('capture').addEventListener('change', function(event) {
        let file = event.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').innerHTML = `<img src="${e.target.result}" style="width:100%; height:100%; object-fit:cover; border-radius:5px;">`;
            };
            reader.readAsDataURL(file);
        }
    });
    
    function validateMoodSelection() {
        const mood = document.getElementById("selectedMood").value;
        const errorDiv = document.getElementById("moodError");
    
        if (mood === "") {
            errorDiv.classList.remove("d-none");
            return false; // prevent submission
        } else {
            errorDiv.classList.add("d-none"); // hide alert if mood selected
            return true; // allow submission
        }
    }

</script>


<?php include("includes/menu_bar.php"); ?>
	
<?php include("includes/app_footer.php"); ?>
	
