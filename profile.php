<?php
include('subdash.php');
include('database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT full_name, username, user_email, mobile_no, company_name, adress, reg_date, profile_pic FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($full_name, $username, $user_email, $mobile_no, $company_name, $address, $reg_date, $profile_pic);
$stmt->fetch();
$stmt->close();

$profile_pic = $profile_pic ? htmlspecialchars($profile_pic) : 'default-profile.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    
    <style>
        .profile-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            background: #fff;
        }
        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #007bff;
            cursor: pointer;
        }
        .profile-header {
            background: #007bff;
            color: white;
            padding: 20px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            text-align: center;
        }
        .profile-body {
            padding: 20px;
        }
        .table-profile {
            width: 100%;
            border-collapse: collapse;
        }
        .table-profile th, .table-profile td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .icon {
            color: #007bff;
            font-size: 20px;
            margin-right: 10px;
        }
        .btn-group {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-4" style="max-width: 700px;">
    <div class="profile-card">
        <div class="profile-header">
            <img src="<?php echo $profile_pic; ?>" 
                 class="profile-img mb-2" 
                 alt="Profile Picture"
                 data-bs-toggle="modal" 
                 data-bs-target="#profileModal">
            <h2><?php echo htmlspecialchars($full_name); ?></h2>
            <p class="text-light">@<?php echo htmlspecialchars($username); ?></p>
        </div>
        <div class="profile-body">
            <table class="table-profile">
                <tr>
                    <th><i class="bi bi-person-badge icon"></i>User ID:</th>
                    <td><?php echo $user_id; ?></td>
                </tr>
                <tr>
                    <th><i class="bi bi-envelope icon"></i>Email:</th>
                    <td><?php echo htmlspecialchars($user_email); ?></td>
                </tr>
                <tr>
                    <th><i class="bi bi-phone icon"></i>Mobile No:</th>
                    <td><?php echo htmlspecialchars($mobile_no); ?></td>
                </tr>
                <tr>
                    <th><i class="bi bi-building icon"></i>Company:</th>
                    <td><?php echo htmlspecialchars($company_name); ?></td>
                </tr>
                <tr>
                    <th><i class="bi bi-geo-alt icon"></i>Address:</th>
                    <td><?php echo htmlspecialchars($address); ?></td>
                </tr>
                <tr>
                    <th><i class="bi bi-calendar icon"></i>Joined on:</th>
                    <td><?php echo htmlspecialchars($reg_date); ?></td>
                </tr>
            </table>

            <div class="btn-group">
                <a href="profile_edit.php" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit Profile
                </a>
                <a href="changepassword.php" class="btn btn-danger">
                    <i class="bi bi-key"></i> Change Password
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Profile Picture Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profileModalLabel">Profile Picture</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img src="<?php echo $profile_pic; ?>" class="modal-img" alt="Full Profile Picture">
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
