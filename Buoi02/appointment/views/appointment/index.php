<?php

// Hàm tự định nghĩa để hiển thị trạng thái
function getStatusText($status)
{
    if ($status == 'pending') {
        return "Chờ duyệt";
    } elseif ($status == 'approved') {
        return "Đã duyệt";
    } elseif ($status == 'completed') {
        return "Đã hoàn thành";
    } elseif ($status == 'cancelled') {
        return "Đã hủy";
    } elseif ($status == 'rejected') {
        return "Từ chối";
    }

    return "Không xác định";
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Đặt lịch tư vấn</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 30px;
        }

        .container {
            width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
        }

        h2 {
            margin-top: 30px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        button {
            margin-top: 20px;
            padding: 12px 25px;
            background: #333;
            color: white;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #eee;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>HỆ THỐNG ĐẶT LỊCH TƯ VẤN</h1>

    <h2>Đặt lịch tư vấn</h2>

    <form method="POST" action="index.php?action=store">

        <label>Tên sinh viên</label>

        <input
            type="text"
            name="student_name"
            placeholder="Nhập tên sinh viên"
            required
        >

        <label>Tên giảng viên</label>

        <input
            type="text"
            name="lecturer_name"
            placeholder="Nhập tên giảng viên"
            required
        >

        <label>Ngày hẹn</label>

        <input
            type="date"
            name="appointment_date"
            required
        >

        <label>Giờ hẹn</label>

        <input
            type="time"
            name="appointment_time"
            required
        >

        <label>Chủ đề cần tư vấn</label>

        <input
            type="text"
            name="topic"
            placeholder="Ví dụ: Tư vấn đồ án PHP"
            required
        >

        <button type="submit">
            ĐẶT LỊCH
        </button>

    </form>


    <h2>Danh sách lịch hẹn</h2>

    <table>

        <tr>

            <th>STT</th>

            <th>Sinh viên</th>

            <th>Giảng viên</th>

            <th>Ngày</th>

            <th>Giờ</th>

            <th>Chủ đề</th>

            <th>Trạng thái</th>

        </tr>

        <?php foreach ($appointments as $index => $appointment): ?>

            <tr>

                <td>
                    <?= $index + 1 ?>
                </td>

                <td>
                    <?= htmlspecialchars($appointment['student_name']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($appointment['lecturer_name']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($appointment['appointment_date']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($appointment['appointment_time']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($appointment['topic']) ?>
                </td>

                <td>
                    <?= getStatusText($appointment['status']) ?>
                </td>

            </tr>

        <?php endforeach; ?>

    </table>

</div>

</body>

</html>