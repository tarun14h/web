<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $books = [
        "the_great_gatsby" => ["name" => "The Great Gatsby", "price" => 100],
        "nineteen_eightyfour" => ["name" => "1984", "price" => 120],
        "to_kill_a_mockingbird" => ["name" => "To Kill a Mockingbird", "price" => 90],
        "harry_potter" => ["name" => "Harry Potter", "price" => 150],
        "emergence" => ["name" => "Emergence", "price" => 110],
    ];

    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $address = htmlspecialchars(trim($_POST['address'] ?? ''));

    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $error = "All customer details are required.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = "Invalid 10-digit phone number.";
    } else {
        $orderedBooks = [];
        $total = 0;

        foreach ($books as $key => $book) {
            $qty = (int) ($_POST[$key] ?? 0);
            if ($qty > 0) {
                $cost = $qty * $book['price'];
                $orderedBooks[] = "{$book['name']} x $qty = ₹$cost";
                $total += $cost;
            }
        }

        if (empty($orderedBooks)) {
            $error = "Please select at least one book.";
        } else {
            echo "<h1>✅ Booking Successful</h1>";
            echo "<p>Thank you, <strong>$name</strong>! Here are your booking details:</p>";
            echo "<ul>";
            foreach ($orderedBooks as $item) echo "<li>$item</li>";
            echo "</ul>";
            echo "<p><strong>Total:</strong> ₹$total</p>";
            echo "<p><strong>Email:</strong> $email<br><strong>Phone:</strong> $phone<br><strong>Address:</strong> $address</p>";
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BookMyRead</title>
  <style>
    body { margin: 0; font-family: 'Segoe UI', sans-serif; background: white; color: #333; }
    nav { background-color: #4a90e2; padding: 1rem 0; text-align: center; }
    nav ul { list-style: none; margin: 0; padding: 0; }
    nav ul li { display: inline; margin: 0 20px; }
    nav ul li a { text-decoration: none; color: white; font-size: 1.1rem; font-weight: bold; cursor: pointer; }
    nav ul li a:hover { text-decoration: underline; }
    h1, h2 { text-align: center; }
    h1 { color: #2c3e50; font-size: 3rem; background-color: #d0e1f9; border: 2px solid #4a90e2; border-radius: 12px; padding: 1rem; width: 60%; margin: 2rem auto 1rem; }
    p { text-align: center; font-size: 1.2rem; }
    #bookFormContainer { max-width: 600px; margin: 2rem auto; padding: 20px; background: #f5f8ff; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    label { display: block; margin: 10px 0 5px; }
    input, textarea { width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc; }
    input[type="number"] { width: 60px; margin-left: 10px; }
    .book-item { display: flex; justify-content: space-between; align-items: center; margin: 8px 0; }
    #total { text-align: right; font-weight: bold; font-size: 1.2rem; }
    button { padding: 10px 20px; background-color: #4a90e2; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem; }
    button:hover { background-color: #357ABD; }
    footer { background-color: #f0f0f0; margin-top: 2rem; padding: 1.5rem; text-align: center; }
    .error { color: red; text-align: center; font-weight: bold; }
  </style>
</head>
<body>

  <nav>
    <ul>
      <li><a href="#home">Home</a></li>
      <li><a href="#form">Book Now</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
  </nav>

  <header id="home">
    <h1>📚 BookMyRead</h1>
    <p>Reserve your favorite books online instantly!</p>
  </header>

  <section id="form">
    <div id="bookFormContainer">
      <h2>Book Registration Form</h2>
      <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
      <form method="POST">
        <h3>Select Books</h3>
        <div class="book-item">The Great Gatsby - ₹100 <input type="number" min="0" value="0" name="the_great_gatsby"></div>
        <div class="book-item">1984 - ₹120 <input type="number" min="0" value="0" name="nineteen_eightyfour"></div>
        <div class="book-item">To Kill a Mockingbird - ₹90 <input type="number" min="0" value="0" name="to_kill_a_mockingbird"></div>
        <div class="book-item">Harry Potter - ₹150 <input type="number" min="0" value="0" name="harry_potter"></div>
        <div class="book-item">Emergence - ₹110 <input type="number" min="0" value="0" name="emergence"></div>

        <h3>Customer Details</h3>
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="tel" name="phone" placeholder="Phone Number" pattern="[0-9]{10}" required>
        <textarea name="address" placeholder="Address" rows="3" required></textarea>

        <button type="submit">Book Now</button>
      </form>
    </div>
  </section>

  <footer id="contact">
    <h2>Contact Us</h2>
    <p>Email: support@bookmyread.com</p>
    <p>Phone: +91 8966956945</p>
    <p>&copy; 2025 BookMyRead. All rights reserved.</p>
  </footer>

</body>
</html>