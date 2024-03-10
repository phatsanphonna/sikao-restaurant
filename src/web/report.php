<?php
include '../lib/conn.php';

session_start();

if (!isset($_SESSION['user'])) {
  header('Location: ./signin.php');
}

if ($_SESSION['user']['user_role'] === 'CHEF') {
  header('Location: ./index.php');
}

$history = isset($_POST['history']) ? $_POST['history'] : 'today';

if ($history == 'today') {
  $sql = "SELECT * FROM bill WHERE created_at >= NOW() - INTERVAL 1 DAY AND total > 0 ORDER BY created_at ASC";
} else if ($history == 'past-1-day') {
  $sql = "SELECT * FROM bill WHERE created_at >= NOW() - INTERVAL 1 DAY AND total > 0 ORDER BY created_at ASC";
} else if ($history == 'past-3-day') {
  $sql = "SELECT * FROM bill WHERE created_at >= NOW() - INTERVAL 3 DAY AND total > 0 ORDER BY created_at ASC";
} else if ($history == 'past-5-day') {
  $sql = "SELECT * FROM bill WHERE created_at >= NOW() - INTERVAL 5 DAY AND total > 0 ORDER BY created_at ASC";
} else if ($history == 'past-1-week') {
  $sql = "SELECT * FROM bill WHERE created_at >= NOW() - INTERVAL 1 WEEK AND total > 0 ORDER BY created_at ASC";
} else if ($history == 'past-1-month') {
  $sql = "SELECT * FROM bill WHERE created_at >= NOW() - INTERVAL 1 MONTH AND total > 0 ORDER BY created_at ASC";
} else if ($history == 'past-3-month') {
  $sql = "SELECT * FROM bill WHERE created_at >= NOW() - INTERVAL 3 MONTH AND total > 0 ORDER BY created_at ASC";
}

$bills = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

$total = 0;

foreach ($bills as $bill) {
  $total += $bill['total'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>รายงานยอดขาย - Gin Kao Restaurant</title>
  <?php include '../lib/components/Header.php'; ?>
</head>

<body>
  <?php include '../lib/components/Navbar.php'; ?>

  <main class="p-4 flex flex-col gap-6 container mx-auto">
    <h2 class="text-secondary text-left text-4xl font-bold">
      สรุปยอดขาย
    </h2>

    <form method="POST" action="." class="flex gap-2 items-center">
      <label for="history" class="text-xl font-medium">ยอดขายของ : </label>
      <select id='history' name="history" class="text-center border-surface rounded-full" onchange="this.form.submit()">
        <option value="today" <?php if ($history == 'today') echo 'selected' ?>>วันนี้</option>
        <option value="past-1-day" <?php if ($history == 'past-1-day') echo 'selected' ?>>ย้อนหลัง 1 วัน</option>
        <option value="past-3-day" <?php if ($history == 'past-3-day') echo 'selected' ?>>ย้อนหลัง 3 วัน</option>
        <option value="past-5-day" <?php if ($history == 'past-5-day') echo 'selected' ?>>ย้อนหลัง 5 วัน</option>
        <option value="past-1-week" <?php if ($history == 'past-1-week') echo 'selected' ?>>ย้อนหลัง 1 สัปดาห์</option>
        <option value="past-1-month" <?php if ($history == 'past-1-month') echo 'selected' ?>>ย้อนหลัง 1 เดือน</option>
        <option value="past-3-month" <?php if ($history == 'past-3-month') echo 'selected' ?>>ย้อนหลัง 3 เดือน</option>
      </select>
    </form>

    <div class="grid grid-cols-2 gap-4 h-80">
      <div class="bg-white rounded-lg shadow-lg p-4 border">
        <h4 class="text-primary text-2xl text-center font-bold">
          <?php
          echo "สรุปยอดขาย";

          if ($history == 'today') {
            echo 'วันนี้';
          } else if ($history == 'past-1-day') {
            echo 'ย้อนหลัง 1 วัน';
          } else if ($history == 'past-3-day') {
            echo 'ย้อนหลัง 3 วัน';
          } else if ($history == 'past-5-day') {
            echo 'ย้อนหลัง 5 วัน';
          } else if ($history == 'past-1-week') {
            echo 'ย้อนหลัง 1 สัปดาห์';
          } else if ($history == 'past-1-month') {
            echo 'ย้อนหลัง 1 เดือน';
          } else if ($history == 'past-3-month') {
            echo 'ย้อนหลัง 3 เดือน';
          }
          ?>
        </h4>

        <div class="grid place-items-center h-full">
          <p class="text-6xl font-bold">
            <?php
            echo "฿" . number_format($total, 2);
            ?>
          </p>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-lg p-4 border flex flex-col justify-center items-center">
        <?php if ($history == 'today') { ?>
          <p class="text-lg text-center">ยอดขายประจำวันไม่สามารถแสดงเป็นกราฟได้</p>
        <?php } else { ?>
          <canvas id="reportChart"></canvas>
        <?php } ?>
      </div>
    </div>
  </main>

  <?php
    $labels = [];
    foreach ($bills as $bill) {
      if (!isset($labels[date('d/m', strtotime($bill['created_at']))])) {
        $labels[] = date('d/m', strtotime($bill['created_at']));
      }
    }

    $data = [];
    foreach ($bills as $bill) {
      if (isset($data[date('d/m', strtotime($bill['created_at']))])) {
        $data[date('d/m', strtotime($bill['created_at']))] += $bill['total'];
      } else {
        $data[date('d/m', strtotime($bill['created_at']))] = $bill['total'];
      }
    }
    ?>

  <script defer>
    const ctx = document.getElementById('reportChart')

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
          label: 'ยอดขาย (บาท)',
          data: <?php echo json_encode($data); ?>,
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
</body>

</html>