<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

include '../db.php';
include '../includes/logger.php';

$user_id = (int)$_SESSION['user_id'];

// ---------- Handle Review Submission ----------
$review_message = '';
if(isset($_POST['submit_review'])){
    $service_id = (int)($_POST['service_id'] ?? 0);
    $rating = (int)($_POST['rating'] ?? 0);
    $review_text = trim($_POST['review_text'] ?? '');

    if($service_id <= 0){
        $review_message = 'Invalid service.';
    } elseif($rating < 1 || $rating > 5){
        $review_message = 'Rating must be between 1 and 5.';
    } elseif($review_text === ''){
        $review_message = 'Please write a review.';
    } else {
        // Ensure customer has a completed appointment for this service
        $has_completed = mysqli_fetch_assoc(mysqli_query($conn, "
            SELECT COUNT(*) AS total
            FROM appointments a
            WHERE a.user_id='{$user_id}'
              AND a.service_id='{$service_id}'
              AND a.status='Completed'
        "))['total'] ?? 0;

        if((int)$has_completed <= 0){
            $review_message = 'You can only review services you have completed.';
        } else {
            // Reviews table might not exist in your SQL dump.
            // We'll try insert into reviews if it exists.
            $inserted = mysqli_query($conn, "
                INSERT INTO reviews (user_id, service_id, rating, review_text, created_at)
                VALUES ('{$user_id}', '{$service_id}', '{$rating}', '" . mysqli_real_escape_string($conn, $review_text) . "', NOW())
            ");

            if($inserted){
                logAction($conn, $user_id, $_SESSION['role'], 'Submitted service review');
                $review_message = 'Review submitted successfully!';
            } else {
                $review_message = 'Could not submit review. (Database table reviews may be missing)';
            }
        }
    }
}

// ---------- Load Services ----------
$services = mysqli_query($conn, "SELECT * FROM services ORDER BY service_name ASC");

// ---------- Load Existing Reviews (if table exists) ----------
$existing_reviews = [];
$reviews_query = mysqli_query($conn, "
    SELECT r.*, s.service_name
    FROM reviews r
    JOIN services s ON s.id = r.service_id
    WHERE r.user_id = '{$user_id}'
    ORDER BY r.created_at DESC
");

if($reviews_query){
    while($row = mysqli_fetch_assoc($reviews_query)){
        $existing_reviews[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Reviews | Glow & Style Salon</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">

    <style>
        body { background: #fff7fa; }
        .content { margin-left: 0; padding: 0; }
        .reviews-page { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .reviews-card {
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
            margin-top: 20px;
        }
        .section-header h2 {
            font-family: 'Playfair Display', serif;
            text-align: center;
            font-size: 28px;
            margin: 0;
        }
        .underline { width: 50px; height: 3px; background: #ff8fab; margin: 10px auto 25px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 700px){ .form-row { grid-template-columns: 1fr; } }

        label { font-weight: 600; font-size: 14px; color: #444; display:block; margin-bottom: 8px; }
        select, textarea, input[type="number"] {
            width: 100%; padding: 12px 18px; border: 1px solid #eee; border-radius: 50px;
            font-family: 'Poppins', sans-serif; background: #fdfdfd;
        }
        textarea { border-radius: 16px; resize: vertical; min-height: 120px; padding: 14px 18px; }

        .submit-btn {
            width: 100%; background: #ff8fab; color: #fff; border: none; padding: 14px;
            border-radius: 50px; font-weight: 700; cursor: pointer; margin-top: 18px;
        }

        .notice { background:#fff0f3; border-left:4px solid #ff8fab; padding:12px 14px; border-radius:12px; color:#444; margin-bottom: 18px; }
        .review-item { padding: 14px 0; border-bottom: 1px solid #f0f0f0; }
        .review-item:last-child { border-bottom: none; }
        .stars { color: #ff8fab; font-weight: 700; }
    </style>
</head>
<body>
    <div class="reviews-page">
        <div class="reviews-card">
            <div class="section-header">
                <h2>Service Review</h2>
                <div class="underline"></div>
            </div>

            <?php if($review_message !== ''): ?>
                <div class="notice"><?php echo htmlspecialchars($review_message); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-row">
                    <div>
                        <label>Service</label>
                        <select name="service_id" required>
                            <option value="">Select a service...</option>
                            <?php while($s = mysqli_fetch_assoc($services)): ?>
                                <option value="<?php echo (int)$s['id']; ?>"><?php echo htmlspecialchars($s['service_name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label>Rating (1 - 5)</label>
                        <input type="number" name="rating" min="1" max="5" value="5" required>
                    </div>
                </div>

                <div style="margin-top: 16px;">
                    <label>Your Review</label>
                    <textarea name="review_text" placeholder="Type your experience..." required></textarea>
                </div>

                <!-- This is the required submit button + typing area + rating field -->
                <button type="submit" name="submit_review" class="submit-btn">Submit Review</button>
            </form>
        </div>

        <div class="reviews-card">
            <h3 style="font-family:'Playfair Display', serif; margin:0 0 10px 0;">Your Reviews</h3>
            <?php if(empty($existing_reviews)): ?>
                <p style="color:#666;">No reviews yet.</p>
            <?php else: ?>
                <?php foreach($existing_reviews as $r): ?>
                    <div class="review-item">
                        <div style="display:flex; justify-content:space-between; gap: 12px;">
                            <div style="font-weight:700;"><?php echo htmlspecialchars($r['service_name']); ?></div>
                            <div class="stars">★ <?php echo (int)$r['rating']; ?>/5</div>
                        </div>
                        <div style="margin-top: 8px; color:#444;"><?php echo nl2br(htmlspecialchars($r['review_text'])); ?></div>
                        <div style="margin-top: 6px; font-size: 12px; color:#999;"><?php echo htmlspecialchars($r['created_at']); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

