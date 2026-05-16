<?php
// Start a secure server session to manage portal authentication state tracking
session_start();

// Handle Secure Logout Action
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: test.php");
    exit();
}

// Production Security Configuration Profiles
$admin_username = "admin";
$admin_password = "password123";

$login_error = "";
if (isset($_POST['login'])) {
    if ($_POST['username'] === $admin_username && $_POST['password'] === $admin_password) {
        $_SESSION['authenticated'] = true;
    } else {
        $login_error = "Access Denied: Invalid security clearance parameters.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexusBook OS - Production Environment</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-main: #f8fafc; --sidebar-bg: #0f172a; --text-main: #1e293b;
            --text-muted: #64748b; --primary: #4f46e5; --primary-hover: #4338ca;
            --success: #10b981; --warning: #f59e0b; --danger: #ef4444;
            --card-bg: #ffffff; --border: #e2e8f0;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        
        /* Secure Portal Gateway CSS Layout */
        .login-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle at top right, #e0e7ff, var(--bg-main)); }
        .login-box { background: white; padding: 40px; border-radius: 20px; width: 100%; max-width: 420px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); border: 1px solid var(--border); text-align: center; }

        /* Core Workspace Layout Panel styling */
        body { background-color: var(--bg-main); color: var(--text-main); display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background-color: var(--sidebar-bg); color: white; padding: 24px; display: flex; flex-direction: column; }
        .sidebar-brand { font-size: 20px; font-weight: 700; margin-bottom: 40px; display: flex; align-items: center; gap: 10px; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px; color: #94a3b8; text-decoration: none; border-radius: 8px; margin-bottom: 8px; font-weight: 500; }
        .nav-item.active { background-color: #1e293b; color: white; }
        .main-content { flex: 1; padding: 40px; overflow-y: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
        .badge { background: #e0e7ff; color: var(--primary); padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }

        /* Operation Alerts UI elements */
        .alert { padding: 16px; border-radius: 12px; margin-bottom: 24px; font-weight: 500; display: flex; align-items: center; gap: 10px; }
        .alert-success { background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-danger { background-color: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; }

        /* Metric Aggregate Layout Component Cards */
        .metrics-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 40px; }
        .metric-card { background: var(--card-bg); padding: 24px; border-radius: 16px; border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .metric-info h4 { font-size: 13px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px; }
        .metric-info p { font-size: 28px; font-weight: 700; }
        .metric-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }

        /* Workspace Grid Split Containers */
        .workspace-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 40px; }
        .card { background: var(--card-bg); padding: 28px; border-radius: 16px; border: 1px solid var(--border); }
        .card-title { font-size: 18px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
        
        /* Modernized Form UI Field Controllers */
        .form-group { margin-bottom: 16px; text-align: left; }
        .form-group label { display: block; font-size: 13px; font-weight: 500; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; outline: none; background: #fff; color: var(--text-main); }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        .btn { width: 100%; padding: 12px; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; }
        .btn-primary { background-color: var(--primary); color: white; }
        .btn-success { background-color: var(--success); color: white; }

        /* Production Asset Ledger Data Tables styling */
        .table-container { background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border); overflow: hidden; margin-bottom: 32px; }
        .table-header { padding: 20px 24px; border-bottom: 1px solid var(--border); }
        .table-header h3 { font-size: 16px; font-weight: 600; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        .data-table th { padding: 14px 24px; background: #f8fafc; color: var(--text-muted); font-weight: 600; font-size: 12px; text-transform: uppercase; border-bottom: 1px solid var(--border); letter-spacing: 0.5px; }
        .data-table td { padding: 16px 24px; border-bottom: 1px solid var(--border); color: #334155; }
        .status-badge { padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center; gap: 4px; }
        .status-instock { background: #ecfdf5; color: #047857; }
        .status-lowstock { background: #fffbeb; color: #b45309; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true): ?>
    <div class="login-container">
        <div class="login-box">
            <div style="font-size: 32px; color: var(--primary); margin-bottom: 16px;"><i class="fa-solid fa-shield-halved"></i></div>
            <h2 style="font-weight: 700; margin-bottom: 8px;">NexusBook OS</h2>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 24px;">Please authenticate to access core system cluster</p>
            
            <?php if ($login_error): ?>
                <div class="alert alert-danger" style="padding: 10px; font-size: 13px; margin-bottom: 16px;"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $login_error; ?></div>
            <?php endif; ?>

            <form method="POST" action="test.php">
                <div class="form-group">
                    <label>Admin Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Username (admin)" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Security Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Password (password123)" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary" style="margin-top: 8px;">Authenticate Cluster</button>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-layer-group" style="color:#818cf8;"></i>
            <span>NexusBook OS</span>
        </div>
        <a href="#" class="nav-item active"><i class="fa-solid fa-chart-pie"></i> Dashboard Workspace</a>
        <a href="test.php?action=logout" class="nav-item" style="margin-top: auto; background: #27272a; color: #ef4444;"><i class="fa-solid fa-power-off"></i> Safe Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h1>Architecture Workspace</h1>
                <p style="color: var(--text-muted); font-size: 14px; margin-top:4px;">Production environment pulling relational engine entities in real time</p>
            </div>
            <span class="badge"><i class="fa-solid fa-circle" style="font-size:8px; margin-right:6px; color:var(--success);"></i> Live Core API Node</span>
        </div>

        <?php
        // Open live connection link channel to local database environment
        $conn = new mysqli("localhost", "root", "", "bookstore");
        if ($conn->connect_error) { die("<div class='alert alert-danger'>Connection Failure: " . $conn->connect_error . "</div>"); }

        // PIPELINE CORE CONTROLLER 1: Identity Registration Processor
        if (isset($_POST['submit_customer'])) {
            $first = $conn->real_escape_string($_POST['fname']);
            $last = $conn->real_escape_string($_POST['lname']);
            $email_input = $conn->real_escape_string($_POST['email']);
            
            $sql = "INSERT INTO Customers (FirstName, LastName, Email) VALUES ('$first', '$last', '$email_input')";
            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success'><i class='fa-solid fa-circle-check'></i> Identity Committed: Database engine has successfully processed customer registration.</div>";
            } else {
                echo "<div class='alert alert-danger'><i class='fa-solid fa-triangle-exclamation'></i> Pipeline Error: " . $conn->error . "</div>";
            }
        }

        // PIPELINE CORE CONTROLLER 2: Inventory Allocation Settlement Transaction Engine
        if (isset($_POST['submit_order'])) {
            $c_id = intval($_POST['cust_id']);
            $b_id = intval($_POST['book_id']);
            $qty = intval($_POST['qty']);
            
            // Check real stock levels left in table row allocation matching the selection
            $stock_check = $conn->query("SELECT Stock FROM Books WHERE BookID = $b_id")->fetch_assoc();
            
            if ($stock_check && $stock_check['Stock'] >= $qty) {
                // Execute transactional inserts and database metrics modifiers
                $conn->query("INSERT INTO Orders (CustomerID, BookID, Quantity) VALUES ($c_id, $b_id, $qty)");
                $conn->query("UPDATE Books SET Stock = Stock - $qty WHERE BookID = $b_id");
                echo "<div class='alert alert-success'><i class='fa-solid fa-circle-check'></i> Settlement Complete: Ledger receipt logged, storage warehouse count updated.</div>";
            } else {
                echo "<div class='alert alert-danger'><i class='fa-solid fa-ban'></i> Transaction Declined: Insufficient real stock left on targeted data shelf.</div>";
            }
        }

        // DYNAMIC BUSINESS AGGREGATION CALLS (No mock variables)
        $stats_books = $conn->query("SELECT SUM(Price * Stock) as inv_val FROM Books")->fetch_assoc();
        $stats_sales = $conn->query("SELECT SUM(Books.Price * Orders.Quantity) as revenue, COUNT(Orders.OrderID) as sales_count FROM Orders INNER JOIN Books ON Orders.BookID = Books.BookID")->fetch_assoc();
        
        $revenue = $stats_sales['revenue'] ? $stats_sales['revenue'] : 0.00;
        $sales_count = $stats_sales['sales_count'] ? $stats_sales['sales_count'] : 0;
        $inventory_valuation = $stats_books['inv_val'] ? $stats_books['inv_val'] : 0.00;
        ?>

        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-info"><h4>Gross Sales Revenue</h4><p>$<?php echo number_format($revenue, 2); ?></p></div>
                <div class="metric-icon" style="background:#ecfdf5; color:var(--success);"><i class="fa-solid fa-wallet"></i></div>
            </div>
            <div class="metric-card">
                <div class="metric-info"><h4>Total Orders Filled</h4><p><?php echo $sales_count; ?></p></div>
                <div class="metric-icon" style="background:#e0e7ff; color:var(--primary);"><i class="fa-solid fa-cart-shopping"></i></div>
            </div>
            <div class="metric-card">
                <div class="metric-info"><h4>Asset Floor Valuation</h4><p>$<?php echo number_format($inventory_valuation, 2); ?></p></div>
                <div class="metric-icon" style="background:#fffbeb; color:var(--warning);"><i class="fa-solid fa-boxes-stacked"></i></div>
            </div>
        </div>

        <div class="workspace-grid">
            <div class="card">
                <div class="card-title"><i class="fa-solid fa-user-plus" style="color:var(--primary);"></i> Register New Identity</div>
                <form method="POST" action="test.php">
                    <div class="form-group"><label>First Name</label><input type="text" class="form-control" name="fname" placeholder="e.g. Isaac" required></div>
                    <div class="form-group"><label>Last Name</label><input type="text" class="form-control" name="lname" placeholder="e.g. Kipngetich" required></div>
                    <div class="form-group"><label>Email Address</label><input type="email" class="form-control" name="email" placeholder="isaac@enterprise.com" required></div>
                    <button type="submit" name="submit_customer" class="btn btn-primary"><i class="fa-solid fa-server"></i> Commit Identity Record</button>
                </form>
            </div>

            <div class="card">
                <div class="card-title"><i class="fa-solid fa-cash-register" style="color:var(--success);"></i> Authorize Transaction</div>
                <form method="POST" action="test.php">
                    <div class="form-group">
                        <label>Active Identity Profile Selector (Real Database Records)</label>
                        <select class="form-control" name="cust_id" required>
                            <option value="">-- Select Verified Active Record --</option>
                            <?php
                            $customers_list = $conn->query("SELECT CustomerID, FirstName, LastName FROM Customers ORDER BY CustomerID DESC");
                            while ($c_row = $customers_list->fetch_assoc()) {
                                echo "<option value='".$c_row['CustomerID']."'>ID #".$c_row['CustomerID']." - ".$c_row['FirstName']." ".$c_row['LastName']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Target Inventory Catalog Asset (Live Product Registry)</label>
                        <select class="form-control" name="book_id" required>
                            <option value="">-- Choose Live Storage Unit --</option>
                            <?php
                            $books_list = $conn->query("SELECT BookID, Title, Price, Stock FROM Books WHERE Stock > 0");
                            while ($b_row = $books_list->fetch_assoc()) {
                                echo "<option value='".$b_row['BookID']."'>".$b_row['Title']." ($".$b_row['Price'].") [In Stock: ".$b_row['Stock']."]</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Transaction Volume (Quantity)</label>
                        <input type="number" class="form-control" name="qty" value="1" min="1" required>
                    </div>
                    <button type="submit" name="submit_order" class="btn btn-success"><i class="fa-solid fa-credit-card"></i> Process Settlement</button>
                </form>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header"><h3>📦 Warehouse Product Inventory Stock Levels</h3></div>
            <?php
            $res_books = $conn->query("SELECT BookID, Title, Price, Stock FROM Books");
            if ($res_books->num_rows > 0) {
                echo "<table class='data-table'><tr><th>Asset Token</th><th>Title Specification</th><th>Unit Price</th><th>Warehouse Available Balance</th></tr>";
                while($row = $res_books->fetch_assoc()) {
                    $is_low = $row["Stock"] <= 3;
                    $badge_class = $is_low ? 'status-lowstock' : 'status-instock';
                    $badge_icon = $is_low ? 'fa-triangle-exclamation' : 'fa-square-check';
                    echo "<tr>
                            <td><strong style='color:var(--primary);'>#".$row["BookID"]."</strong></td>
                            <td style='font-weight:500;'>".$row["Title"]."</td>
                            <td>$".number_format($row["Price"], 2)."</td>
                            <td><span class='status-badge ".$badge_class."'><i class='fa-solid ".$badge_icon."'></i> ".$row["Stock"]." units remaining</span></td>
                          </tr>";
                }
                echo "</table>";
            }
            ?>
        </div>

        <div class="table-container">
            <div class="table-header"><h3>📜 Confirmed Operational Transaction Receipts Ledger</h3></div>
            <?php
            $order_query = "SELECT Orders.OrderID, Customers.FirstName, Customers.LastName, Books.Title, Books.Price, Orders.Quantity 
                            FROM Orders 
                            INNER JOIN Customers ON Orders.CustomerID = Customers.CustomerID 
                            INNER JOIN Books ON Orders.BookID = Books.BookID ORDER BY Orders.OrderID DESC";
            $res_orders = $conn->query($order_query);
            if ($res_orders->num_rows > 0) {
                echo "<table class='data-table'><tr><th>Receipt Code</th><th>Identity Handle Name</th><th>Acquired Allocation Asset</th><th>Financial Volume Balance</th></tr>";
                while($row = $res_orders->fetch_assoc()) {
                    $total_item_cost = $row["Price"] * $row["Quantity"];
                    echo "<tr>
                            <td><code>TXN-".str_pad($row["OrderID"], 4, "0", STR_PAD_LEFT)."</code></td>
                            <td>".$row["FirstName"]." ".$row["LastName"]."</td>
                            <td>".$row["Title"]." <span style='color:var(--text-muted); font-size:12px;'>x".$row["Quantity"]."</span></td>
                            <td><strong style='color:var(--success);'>+$".number_format($total_item_cost, 2)."</strong></td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<div style='padding:24px; color:var(--text-muted); font-size:14px; text-align:center;'>No structural transactions committed to database infrastructure node ledger yet.</div>";
            }
            ?>
        </div>
    </div>
<?php endif; ?>

</body>
</html>