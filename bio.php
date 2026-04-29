<?php
require __DIR__.'/vendor/autoload.php';

if (!isset($_COOKIE['SessionKey'])) {
    header('Location: login.html');
    exit();
}

$client = new MongoDB\Client("mongodb://100.120.179.21:27017/");
$db = $client->survivalists_db;
$collection = $db->reg_users;

$user = $collection->findOne(['keySession' => $_COOKIE['SessionKey']]);
$bio = $user['bio'] ?? []; //if users dont have info, we use emopty array
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Bio</title>

    <!-- here im adding the bootstrap to make it responsive-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* I picked this font but we can change it later on */
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap');

        body {
            background: #FDF5DF;
	    font-family: 'Playfair Display', serif;
        }
        .card {
            border-radius: 15px;
            border: none;
	    box-shadow: 5px 5px 10px;
        }
 	h4 {
	    font-weight: normal;
	    color: 1a1a1a;

 	}
        form-control {
	    font-family: 'Playfair Display', sans-serif;
            font-size: 13px;
             backgroun-color; #fcfcfc;
            border: 1px solid #ddd;
        }
        .btn-primary {
	    background-color: #1a1a1a;
	    border: none;
            padding: 10px;
	    font-family: 'Playfair Display', sans-serif;
	    font-weight: bold;
	     
        }
        .btn-primary:hover {
	   background-color: #F92C85;
           color: white;
        }
    </style>
</head>

<body>

<!-- i used this reference to understand/implement bootstrap css better: https://getbootstrap.com/docs/3.4/css/-->
<div class="container py-5">
    <div class="row justify-content-center">
         <div class="col-md-8 col-lg-6">

           <div class="card shadow">
                <div class="card-body">

                    <h4 class="mb-4 text-center">SocialTune User Bio</h4>

                     <div id="alertBox"></div>

                     <form id="bioForm" method="POST" action="saveBio.php">

                        <div class="mb-3">
                            <label>Name</label>
                             <input type="text" name="name" class="form-control"
                                  value="<?= $bio['name'] ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                             <label>Email</label>
                             <input type="email" name="email" class="form-control"
                                   value="<?= $bio['email'] ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                             <label>Date of Birth</label>
                             <input type="date" name="dob" class="form-control"
                                    value="<?= $bio['dob'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                             <label>Age</label>
                             <input type="number" name="age" class="form-control"
                                   value="<?= $bio['age'] ?? '' ?>" readonly>   <!-- its read since age is calculated automatically -->
                        </div>

                        <div class="mb-3">
                            <label>Facebook</label>
                            <input type="text" name="facebook" class="form-control"
                                   value="<?= $bio['facebook'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label>Instagram</label>
                            <input type="text" name="instagram" class="form-control"
                                   value="<?= $bio['instagram'] ?? '' ?>">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Save Bio
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script>

// in this part i handle the age calc by doing it automatically. I used this reference: https://www.slingacademy.com/article/calculating-age-or-time-spans-from-birthdates-in-javascript/
document.querySelector('input[name="dob"]').addEventListener('change', function() {
    let dob = new Date(this.value);
    let today = new Date();

    let age = today.getFullYear() - dob.getFullYear();
     let month = today.getMonth() - dob.getMonth();

    if (month < 0 || (month === 0 && today.getDate() < dob.getDate())) {
         age--;
    }

    document.querySelector('input[name="age"]').value = age;
});

</script>

</body>
</html>
